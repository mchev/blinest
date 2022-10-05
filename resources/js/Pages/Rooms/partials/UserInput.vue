<script>
// use normal <script> to declare options
export default {
  inheritAttrs: false
}
</script>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import Dropdown from '@/Components/Dropdown.vue'
import Controls from './Controls.vue'

const props = defineProps({
  room: Object,
  channel: String,
  currentTime: Number,
})

const input = ref(null)
const track = ref(null)
const round = ref(null)
const text = ref('')
const message = ref(null)
const answers = ref([])
const user = usePage().props.value.auth.user

onMounted(() => {
  focus()

  Echo.channel(props.channel).listen('TrackPlayed', (e) => {
    props.room.value = e.room
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
    axios.post(`/rounds/${round.value.id}/tracks/${track.value.id}/check`, { text: text.value, currentTime: props.currentTime }).then((response) => {
      answers.value.push(...response.data.good_answers)
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
  }, 1500)
}
</script>
<template>
  <form class="flex w-full items-center justify-center" @submit.prevent="check">
    <div class="relative flex w-full items-center">
      <blockquote v-if="message" class="absolute bottom-full right-0 flex translate-y-[-80%] translate-x-[-50%] items-center rounded-lg bg-teal-600 py-1 px-2 text-neutral-100">
        {{ message.body }}
        <div class="absolute left-5 top-full h-full h-0 w-full w-0 translate-y-[-50%] border-t-[13px] border-l-[13px] border-r-[13px] border-t-transparent border-l-transparent border-r-transparent border-t-teal-600"></div>
      </blockquote>

      <input ref="input" v-model="text" type="text" class="h-14 w-full flex-grow rounded-bl-md border-none p-2 text-2xl uppercase text-gray-600 focus:shadow-none focus:outline-none focus:ring-0" placeholder="Une idée?" autofocus />

      <dropdown v-if="round && props.room.moderators.find(x => user.id === x.id)" :autoClose="false" placement="bottom-end">
        <template #default>
          <button type="button" class="h-14 bg-white p-4 text-neutral-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
          </button>
        </template>
        <template #dropdown>
          <Controls :channel="channel" :room="props.room" :round="round" />
        </template>
      </dropdown>

      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="w-6 h-6">
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
