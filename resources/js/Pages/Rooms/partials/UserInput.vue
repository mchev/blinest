<script>
// use normal <script> to declare options
export default {
  inheritAttrs: false,
}
</script>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
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
const user = usePage().props.auth.user
const inputDisabled = ref(true)

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
    .listen('TrackEnded', (e) => {
      inputDisabled.value = true
      text.value = ''
      words.value = []
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})

const focus = () => {
  input.value.focus()
}

const check = () => {
  if (text.value.length >= 1 && track.value) {
    axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, { text: text.value, words: words.value, currentTime: props.currentTime }).then((response) => {
      answers.value.push(...response.data.good_answers)
      words.value = response.data.words
      showMessage(response.data.message)
      focus()
    })
  }
  text.value = ''
}

const showMessage = (data) => {
  message.value = data
  setTimeout(() => {
    message.value = null
  }, 1600)
}

const pastedAnswer = (event) => {
  event.preventDefault()
  text.value = "Je copie colle et c'est mal. Je copie colle et c'est mal."
  return false
}
</script>
<template>
  <form class="flex w-full items-center justify-center" @submit.prevent="check">
    <div class="relative flex w-full items-center">
      <blockquote v-if="message" class="absolute top-full right-0 mt-2 flex rounded-lg py-1 px-2 text-neutral-100 opacity-80" :class="{ 'bg-teal-600': message.type === 'good', 'bg-orange-600': message.type === 'almost', 'bg-red-700': message.type === 'bad' }">
        {{ message.body }}
      </blockquote>

      <input ref="input" v-model="text" type="text" class="h-14 w-full flex-grow rounded-none rounded-bl-md border-none border-none bg-neutral-700 p-2 text-2xl uppercase focus:shadow-none focus:outline-none focus:ring-0" :placeholder="__('Any idea?')" autofocus :readonly="inputDisabled" @paste.prevent="pastedAnswer" @drop.prevent="pastedAnswer" autocomplete="off" maxlength="255" />

      <Volume class="-ml-1 flex h-14 items-center justify-center bg-neutral-700 p-2" />

      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="h-6 w-6">
          <title>{{ __('Send') }}</title>
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
      </button>
    </div>
  </form>
  <ul class="mt-2 flex gap-2">
    <li v-for="answer in answers" :key="answer.id" class="flex items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100"><span v-if="answer.type.svg_icon" class="mr-1" v-html="answer.type.svg_icon" /> {{ answer.value }}</li>
  </ul>
</template>
