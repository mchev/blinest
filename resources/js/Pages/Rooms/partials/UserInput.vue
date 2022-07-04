<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Controls from './Controls.vue'

const props = defineProps({
  channel: String,
})

const input = ref(null)
const track = ref(null)
const room = ref(null)
const round = ref(null)
const text = ref('')
const answers = ref([])

onMounted(() => {
  focus()

  Echo.channel(props.channel).listen('TrackPlayed', (e) => {
    room.value = e.room
    round.value = e.round
    track.value = e.track
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
  if (text.value.length > 1 && track.value) {
    axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, { text: text.value }).then((response) => {
      answers.value.push(...response.data.good_answers)
      focus()
    })
  }
  text.value = ''
}
</script>
<template>
  <form class="flex w-full items-center justify-center" @submit.prevent="check">
    <div class="flex w-full items-center">
      <input ref="input" v-model="text" type="text" class="h-14 flex-grow rounded-bl-md border-none p-2 text-2xl uppercase text-gray-600 focus:shadow-none focus:outline-none focus:ring-0 w-full" placeholder="Une idée?" autofocus />

      <dropdown v-if="round" :autoClose="false" placement="bottom-end">
        <template #default>
          <button type="button" class="h-14 bg-white p-4 text-neutral-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
          </button>
        </template>
        <template #dropdown>
          <Controls :channel="channel" :room="room" :round="round" />
        </template>
      </dropdown>

      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <title>{{ __('Send') }}</title>
          <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" />
        </svg>
      </button>
    </div>
  </form>
  <ul class="mt-2 flex gap-2">
    <li v-for="answer in answers" :key="answer.id" class="flex items-center rounded-lg bg-teal-500 py-1 px-2 text-neutral-100"><span v-if="answer.type.svg_icon" class="mr-1" v-html="answer.type.svg_icon" /> {{ answer.value }}</li>
  </ul>
</template>
