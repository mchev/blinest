<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import UserAvatar from '@/Components/UserAvatar.vue'
import { userHasSupporterReactions } from '@/utils/donorPerks'
import Moderation from './Moderation.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
  },
  message: {
    type: Object,
    required: true,
  },
})

const moderate = ref(false)
const reporting = ref(false)
const reported = ref(false)
const { auth, publicModerators, chat_reactions: chatReactions } = usePage().props
const user = auth.user

const standardEmojis = computed(() => chatReactions?.standard ?? [])
const supporterEmojis = computed(() => chatReactions?.supporter ?? [])
const canUseSupporterReactions = computed(() => userHasSupporterReactions(user))

const isModerator = computed(() => props.room.moderators.some((x) => x.id === user.id))
const userIsPublicModerator = computed(() => publicModerators.some((x) => x.id === user.id))
const isMessageFromPublicModerator = computed(() => publicModerators.some((x) => x.id === props.message.user.id))
const shouldShowReportButton = computed(() => !isMessageFromPublicModerator.value)
const isFromModerator = computed(() => props.room.moderators.some((x) => x.id === props.message.user.id))
const reportsCount = computed(() => Math.abs(props.message.reports || 0))

const report = async () => {
  if (reporting.value || reported.value) return
  reporting.value = true
  try {
    await axios.post(`/rooms/${props.room.id}/message/${props.message.id}/report`)
    reported.value = true
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

const selectEmoji = (emoji) => {
  toggleReaction(emoji)
  showEmojiPicker.value = false
}

const listenForReactions = () => {
  if (!window.Echo) return
  window.Echo.private(`chat.message.${props.message.id}`).listen('MessageReactionUpdated', (e) => {
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
const messageRef = ref(null)
const emojiPickerPosition = ref({ top: 'auto', bottom: 'auto' })

useClickOutside(emojiPickerRef, () => {
  showEmojiPicker.value = false
})

// Calculate emoji picker position based on messages container, not viewport
const calculateEmojiPickerPosition = () => {
  if (!messageRef.value || !emojiPickerRef.value) return

  // Find the messages container (parent scrollable container)
  const messagesContainer = messageRef.value.closest('.overflow-y-auto') || messageRef.value.closest('[class*="overflow"]') || null

  if (!messagesContainer) {
    // Fallback to viewport if container not found
    const messageRect = messageRef.value.getBoundingClientRect()
    const viewportHeight = window.innerHeight
    const pickerHeight = emojiPickerRef.value.offsetHeight || 200
    const spaceBelow = viewportHeight - messageRect.bottom
    const spaceAbove = messageRect.top
    const margin = 20

    if (spaceBelow < pickerHeight + margin && spaceAbove > pickerHeight + margin) {
      emojiPickerPosition.value = { bottom: '100%', top: 'auto', marginBottom: '8px' }
    } else {
      emojiPickerPosition.value = { top: '100%', bottom: 'auto', marginTop: '8px' }
    }
    return
  }

  // Calculate position relative to messages container
  const messageRect = messageRef.value.getBoundingClientRect()
  const containerRect = messagesContainer.getBoundingClientRect()
  const pickerHeight = emojiPickerRef.value.offsetHeight || 200

  // Calculate space relative to container
  // Space below = distance from message bottom to container bottom
  const spaceBelow = containerRect.bottom - messageRect.bottom
  // Space above = distance from container top to message top
  const spaceAbove = messageRect.top - containerRect.top

  // Add some margin (20px) for better UX
  const margin = 20

  // If not enough space below but enough space above, show above
  if (spaceBelow < pickerHeight + margin && spaceAbove > pickerHeight + margin) {
    emojiPickerPosition.value = { bottom: '100%', top: 'auto', marginBottom: '8px' }
  } else {
    emojiPickerPosition.value = { top: '100%', bottom: 'auto', marginTop: '8px' }
  }
}

// Watch for emoji picker visibility changes
const toggleEmojiPicker = () => {
  showEmojiPicker.value = !showEmojiPicker.value
  if (showEmojiPicker.value) {
    // Calculate position after Vue updates the DOM
    nextTick(() => {
      calculateEmojiPickerPosition()
    })
  }
}

// Handle scroll and resize to recalculate position
const handleRecalculate = () => {
  if (showEmojiPicker.value) {
    calculateEmojiPickerPosition()
  }
}

// Watch for emoji picker visibility to add/remove listeners
watch(showEmojiPicker, (isVisible) => {
  if (isVisible) {
    nextTick(() => {
      calculateEmojiPickerPosition()
      window.addEventListener('scroll', handleRecalculate, { passive: true })
      window.addEventListener('resize', handleRecalculate, { passive: true })
    })
  } else {
    window.removeEventListener('scroll', handleRecalculate)
    window.removeEventListener('resize', handleRecalculate)
  }
})

onMounted(() => {
  fetchReactions()
  listenForReactions()
})
onUnmounted(() => {
  if (window.Echo) {
    window.Echo.private(`chat.message.${props.message.id}`).stopListening('MessageReactionUpdated')
  }
  // Cleanup scroll and resize listeners
  window.removeEventListener('scroll', handleRecalculate)
  window.removeEventListener('resize', handleRecalculate)
})
</script>

<template>
  <div ref="messageRef" class="chat-message group">
    <div class="absolute right-2 top-2 z-20 hidden flex-row gap-1 group-hover:flex">
      <button class="chat-icon-btn text-base" @click="toggleEmojiPicker" title="Réagir" type="button">😊</button>
      <button v-if="shouldShowReportButton" class="chat-icon-btn disabled:opacity-50" :disabled="reporting" @click="report" :title="reported ? __('Reported') : __('Report this message')" type="button">
        <svg v-if="!reported" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-brand-primary">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-brand-accent">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </button>
    </div>
    <div class="flex items-start gap-3">
      <div class="relative">
        <UserAvatar :user="message.user" :img-class="['h-10 w-10 rounded-full border-2 object-cover', isFromModerator ? 'border-brand-accent' : 'border-white/20'].join(' ')" crown-size="sm" />
        <div v-if="isFromModerator" class="absolute -bottom-1 -right-1 bg-brand-accent p-0.5" title="Room Moderator">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>

      <div class="min-w-0 flex-1">
        <div class="relative mb-1 flex items-center justify-between">
          <div class="flex items-center gap-2 overflow-hidden">
            <button
              v-if="isModerator || userIsPublicModerator"
              @click="moderate = true"
              class="truncate text-sm font-bold hover:underline"
              :class="{
                'text-brand-accent': isFromModerator,
                'text-white': !isFromModerator,
              }"
            >
              {{ message.user.name }}
            </button>
            <span
              v-else
              class="truncate text-sm font-bold"
              :class="{
                'text-brand-accent': isFromModerator,
                'text-white': !isFromModerator,
              }"
            >
              {{ message.user.name }}
            </span>
            <span v-if="message.user.team_id" class="truncate border border-white/10 bg-brand-midnight px-1.5 py-0.5 text-xs text-white/60">
              {{ message.user.team.name }}
            </span>
          </div>
          <div class="flex items-center gap-1">
            <span v-if="reportsCount >= 1" class="ml-2 whitespace-nowrap text-xs text-brand-secondary" :title="__('Reports')"> {{ reportsCount }} {{ reportsCount === 1 ? __('Report') : __('Reports') }} </span>
            <time :datetime="message.timestamp" class="ml-2 whitespace-nowrap text-xs text-white/40">
              {{ message.time }}
            </time>
          </div>
        </div>

        <p class="whitespace-pre-wrap break-words text-sm leading-relaxed text-white/90">
          {{ message.body }}
        </p>
        <!-- Réactions -->
        <div class="relative mt-2 flex flex-wrap items-center gap-1">
          <div v-for="reaction in reactions" :key="reaction.emoji" class="group/emoji relative">
            <button class="chat-reaction" :class="{ 'chat-reaction--mine': userReaction === reaction.emoji, 'chat-reaction--supporter': supporterEmojis.includes(reaction.emoji) }" @click="toggleReaction(reaction.emoji)" type="button">
              <span>{{ reaction.emoji }}</span>
              <span class="text-xs font-medium">{{ reaction.count }}</span>
            </button>
            <span v-if="reaction.users && reaction.users.length" class="pointer-events-none absolute bottom-full left-1/2 z-20 mb-2 -translate-x-1/2 whitespace-nowrap border border-white/10 bg-brand-deep px-2 py-1 text-xs text-white/80 opacity-0 shadow-lg transition-opacity group-hover/emoji:opacity-100">
              {{ reaction.users.map((u) => u.name).join(', ') }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Sélecteur d'emojis flottant -->
    <div
      v-if="showEmojiPicker"
      ref="emojiPickerRef"
      class="retro-dropdown-panel absolute right-2 z-40 flex flex-wrap gap-1 border border-white/10 bg-brand-deep p-2 shadow-lg"
      :style="{
        top: emojiPickerPosition.top,
        bottom: emojiPickerPosition.bottom,
        marginTop: emojiPickerPosition.marginTop || '0',
        marginBottom: emojiPickerPosition.marginBottom || '0',
      }"
    >
      <button v-for="emoji in standardEmojis" :key="emoji" class="text-xl transition-transform hover:scale-125" @click="selectEmoji(emoji)">
        {{ emoji }}
      </button>
      <template v-if="supporterEmojis.length">
        <div class="my-1 w-full border-t border-white/10" />
        <p class="w-full px-1 text-[10px] font-semibold uppercase tracking-wide" :class="canUseSupporterReactions ? 'text-amber-300/90' : 'text-white/35'">
          {{ __('Supporter reactions') }}
        </p>
        <button v-for="emoji in supporterEmojis" :key="`supporter-${emoji}`" class="text-xl transition-transform" :class="canUseSupporterReactions ? 'hover:scale-125' : 'cursor-not-allowed opacity-40'" :disabled="!canUseSupporterReactions" :title="canUseSupporterReactions ? undefined : __('Supporter reaction emoji locked')" @click="canUseSupporterReactions && selectEmoji(emoji)">
          {{ emoji }}
        </button>
      </template>
    </div>
  </div>

  <Moderation v-if="moderate" :message="message" :room="room" @close="moderate = false" />
</template>
