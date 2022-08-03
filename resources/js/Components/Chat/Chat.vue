<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Message from './Message.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  room: Object,
})

const channel = `chat-room.${props.room.id}`
const body = ref('')
const messages = ref(props.room.latest_messages)
const user = usePage().props.value.auth.user
const messenger = ref()
const reported = ref(null)

onMounted(() => {
  Echo.private(channel).listen('NewMessage', (e) => {
    console.log(e.message)
    messages.value.unshift(...[e.message])
    scrollToBottom()
  })
})

onUnmounted(() => {
  Echo.leave(channel)
})

const alertModerators = () => {
  axios.post(`/rooms/${props.room.id}/alert`).then((response) => {
    reported.value = true
  })
}

const sendMessage = () => {
  axios
    .post(`/rooms/${props.room.id}/message`, {
      body: body.value,
    })
    .then((response) => {
      body.value = ''
    })
}

const scrollToBottom = () => {
  let container = messenger.value
  container.scrollTop = container.scrollHeight + 1000
}

const showModerationModal = (e) => {
  console.log(e)
}
</script>
<template>
  <div class="flex h-full flex-col">
    <div class="flex justify-center px-2 py-4">
      <button class="btn-secondary btn-sm bg-neutral-700" @click="alertModerators" v-if="!reported">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        {{ __('Alerting moderators') }}
      </button>
      <div v-else class="flex items-center text-green-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Alert sent') }}
      </div>
    </div>
    <div ref="messenger" class="flex flex-1 flex-col-reverse overflow-y-scroll p-2">
      <Message v-for="message in messages" :key="message.id" :message="message" :room="room" @moderate="showModerationModal" />
    </div>
    <div class="flex p-2">
      <form @submit.prevent="sendMessage" class="flex">
        <TextInput v-model="body" class="rounded-r-none" />
        <button type="submit" class="rounded-r bg-teal-600 p-2 text-neutral-100">Send</button>
      </form>
    </div>
  </div>
</template>
