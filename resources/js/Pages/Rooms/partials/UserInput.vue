<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Volume from '@/Components/Volume.vue'

const props = defineProps({
  room: Object,
  channel: String,
  currentTime: Number,
})

const input = ref(null)
const track = ref(null)
const round = ref(null)
const text = ref('')
const words = ref([])
const message = ref(null)
const answers = ref([])
const { auth } = usePage().props
const user = auth.user
const inputDisabled = ref(true)

const focus = () => {
  input.value?.focus()
}

const showMessage = (data) => {
  message.value = data
  setTimeout(() => {
    message.value = null
  }, 1600)
}

const check = async () => {
  if (inputDisabled.value || text.value.length < 1 || !track.value) return

  try {
    const response = await axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, {
      text: text.value,
      words: words.value,
      currentTime: props.currentTime
    })

    answers.value.push(...response.data.good_answers)
    words.value = response.data.words
    showMessage(response.data.message)
    focus()
  } catch (error) {
    console.error('Error checking answer:', error)
  }

  text.value = ''
}

const pastedAnswer = (event) => {
  event.preventDefault()
  text.value = "Je copie colle et c'est mal. Je copie colle et c'est mal."
}

onMounted(() => {
  focus()

  Echo.channel(props.channel)
    .listen('TrackPlayed', (e) => {
      props.room.value = e.room
      round.value = e.round
      track.value = e.track
      answers.value = []
      inputDisabled.value = false
      text.value = ''
    })
    .listen('TrackEnded', () => {
      inputDisabled.value = true
      text.value = ''
      words.value = []
    })
    .listen('UserHasFoundAllTheAnswers', (e) => {
      if (e.user === user) {
        inputDisabled.value = true
      }
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})

const messageClass = computed(() => ({
  'bg-teal-600': message.value?.type === 'good',
  'bg-orange-600': message.value?.type === 'almost',
  'bg-red-700': message.value?.type === 'bad'
}))

const isAnswerFound = (answerId) => answers.value.some(a => a.id === answerId)

const getFoundAnswer = (answerId) => answers.value.find(a => a.id === answerId)
</script>

<template>
  <form class="flex w-full items-center justify-center" @submit.prevent="check">
    <div class="relative flex w-full items-center">
      <blockquote v-if="message" class="absolute top-full right-0 mt-2 flex rounded-lg py-1 px-2 text-neutral-100 opacity-80" :class="messageClass">
        {{ message.body }}
      </blockquote>

      <input
        ref="input"
        v-model="text"
        type="text"
        class="h-14 w-full flex-grow rounded-none rounded-bl-md border-none bg-neutral-700 p-2 text-2xl uppercase focus:shadow-none focus:outline-none focus:ring-0"
        :placeholder="__('Any idea?')"
        autofocus
        @paste.prevent="pastedAnswer"
        @drop.prevent="pastedAnswer"
        autocomplete="off"
        maxlength="255"
      />

      <Volume class="-ml-1 flex h-14 items-center justify-center bg-neutral-700 p-2" />

      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="h-6 w-6">
          <title>{{ __('Send') }}</title>
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
      </button>
    </div>
  </form>
  <ul v-if="track" class="mt-2 flex flex-wrap gap-2 text-sm">
    <li
      v-for="answer in track.answers"
      :key="answer.id"
      class="flex items-center rounded py-1 px-2 text-neutral-100"
      :class="{ 'bg-neutral-700': !isAnswerFound(answer.id), 'bg-teal-600': isAnswerFound(answer.id) }"
    >
      <template v-if="isAnswerFound(answer.id)">
        <span v-if="getFoundAnswer(answer.id).type.svg_icon" class="mr-1" v-html="getFoundAnswer(answer.id).type.svg_icon"></span>
        {{ getFoundAnswer(answer.id).value }}
      </template>
      <template v-else>
        {{ __(answer.name) }} ?
      </template>
    </li>
  </ul>
</template>
