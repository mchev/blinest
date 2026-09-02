<script setup>
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Volume from '@/Components/Volume.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Icon from '@/Components/Icon.vue'
import { useAnswerSubmissionQueue } from '@/composables/useAnswerSubmissionQueue'
import { useAnswerFeedbackSounds } from '@/composables/useAnswerFeedbackSounds'

const props = defineProps({
  room: Object,
  channel: String,
  currentTime: Number,
  initialTrack: Object,
  initialRound: Object,
})

const input = ref(null)
const track = ref(props.initialTrack || null)
const round = ref(props.initialRound || null)
const text = ref('')
const words = ref([])
const answers = ref([])
const feedbackFlash = ref(null)
const hintMessage = ref(null)
const flashingAnswerIds = ref([])
const page = usePage()
const { auth } = page.props
const user = auth.user

const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation

  Object.keys(replace).forEach((replaceKey) => {
    result = result.replace(`:${replaceKey}`, replace[replaceKey])
  })

  return result
}
const inputDisabled = computed(() => !track.value || !round.value)
const autoFocus = ref(localStorage.getItem('autoFocus') !== 'false')
const answerSounds = ref(localStorage.getItem('answerSounds') !== 'false')
const userHasInteracted = ref(false)
const isComposing = ref(false)
const { playSend, playGood, playAlmost, playBad, primeAnswerFeedbackAudio } = useAnswerFeedbackSounds()

let feedbackTimer = null
let hintTimer = null

const clearFeedbackTimer = () => {
  if (feedbackTimer) {
    clearTimeout(feedbackTimer)
    feedbackTimer = null
  }
}

const clearHintTimer = () => {
  if (hintTimer) {
    clearTimeout(hintTimer)
    hintTimer = null
  }
}

