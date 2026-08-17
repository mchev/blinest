<script setup>
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Volume from '@/Components/Volume.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  room: Object,
  channel: String,
  currentTime: Number,
  initialTrack: Object, // Track currently playing when component mounts
  initialRound: Object, // Round currently active when component mounts
})

const input = ref(null)
const track = ref(props.initialTrack || null)
const round = ref(props.initialRound || null)
const text = ref('')
const words = ref([])
const message = ref(null)
const answers = ref([])
const { auth } = usePage().props
const user = auth.user
// Input is disabled only if there's no active track
const inputDisabled = computed(() => !track.value || !round.value)
const autoFocus = ref(localStorage.getItem('autoFocus') !== 'false')
const userHasInteracted = ref(false) // Track if user has manually interacted with input

// Non-blocking focus - only if autofocus is enabled and user hasn't manually interacted
const attemptAutoFocus = () => {
  // Never force focus if user has manually interacted or autofocus is off
  if (!autoFocus.value || userHasInteracted.value) {
    return
  }

  if (input.value && !inputDisabled.value) {
    // Use requestAnimationFrame to avoid blocking user interactions
    requestAnimationFrame(() => {
      // Double-check conditions haven't changed
      if (input.value && !inputDisabled.value && autoFocus.value && !userHasInteracted.value) {
        // Only focus if input doesn't already have focus
        if (document.activeElement !== input.value) {
          input.value.focus()
          // Only select if field is empty
          if (!text.value || text.value.length === 0) {
            requestAnimationFrame(() => {
              if (input.value && (!text.value || text.value.length === 0)) {
                input.value.select()
              }
            })
          }
        }
      }
    })
  }
}

// Watch for initial props changes (when joining a room in progress)
// Use flush: 'post' to avoid blocking input during rapid typing
watch(
  () => [props.initialTrack, props.initialRound],
  ([newTrack, newRound]) => {
    if (newTrack && newRound && (!track.value || track.value.id !== newTrack.id)) {
      track.value = newTrack
      round.value = newRound
      // Reset interaction flag when receiving initial track
      userHasInteracted.value = false
      // Attempt autofocus if enabled (non-blocking)
      attemptAutoFocus()
    }
  },
  { immediate: true, flush: 'post' },
)

const toggleAutoFocus = () => {
  autoFocus.value = !autoFocus.value
  localStorage.setItem('autoFocus', autoFocus.value)
}

const showMessage = (data) => {
  message.value = data
  // Hints should display longer (5 seconds) than other messages (1.6 seconds)
  const duration = data?.type === 'hint' ? 5000 : 1600
  setTimeout(() => {
    message.value = null
  }, duration)
}

// Debounce flag to prevent multiple rapid submissions
const isSubmitting = ref(false)

const check = () => {
  // Fast validation - return immediately if conditions not met
  if (isSubmitting.value || inputDisabled.value || text.value.length < 1 || !track.value || !round.value) return

  const currentText = text.value.trim()
  if (currentText.length < 1) return

  // Mark as submitting immediately to prevent double submissions
  isSubmitting.value = true

  // Store what was submitted - preserve original text including spaces
  const submittedText = text.value // Keep original, not trimmed, to preserve user input

  // Fire request immediately without waiting
  const requestPromise = axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, {
    text: currentText,
    words: words.value,
    currentTime: props.currentTime,
  })

  // DON'T clear input here - wait for server response to avoid losing user input
  // The input will be cleared only after successful response or if user typed something new

  requestPromise
    .then((response) => {
      // Update state asynchronously to not block input
      if (response.data.good_answers && response.data.good_answers.length > 0) {
        answers.value.push(...response.data.good_answers)
      }
      words.value = response.data.words || []
      showMessage(response.data.message)

      // Only clear input if text still matches what we submitted (user didn't continue typing)
      // This preserves rapid typing where user submits and immediately types next answer
      const currentTextValue = text.value.trim()
      if (currentTextValue === currentText || currentTextValue === submittedText.trim()) {
        // Safe to clear - user hasn't started typing next answer yet
        text.value = ''

        // Maintain focus for next input
        nextTick(() => {
          requestAnimationFrame(() => {
            if (input.value && !inputDisabled.value) {
              input.value.focus()
            }
          })
        })
      }
      // If text changed, user already started typing next answer - preserve it, don't clear
    })
    .catch((error) => {
      console.error('Error checking answer:', error)

      // Always reset submitting flag, even on error
      isSubmitting.value = false

      const status = error.response?.status
      const isNetworkOrThrottle = !error.response || status === 429
      const isConflict = status === 409

      if (isNetworkOrThrottle) {
        showMessage({ type: 'bad', body: __('Connection problem, please try again') })
      } else if (isConflict) {
        showMessage({ type: 'bad', body: __('The round has changed, try again on the next track') })
        const currentTextValue = text.value.trim()
        if (currentTextValue === currentText || currentTextValue === submittedText.trim()) {
          text.value = ''
        }
      }

      // Restore text on error so user can retry (unless validation or conflict)
      if (status !== 400 && !isConflict) {
        const currentTextValue = text.value.trim()
        if (currentTextValue === '' || currentTextValue === currentText) {
          text.value = submittedText
        }
      } else if (status === 400) {
        const currentTextValue = text.value.trim()
        if (currentTextValue === currentText || currentTextValue === submittedText.trim()) {
          text.value = ''
        }
      }

      nextTick(() => {
        requestAnimationFrame(() => {
          if (input.value && !inputDisabled.value) {
            input.value.focus()
          }
        })
      })
    })
    .finally(() => {
      // Reset flag after request completes (if not already reset in catch)
      if (isSubmitting.value) {
        isSubmitting.value = false
      }
    })
}

