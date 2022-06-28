<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  data: Object,
  channel: String,
})

const input = ref(null)
const track = ref(props?.data?.track)
const text = ref('')
const answers = ref([])

watch(
  () => props.data,
  (value) => {
    track.value = value.track
    focus()
  },
)

onMounted(() => {
  focus()

  Echo.channel(props.channel).listen('TrackPlayed', () => {
    answers.value = []
  })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})

const focus = () => {
  input.value.focus()
}

const check = () => {
  if (text.value.length > 1 && props.data) {
    axios.post(`/rounds/${props.data.round_id}/tracks/${track.value.id}/check`, { text: text.value }).then((response) => {
      answers.value.push(...response.data.good_answers)
      focus()
    })
  }
  text.value = ''
}
</script>
<template>
  <form @submit.prevent="check" class="flex w-full items-center justify-center">
    <div class="flex w-full items-center">
      <input ref="input" v-model="text" type="text" class="h-14 flex-grow rounded-bl-md border-none p-2 text-2xl uppercase text-gray-600 focus:shadow-none focus:outline-none focus:ring-0" placeholder="Une idée?" autofocus />
      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <title>{{ __('Send') }}</title>
          <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" />
        </svg>
      </button>
    </div>
  </form>
  <ul class="mt-2 flex gap-2">
    <li v-for="answer in answers" :key="answer.id" class="rounded-lg bg-teal-500 text-neutral-100 py-1 px-2 flex items-center">
      <span v-if="answer.type.svg_icon" v-html="answer.type.svg_icon" class="mr-1" /> {{ answer.value }}
    </li>
  </ul>
</template>
