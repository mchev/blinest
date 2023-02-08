<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Message from './Message.vue'
import AlertModeratorsModal from './AlertModeratorsModal.vue'
import TextInput from '@/Components/TextInput.vue'
import Share from '@/Components/Share.vue'

const props = defineProps({
  room: Object,
})

const channel = `chat-room.${props.room.id}`
const body = ref('')
const messages = ref(props.room.latest_messages)
const user = usePage().props.auth.user
const messenger = ref()
const reported = ref(null)
const alertingModerators = ref(null)

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
</script>
<template>
  <div class="flex h-full flex-col">
    <div class="flex items-center justify-center gap-2 px-2 py-4">
      <button class="btn-secondary btn-sm bg-neutral-700" @click="alertingModerators = true" v-if="!reported" :title="__('Report a problem on the chat')">
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
      <a :href="route('faq')" title="FAQ" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
        </svg>
      </a>
      <Share :url="room.url" class="h-5 w-5" />
    </div>
    <div ref="messenger" class="flex flex-1 flex-col-reverse overflow-y-scroll p-2">
      <Message v-for="message in messages" :key="message.id" :message="message" :room="room" />
    </div>
    <div class="flex w-full p-2">
      <form @submit.prevent="sendMessage" class="flex w-full">
        <TextInput v-model="body" autocomplete="off" inputClass="rounded-r-none border-0" class="flex-grow" />
        <button type="submit" class="rounded-r bg-teal-600 p-2 text-neutral-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <title>{{ __('Send') }}</title>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
          </svg>
        </button>
      </form>
    </div>
  </div>
  <AlertModeratorsModal :room="room" :show="alertingModerators" @reported="reported = true" @close="alertingModerators = false" />
</template>
