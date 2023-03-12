<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Bookmark from './Bookmark.vue'
import Controls from './Controls.vue'
import PodiumModal from './PodiumModal.vue'
import ShareModal from '@/Components/ShareModal.vue'

const props = defineProps({
  room: Object,
  channel: String,
  round: Object,
})

const emit = defineEmits(['displayChat'])

const user = usePage().props.auth.user
const showSidebar = ref(props.room.is_chat_active)
const showPodiumModal = ref(false)
const showShareModal = ref(false)

const toggleChat = () => {
  showSidebar.value = !showSidebar.value
  emit('displayChat', showSidebar.value)
}

</script>

<template>

  <button class="btn-secondary btn-sm gap-1" @click="showShareModal = true">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
    </svg>
    <span class="hidden md:block">{{ __('Share') }}</span>
  </button>

  <template v-if="user">
    <Controls v-if="room.moderators.find((x) => user.id === x.id)" :room="room" :round="round" :channel="channel" />
  </template>

  <button v-if="user" type="button" @click="showPodiumModal = true" :title="__('Show rankings for this room')" class="btn-secondary btn-sm gap-1">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
    </svg>
    <span class="hidden md:block">{{ __('Rankings') }}</span>
  </button>

  <Bookmark :room="room"/>

  <button v-if="user && room.is_chat_active" class="btn-secondary btn-sm gap-1" :class="{'text-green-300': showSidebar}" @click="toggleChat" :title="__('Hide/Show chatbox')">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <span class="hidden md:block">{{ __('Chat') }}</span>
  </button>

  <PodiumModal v-if="user && showPodiumModal" :room="room" :show="showPodiumModal" @close="showPodiumModal = false" />
  <ShareModal v-if="showShareModal" :url="room.url" :show="showShareModal" @close="showShareModal = false" />

</template>