const attemptAutoFocus = () => {
  if (!autoFocus.value || userHasInteracted.value) {
    return
  }

  if (input.value && !inputDisabled.value) {
    requestAnimationFrame(() => {
      if (input.value && !inputDisabled.value && autoFocus.value && !userHasInteracted.value) {
        if (document.activeElement !== input.value) {
          input.value.focus()
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

watch(
  () => [props.initialTrack, props.initialRound],
  ([newTrack, newRound]) => {
    if (newTrack && newRound && (!track.value || track.value.id !== newTrack.id)) {
      track.value = newTrack
      round.value = newRound
      userHasInteracted.value = false
      attemptAutoFocus()
    }
  },
  { immediate: true, flush: 'post' },
)

const toggleAutoFocus = () => {
  autoFocus.value = !autoFocus.value
  localStorage.setItem('autoFocus', autoFocus.value)
}

const toggleAnswerSounds = () => {
  answerSounds.value = !answerSounds.value
  localStorage.setItem('answerSounds', answerSounds.value ? 'true' : 'false')

  if (answerSounds.value) {
    primeAnswerFeedbackAudio()
    playBad()
  }
}

const playResultSound = (type) => {
  if (type === 'good') {
    playGood()
  } else if (type === 'almost') {
    playAlmost()
  } else if (type === 'bad') {
    playBad()
  }
}

const flashAnswerChips = (goodAnswers) => {
  const ids = goodAnswers.map((answer) => answer.id)
  flashingAnswerIds.value = [...new Set([...flashingAnswerIds.value, ...ids])]

  window.setTimeout(() => {
    flashingAnswerIds.value = flashingAnswerIds.value.filter((id) => !ids.includes(id))
  }, 650)
}

const FEEDBACK_DURATIONS = {
  good: 700,
  almost: 1000,
  bad: 550,
}

const showFeedback = (type) => {
  if (!['good', 'bad', 'almost'].includes(type)) {
    return
  }

  clearFeedbackTimer()
  feedbackFlash.value = type
  playResultSound(type)

  feedbackTimer = window.setTimeout(() => {
    feedbackFlash.value = null
  }, FEEDBACK_DURATIONS[type])
}

const showHint = (body) => {
  clearHintTimer()
  hintMessage.value = body

  hintTimer = window.setTimeout(() => {
    hintMessage.value = null
  }, 5000)
}

const onServerMessage = (data) => {
  if (data.type === 'hint') {
    showHint(data.body)

    return
  }

  showFeedback(data.type)
}

const { submit, reset, pendingCount } = useAnswerSubmissionQueue({
  getRound: () => round.value,
  getTrack: () => track.value,
  getCurrentTime: () => props.currentTime,
  getWords: () => words.value,
  setWords: (nextWords) => {
    words.value = nextWords
  },
  onGoodAnswers: (goodAnswers) => {
    answers.value.push(...goodAnswers)
    flashAnswerChips(goodAnswers)
  },
  onMessage: onServerMessage,
  isDisabled: () => inputDisabled.value,
})

const clearInput = () => {
  text.value = ''

  if (input.value) {
    input.value.value = ''
  }
}

const check = () => {
  if (inputDisabled.value || isComposing.value) {
    return
  }

  const submittedText = text.value.trim()

  if (submittedText.length < 1) {
    return
  }

  clearInput()

  const didSubmit = submit(submittedText)

  if (!didSubmit) {
    if (!inputDisabled.value && round.value && track.value) {
      return
    }

    text.value = submittedText

    return
  }

  primeAnswerFeedbackAudio()
  playSend()

  nextTick(() => {
    requestAnimationFrame(() => {
      if (input.value && !inputDisabled.value) {
        input.value.focus()
      }
    })
  })
}

const onInputInteract = () => {
  userHasInteracted.value = true
  primeAnswerFeedbackAudio()
}

const onInputKeydown = () => {
  primeAnswerFeedbackAudio()
}

const pastedAnswer = (event) => {
  event.preventDefault()
  showFeedback('bad')
}

const resetFeedback = () => {
  clearFeedbackTimer()
  clearHintTimer()
  feedbackFlash.value = null
  hintMessage.value = null
  flashingAnswerIds.value = []
}

onMounted(() => {
  if (track.value && round.value) {
    attemptAutoFocus()
  }

  Echo.join(props.channel)
    .listen('TrackPlayed', (e) => {
      if (e.room) {
        Object.assign(props.room, e.room)
      }
      resetFeedback()
      reset()
      round.value = e.round
      track.value = e.track
      answers.value = []
      text.value = ''
      words.value = []
      userHasInteracted.value = false
      attemptAutoFocus()
    })
    .listen('TrackEnded', () => {
      resetFeedback()
      reset()
      track.value = null
      round.value = null
      text.value = ''
      words.value = []
    })
    .listen('UserHasFoundAllTheAnswers', (e) => {
      if (e.user === user) {
        // Input disables automatically when track ends
      }
    })
})

const feedbackWrapClass = computed(() => ({
  'room-input-wrap--flash-good': feedbackFlash.value === 'good',
  'room-input-wrap--flash-almost': feedbackFlash.value === 'almost',
  'room-input-wrap--flash-bad': feedbackFlash.value === 'bad',
}))

const feedbackMessage = computed(() => {
  switch (feedbackFlash.value) {
    case 'good':
      return __('Correct answer')
    case 'almost':
      return __('Almost!')
    case 'bad':
      return __('Wrong!')
    default:
      return ''
  }
})

const isAnswerFound = (answerId) => answers.value.some((a) => a.id === answerId)

const getFoundAnswer = (answerId) => answers.value.find((a) => a.id === answerId)

const isAnswerFlashing = (answerId) => flashingAnswerIds.value.includes(answerId)
</script>

<template>
  <div class="room-user-input w-full space-y-2">
    <form class="m-0 flex w-full items-center justify-center p-0" @submit.prevent="check">
      <div class="relative flex w-full flex-col">
        <span class="sr-only" aria-live="polite" aria-atomic="true">{{ feedbackMessage }}</span>

        <div class="room-input-wrap" :class="feedbackWrapClass">
          <div class="room-input-field">
            <input ref="input" v-model="text" type="text" class="room-input" :placeholder="__('Any idea?')" :aria-label="__('Any idea?')" tabindex="0" @paste.prevent="pastedAnswer" @drop.prevent="pastedAnswer" @focus="onInputInteract" @click="onInputInteract" @keydown="onInputKeydown" @compositionstart="isComposing = true" @compositionend="isComposing = false" @keydown.enter.prevent="check" autocomplete="off" maxlength="255" :disabled="inputDisabled" />
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
                  <div class="space-y-3 px-4 py-2">
                    <div class="flex items-center justify-between">
                      <label class="text-sm text-white/70">{{ __('Auto-focus') }}</label>
                      <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200" :class="autoFocus ? 'bg-brand-accent-dark' : 'bg-brand-midnight'" :aria-pressed="autoFocus" @click="toggleAutoFocus">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="autoFocus ? 'translate-x-6' : 'translate-x-1'" />
                      </button>
                    </div>

                    <div class="flex items-center justify-between">
                      <label class="text-sm text-white/70">{{ __('Answer sounds') }}</label>
                      <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200" :class="answerSounds ? 'bg-brand-accent-dark' : 'bg-brand-midnight'" :aria-pressed="answerSounds" @click="toggleAnswerSounds">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="answerSounds ? 'translate-x-6' : 'translate-x-1'" />
                      </button>
                    </div>

                    <Volume class="w-full" />
                  </div>
                </div>
              </template>
            </Dropdown>

            <button type="submit" class="room-input-submit" :class="{ 'room-input-submit--busy': pendingCount > 0 }" :disabled="inputDisabled || !text.trim()" :aria-label="__('Send')" :aria-busy="pendingCount > 0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
            </button>
          </div>
        </div>

        <transition name="fade">
          <p v-if="hintMessage" class="room-input-hint">
            <Icon name="hint" class="mt-0.5 h-4 w-4 shrink-0" />
            <span>{{ hintMessage }}</span>
          </p>
        </transition>
      </div>
    </form>

    <div class="room-answer-chips-row relative min-w-0">
      <transition-group name="fade-slide" tag="ul" v-if="track" class="room-answer-chips flex flex-wrap gap-2 text-sm sm:gap-4">
        <li v-for="answer in track.answers" :key="answer.id" class="room-answer-chip" :class="{ 'room-answer-chip--found': isAnswerFound(answer.id), 'room-answer-chip--flash': isAnswerFlashing(answer.id) }">
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