const pastedAnswer = (event) => {
  event.preventDefault()
  text.value = "Je copie colle et c'est mal. Je copie colle et c'est mal."
}

onMounted(() => {
  // If we have initial track/round, attempt autofocus
  if (track.value && round.value) {
    attemptAutoFocus()
  }

  Echo.join(props.channel)
    .listen('TrackPlayed', (e) => {
      if (e.room) {
        Object.assign(props.room, e.room)
      }
      round.value = e.round
      track.value = e.track
      answers.value = []
      text.value = ''
      // Reset interaction flag on new track - allow autofocus again
      userHasInteracted.value = false
      // Attempt autofocus on new track (non-blocking)
      attemptAutoFocus()
    })
    .listen('TrackEnded', () => {
      track.value = null
      round.value = null
      text.value = ''
      words.value = []
    })
    .listen('UserHasFoundAllTheAnswers', (e) => {
      if (e.user === user) {
        // Don't disable input, just prevent further submissions
        // The input will be disabled automatically via computed when track ends
      }
    })
})

const messageClass = computed(() => ({
  'bg-brand-accent-dark': message.value?.type === 'good',
  'bg-brand-secondary text-brand-midnight': message.value?.type === 'almost',
  'bg-brand-primary': message.value?.type === 'bad',
  'bg-brand-deep border border-brand-accent/30': message.value?.type === 'hint',
}))

const isAnswerFound = (answerId) => answers.value.some((a) => a.id === answerId)

const getFoundAnswer = (answerId) => answers.value.find((a) => a.id === answerId)
</script>

<template>
  <div class="room-user-input w-full space-y-2">
    <form class="m-0 flex w-full items-center justify-center p-0" @submit.prevent="check">
      <div class="relative flex w-full flex-col">
        <div class="room-input-wrap">
          <div class="room-input-field">
            <input ref="input" v-model="text" type="text" class="room-input" :placeholder="__('Any idea?')" :aria-label="__('Any idea?')" tabindex="0" @paste.prevent="pastedAnswer" @drop.prevent="pastedAnswer" @focus="userHasInteracted = true" @click="userHasInteracted = true" @keydown.enter.prevent="check" autocomplete="off" maxlength="255" :disabled="inputDisabled || isSubmitting" />
          </div>

          <div class="room-input-actions">
            <Dropdown placement="bottom-end">
              <button type="button" class="room-input-settings" :aria-label="__('Settings')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </button>

              <template #dropdown>
                <div class="w-64 py-2">
                  <div class="px-4 py-2">
                    <div class="mb-3 flex items-center justify-between">
                      <label class="text-sm text-white/70">{{ __('Auto-focus') }}</label>
                      <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200" :class="autoFocus ? 'bg-brand-accent-dark' : 'bg-brand-midnight'" :aria-pressed="autoFocus" @click="toggleAutoFocus">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="autoFocus ? 'translate-x-6' : 'translate-x-1'" />
                      </button>
                    </div>

                    <div class="flex items-center justify-between">
                      <Volume class="w-full" />
                    </div>
                  </div>
                </div>
              </template>
            </Dropdown>

            <button type="submit" class="room-input-submit" :disabled="inputDisabled || !text.trim() || isSubmitting" :aria-label="__('Send')">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
            </button>
          </div>
        </div>

        <div class="room-answer-feedback" aria-live="polite" aria-atomic="true">
          <transition name="fade">
            <blockquote v-if="message" class="room-answer-feedback__message" :class="messageClass">
              <Icon v-if="message.type === 'hint'" name="hint" class="h-4 w-4 flex-shrink-0 text-brand-secondary" />
              <span>{{ message.body }}</span>
            </blockquote>
          </transition>
        </div>
      </div>
    </form>

    <div class="room-answer-chips-row relative min-w-0">
      <transition-group name="fade-slide" tag="ul" v-if="track" class="room-answer-chips flex flex-wrap gap-2 text-sm sm:gap-4">
        <li v-for="answer in track.answers" :key="answer.id" class="room-answer-chip" :class="{ 'room-answer-chip--found': isAnswerFound(answer.id) }">
          <template v-if="isAnswerFound(answer.id)">
            <span v-if="getFoundAnswer(answer.id)?.type?.svg_icon" class="mr-2 text-white/90" v-html="getFoundAnswer(answer.id).type.svg_icon"></span>
            <span class="font-semibold text-white">{{ getFoundAnswer(answer.id)?.value || answer.value }}</span>
          </template>
          <template v-else>
            <span class="font-medium">{{ __(answer.name) }} ?</span>
          </template>
        </li>
      </transition-group>
    </div>
  </div>
</template>
