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
  if (input.value) {
    input.value.focus()
    input.value.click()
    input.value.select()
  }
}

const showMessage = (data) => {
  message.value = data
  setTimeout(() => {
    message.value = null
  }, 1600)
}

const check = () => {
  if (inputDisabled.value || text.value.length < 1 || !track.value) return

  const currentText = text.value
  text.value = ''
  
  axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, {
    text: currentText,
    words: words.value,
    currentTime: props.currentTime
  })
  .then((response) => {
    answers.value.push(...response.data.good_answers)
    words.value = response.data.words
    showMessage(response.data.message)
    focus()
  })
  .catch(error => {
    console.error('Error checking answer:', error)
  })
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
      setTimeout(focus, 0)
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
  <form class="flex w-full items-center justify-center p-0 m-0" @submit.prevent="check">
    <div class="relative flex w-full flex-col">
      <div class="flex items-center shadow-lg rounded-lg overflow-hidden border border-neutral-600">
        <input
          ref="input"
          v-model="text"
          type="text"
          class="h-14 w-full flex-grow border-none bg-neutral-800/80 px-4 text-xl font-medium text-white placeholder-neutral-400 focus:shadow-none focus:outline-none focus:ring-0 transition-all duration-200 backdrop-blur-sm"
          :placeholder="__('Any idea?')"
          tabindex="0"
          @paste.prevent="pastedAnswer"
          @drop.prevent="pastedAnswer"
          autocomplete="off"
          maxlength="255"
          :disabled="inputDisabled"
        />

        <Volume class="flex h-14 items-center justify-center bg-neutral-800/80 p-2 border-l border-neutral-600" />

        <button 
          type="submit" 
          class="h-14 px-5 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white font-medium transition-all duration-200 flex items-center justify-center"
          :disabled="inputDisabled || !text.trim()"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-6 w-6">
            <title>{{ __('Send') }}</title>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </button>
      </div>
    </div>
  </form>

  <div class="relative">
    <transition-group 
      name="fade-slide" 
      tag="ul" 
      v-if="track" 
      class="flex flex-wrap gap-4 text-sm"
    >
      <li
        v-for="answer in track.answers"
        :key="answer.id"
        class="flex items-center rounded-lg py-1.5 px-3 text-neutral-100 shadow-md transition-all duration-300"
        :class="{
          'bg-gradient-to-r from-neutral-700 to-neutral-800 border border-neutral-600': !isAnswerFound(answer.id),
          'bg-gradient-to-r from-teal-600 to-teal-700 border border-teal-500 transform scale-105': isAnswerFound(answer.id)
        }"
      >
        <template v-if="isAnswerFound(answer.id)">
          <span v-if="getFoundAnswer(answer.id).type.svg_icon" class="mr-2 text-teal-200" v-html="getFoundAnswer(answer.id).type.svg_icon"></span>
          <span class="font-medium">{{ getFoundAnswer(answer.id).value }}</span>
        </template>
        <template v-else>
          <span class="font-medium opacity-80">{{ __(answer.name) }} ?</span>
        </template>
      </li>
    </transition-group>

    <transition name="fade">
      <blockquote 
        v-if="message" 
        class="mt-2 absolute top-0 right-0 rounded-lg py-2 px-4 text-neutral-100 shadow-lg backdrop-blur-sm"
        :class="messageClass"
      >
        {{ message.body }}
      </blockquote>
    </transition>
    </div>
</template>
