<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
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
const isFromModerator = computed(() => props.room.moderators.some(x => x.id === props.message.user.id))

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

// --- Réactions ---
const reactions = ref([])
const userReaction = ref(null)
const showEmojiPicker = ref(false)
const emojiList = [
  '👍', '😂', '❤️', '🔥', '😮', '😢', '👏', '😡', '🎉', '😎', '🤔', '🙌', '💯', '🎵', '🥁', '🎸', '🎤', '🎻', '🎺', '🎷', '🥁', '🎶', '😊', '😃', '😞', '😉', '😛', '😲', '😘', '😕', '🤑'
]

const fetchReactions = async () => {
  const { data } = await axios.get(`/api/messages/${props.message.id}/reactions`)
  reactions.value = data.reactions || data // supporte [{emoji, count}] ou {reactions, userReaction}
  userReaction.value = data.userReaction || null
}

const toggleReaction = async (emoji) => {
  // Si l'utilisateur a déjà réagi avec cet emoji, on retire
  if (userReaction.value === emoji) {
    await axios.post(`/api/messages/${props.message.id}/reactions`, { emoji })
    // toggle off
  } else {
    // Si une autre réaction existe, on la retire d'abord
    if (userReaction.value) {
      await axios.post(`/api/messages/${props.message.id}/reactions`, { emoji: userReaction.value })
    }
    await axios.post(`/api/messages/${props.message.id}/reactions`, { emoji })
  }
  // La maj se fait via Echo
}

const listenForReactions = () => {
  if (!window.Echo) return
  window.Echo.private(`chat.message.${props.message.id}`)
    .listen('MessageReactionUpdated', (e) => {
      reactions.value = e.reactions
      userReaction.value = e.userReaction || null
    })
}

// --- Click outside directive ---
function useClickOutside(targetRef, callback) {
  const handler = (event) => {
    if (targetRef.value && !targetRef.value.contains(event.target)) {
      callback()
    }
  }
  onMounted(() => {
    document.addEventListener('mousedown', handler)
    document.addEventListener('touchstart', handler)
  })
  onUnmounted(() => {
    document.removeEventListener('mousedown', handler)
    document.removeEventListener('touchstart', handler)
  })
}

const emojiPickerRef = ref(null)
useClickOutside(emojiPickerRef, () => { showEmojiPicker.value = false })

onMounted(() => {
  fetchReactions()
  listenForReactions()
})
onUnmounted(() => {
  if (window.Echo) {
    window.Echo.private(`chat.message.${props.message.id}`).stopListening('MessageReactionUpdated')
  }
})
</script>

<template>
  <div class="group relative p-2 rounded-lg transition-all duration-200 hover:bg-neutral-800/60 mb-1">
    <div class="flex items-start gap-3">
      <div class="relative">
        <img 
          :src="message.user.photo" 
          :alt="message.user.name" 
          class="h-10 w-10 rounded-full object-cover border-2" 
          :class="isFromModerator ? 'border-purple-500' : 'border-neutral-700'"
          loading="lazy" 
        />
        <div 
          v-if="isFromModerator" 
          class="absolute -bottom-1 -right-1 bg-purple-500 rounded-full p-0.5"
          title="Room Moderator"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      
      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-1 relative">
          <div class="flex items-center gap-2 overflow-hidden">
            <button 
              v-if="isModerator || userIsPublicModerator" 
              @click="moderate = true" 
              class="font-bold text-sm hover:underline truncate"
              :class="{
                'text-purple-400': isFromModerator,
                'text-neutral-200': !isFromModerator
              }"
            >
              {{ message.user.name }}
            </button>
            <span 
              v-else 
              class="font-bold text-sm truncate"
              :class="{
                'text-purple-400': isFromModerator,
                'text-neutral-200': !isFromModerator
              }"
            >
              {{ message.user.name }}
            </span>
            <span 
              v-if="message.user.team_id" 
              class="px-1.5 py-0.5 bg-neutral-700/50 text-xs rounded-md text-neutral-300 truncate"
            >
              {{ message.user.team.name }}
            </span>
          </div>
          <div class="flex items-center gap-1">
            <time 
              :datetime="message.timestamp" 
              class="text-neutral-500 text-xs whitespace-nowrap ml-2"
            >
              {{ message.time }}
            </time>
            <!-- Bouton emoji à droite, n'apparaît qu'au survol du message -->
            <button
              v-if="!userReaction"
              class="hidden group-hover:flex absolute top-full right-0 items-center justify-center ml-2 px-2 py-1 rounded-full bg-neutral-800/70 hover:bg-neutral-700 text-xl transition shadow-lg border border-neutral-700"
              @click="showEmojiPicker = !showEmojiPicker"
              title="Ajouter une réaction"
              style="width: 2.5rem; height: 2.5rem;"
            >
              😊
            </button>
            <div
              v-if="showEmojiPicker"
              ref="emojiPickerRef"
              class="absolute z-10 right-0 mt-10 bg-neutral-900 border border-neutral-700 rounded-lg p-2 flex flex-wrap gap-1 shadow-lg"
            >
              <button
                v-for="emoji in emojiList"
                :key="emoji"
                class="text-xl hover:scale-125 transition-transform"
                @click="toggleReaction(emoji); showEmojiPicker = false"
              >
                {{ emoji }}
              </button>
            </div>
          </div>
        </div>
        
        <p class="text-neutral-100 whitespace-pre-wrap break-words text-sm leading-relaxed">
          {{ message.body }}
        </p>
        <!-- Réactions -->
        <div class="flex gap-1 mt-2 flex-wrap items-center relative">
          <button
            v-for="reaction in reactions"
            :key="reaction.emoji"
            class="flex items-center gap-1 px-2 py-1 rounded-full bg-neutral-700/70 hover:bg-neutral-600 text-sm transition"
            :class="{'ring-2 ring-yellow-400': userReaction === reaction.emoji}"
            @click="toggleReaction(reaction.emoji)"
          >
            <span>{{ reaction.emoji }}</span>
            <span class="font-medium text-xs">{{ reaction.count }}</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Report button -->
    <div 
      v-if="shouldShowReportButton" 
      class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
    >
      <div v-if="reporting" class="animate-spin bg-neutral-800 rounded-full p-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-neutral-300">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
      </div>
      <button 
        v-else 
        @click="report" 
        class="flex items-center gap-1 bg-neutral-800 hover:bg-neutral-700 rounded-full px-2 py-1 transition-colors duration-200" 
        :disabled="reporting"
        :class="{ 'bg-red-900/50': message.reports }"
      >
        <span v-if="message.reports" class="text-yellow-500 font-medium text-xs">{{ message.reports }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-red-500">
          <title>{{ __('Report this message') }}</title>
          <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  </div>
  
  <Moderation v-if="moderate" :message="message" :room="room" @close="moderate = false" />
</template>
