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

onMounted(() => {
  Echo.private(channel)
    .listen('NewMessage', (e) => {
      console.log(e.message)
      messages.value.unshift(...[e.message])
      scrollToBottom()
    })
})

onUnmounted(() => {
  Echo.leave(channel)
})

const sendMessage = () => {
  axios.post(`/rooms/${props.room.id}/message`, {
    body: body.value,
  }).then(response => {
    body.value = ''
  })
}

const scrollToBottom = () => {
  let container = messenger.value
  container.scrollTop = container.scrollHeight + 1000;
}
</script>
<template>

<div class="flex flex-col h-full">
  <div ref="messenger" class="flex-1 overflow-y-scroll flex flex-col-reverse p-2">
    <Message v-for="message in messages" :key="message.id" :message="message"/>
  </div>
  <div class="flex p-2">
    <form @submit.prevent="sendMessage" class="flex">
      <TextInput v-model="body" class="rounded-r-none" />
      <button type="submit" class="rounded-r bg-teal-600 p-2 text-neutral-100">Send</button>
    </form>
  </div>
</div>

</template>
