<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Moderation from './Moderation.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true
  },
  message: {
    type: Object,
    required: true
  },
})

const moderate = ref(false)
const reporting = ref(false)
const { auth, publicModerators } = usePage().props
const user = auth.user

const isModerator = computed(() => props.room.moderators.some(x => x.id === user.id))
const userIsPublicModerator = computed(() => publicModerators.some(x => x.id === user.id))
const isMessageFromPublicModerator = computed(() => publicModerators.some(x => x.id === props.message.user.id))
const shouldShowReportButton = computed(() => !isMessageFromPublicModerator.value)

const report = async () => {
  if (reporting.value) return
  reporting.value = true
  try {
    await axios.post(`/rooms/${props.room.id}/message/${props.message.id}/report`)
  } catch (error) {
    console.error('Failed to report message:', error)
  } finally {
    reporting.value = false
  }
}
</script>

<template>
  <div class="group hover:opacity-80 text-[13px] w-full">
    <div class="flex items-start gap-2">
      <img :src="message.user.photo" :alt="message.user.name" class="h-8 w-8 rounded-full" loading="lazy" />
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <button 
              v-if="isModerator || userIsPublicModerator" 
              @click="moderate = true" 
              class="font-bold whitespace-nowrap"
              :class="{
                'text-purple-500': room.moderators.some(x => x.id === message.user.id),
                'text-neutral-400': !room.moderators.some(x => x.id === message.user.id)
              }"
            >
              {{ message.user.name }}
            </button>
            <span 
              v-else 
              class="font-bold whitespace-nowrap"
              :class="{
                'text-purple-500': room.moderators.some(x => x.id === message.user.id),
                'text-neutral-400': !room.moderators.some(x => x.id === message.user.id)
              }"
            >
              {{ message.user.name }}
            </span>
            <span v-if="message.user.team_id" class="text-neutral-500 ml-1 text-xs">
              | {{ message.user.team.name }}
            </span>
          </div>

          <div class="flex items-center gap-2">
            <div v-if="shouldShowReportButton" class="group-hover:flex items-center" :class="{ 'hidden': !message.reports }">
              <div v-if="reporting" class="animate-spin">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
              </div>
              <button v-else @click="report" class="flex items-center text-xs" :disabled="reporting">
                <span class="mr-1 text-yellow-600">{{ message.reports }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500">
                  <title>{{ __('Report this message') }}</title>
                  <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
            <time :datetime="message.timestamp" class="text-neutral-500 text-xs mr-2">{{ message.time }}</time>
          </div>
        </div>
        <p class="whitespace-pre-wrap break-words max-w-72 lg:max-w-52 xl:max-w-64">{{ message.body }}</p>
      </div>
    </div>
  </div>
  <Moderation v-if="moderate" :message="message" :room="room" @close="moderate = false" />
</template>
