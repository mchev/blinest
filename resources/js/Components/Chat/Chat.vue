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
  Echo.private(channel)
    .listen('NewMessage', (e) => {
      messages.value.unshift(...[e.message])
      scrollToBottom()
    })
    .listen('MessageReported', (e) => {
      messages.value = messages.value.map((x) => {
        if (x.id === e.message.id) return e.message
        return x
      })
    })
    .listen('MessageDeleted', (e) => {
      messages.value = messages.value.filter((x) => {
        return x.id != e.message.id
      })
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
      <button class="ml-2">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
          <title>{{ __('Help') }}</title>
          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
    <div ref="messenger" class="flex flex-1 flex-col-reverse overflow-y-scroll p-2">
      <Message v-for="message in messages" :key="message.id" :message="message" :room="room" @moderate="showModerationModal" />
    </div>
    <div class="flex p-2">
      <form @submit.prevent="sendMessage" class="flex">
        <TextInput v-model="body" autocomplete="off" inputClass="rounded-r-none border-0" />
        <button type="submit" class="rounded-r bg-teal-600 p-2 text-neutral-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <title>{{ __('Send') }}</title>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
          </svg>
        </button>
      </form>
    </div>
  </div>
</template>
