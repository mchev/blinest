<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Moderation from './Moderation.vue'

const props = defineProps({
  room: Object,
  message: Object,
})

const moderate = ref(false)
const reporting = ref(false)
const user = usePage().props.value.auth.user
const isModerator = props.room.moderators.find((x) => x.id === user.id)

const report = () => {
  reporting.value = true
  axios.post(`/rooms/${props.room.id}/message/${props.message.id}/report`).then((response) => {
    reporting.value = false
  })

}

</script>
<template>
  <div class="group relative my-1 flex flex-wrap items-center text-sm hover:opacity-90">
    <div class="absolute top-0 right-0 items-center group-hover:flex bg-neutral-800 py-1 px-2" :class="message.reports < 0 ? 'flex' : 'hidden'">
      <div v-if="reporting">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 animate-spin">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
      </div>
      <button v-else @click="report" class="text-xs flex items-center">
        <span class="text-yellow-600 mr-1">{{ message.reports }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500">
          <title>{{ __('Report this message') }}</title>
          <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
    <time class="mr-2 text-xs italic text-neutral-500">{{ message.time }}</time>
    <button v-if="isModerator" @click="moderate = true" class="mr-1 flex items-center" :class="(room.moderators.find((x) => x.id === message.user.id)) ? 'text-purple-500' : 'text-neutral-400'">
      <img :src="message.user.photo" :alt="message.user.name" class="mr-1 h-6 w-6 rounded-full" />
      {{ message.user.name }} <sup v-if="user.team" class="mx-1 text-[9px] uppercase">[{{ user.team.name }}]</sup> :
    </button>
    <span v-else class="mr-1 flex items-center" :class="(room.moderators.find((x) => x.id === message.user.id)) ? 'text-purple-500' : 'text-neutral-400'">
      <img :src="message.user.photo" :alt="message.user.name" class="mr-1 h-6 w-6 rounded-full" />
      {{ message.user.name }} <sup v-if="user.team" class="mx-1 text-[9px] uppercase">[{{ user.team.name }}]</sup> :
    </span>
    <span class="">{{ message.body }}</span>
    <Moderation v-if="moderate" :message="message" :room="room" @close="moderate = false" />
  </div>
</template>
