<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Message from './Message.vue'
import AlertModeratorsModal from './AlertModeratorsModal.vue'
import TextInput from '@/Components/TextInput.vue'
import { replaceEmojis } from './emojis.js'
import EmojiPicker from 'vue3-emoji-picker'
import 'vue3-emoji-picker/css'

const props = defineProps({
  room: {
    type: Object,
    required: true,
  },
  embedded: {
    type: Boolean,
    default: false,
  },
})

const channel = computed(() => `chat-room.${props.room.id}`)
const body = ref('')
const messages = ref(props.room.latest_messages)
const { auth } = usePage().props
const user = auth.user
const users = ref([])
const reported = ref(false)
const alertingModerators = ref(false)
const messagesContainer = ref(null)
const showMentions = ref(false)
const mentionFilter = ref('')
const cursorPosition = ref(0)
const showEmojiPicker = ref(false)
const inputRef = ref(null)

const filteredUsers = computed(() => {
  return users.value.filter(user =>
    user.name.toLowerCase().includes(mentionFilter.value.toLowerCase())
  )
})

onMounted(() => {
  Echo.join(channel.value)
    .here((newUsers) => {
      users.value = newUsers
    })
    .joining((user) => {
      users.value.push(user)
    })
    .leaving((user) => {
      users.value = users.value.filter(u => u.id !== user.id)
    })

  Echo.private(channel.value)
    .listen('NewMessage', ({ message }) => {
      messages.value.unshift(message)
      nextTick(() => {
        messagesContainer.value.scrollTop = 0
      })
    })
    .listen('MessageReported', ({ message }) => {
      const index = messages.value.findIndex(m => m.id === message.id)
      if (index !== -1) {
        messages.value[index] = message
      }
    })
    .listen('MessageDeleted', ({ message }) => {
      messages.value = messages.value.filter(m => m.id !== message.id)
    })
})

onUnmounted(() => {
  Echo.leave(channel.value)
})

const sendMessage = async () => {
  if (body.value.trim() === '') return

  const messageBody = replaceEmojis(body.value)
  try {
    await axios.post(`/rooms/${props.room.id}/message`, { body: messageBody })
    body.value = ''
  } catch (error) {
    console.error('Failed to send message:', error)
  }
}

const handleInput = (event) => {
  const currentPosition = event.target.selectionStart
  const textBeforeCursor = body.value.slice(0, currentPosition)
  const lastAtSymbol = textBeforeCursor.lastIndexOf('@')

  if (lastAtSymbol !== -1 && currentPosition - lastAtSymbol <= 20) {
    showMentions.value = true
    mentionFilter.value = textBeforeCursor.slice(lastAtSymbol + 1)
    cursorPosition.value = currentPosition
  } else {
    showMentions.value = false
  }
}

const selectUser = (userName) => {
  const beforeMention = body.value.slice(0, cursorPosition.value - mentionFilter.value.length - 1)
  const afterMention = body.value.slice(cursorPosition.value)
  body.value = `${beforeMention}@${userName} ${afterMention}`
  showMentions.value = false
  focusInput()
}

const onSelectEmoji = (emoji) => {
  body.value += emoji.i
  showEmojiPicker.value = false
  focusInput()
}

const focusInput = () => {
  nextTick(() => {
    inputRef.value?.$el.querySelector('input')?.focus()
  })
}
</script>

<template>
  <div class="chat-panel" :class="{ 'chat-panel--embedded': embedded }">
    <div v-if="! embedded" class="chat-header">
      <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-brand-accent">{{ __('Chat') }}</span>
      <button
        v-if="!reported"
        type="button"
        class="room-action-btn !py-1.5 !text-[10px]"
        @click="alertingModerators = true"
        :title="__('Report a problem on the chat')"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="hidden lg:inline">{{ __('Alert moderators') }}</span>
      </button>
      <div v-else class="flex items-center text-brand-accent text-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Alert sent') }}
      </div>
      <a :href="route('docs.faq')" title="FAQ" target="_blank" rel="noopener noreferrer" class="chat-icon-btn !h-7 !w-7">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
        </svg>
      </a>
    </div>

    <div v-else class="chat-header chat-header--embedded">
      <button
        v-if="!reported"
        type="button"
        class="chat-icon-btn !h-7 !w-7"
        @click="alertingModerators = true"
        :title="__('Report a problem on the chat')"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>
      <div v-else class="flex items-center text-brand-accent text-[10px]">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Alert sent') }}
      </div>
      <a :href="route('docs.faq')" title="FAQ" target="_blank" rel="noopener noreferrer" class="chat-icon-btn !h-7 !w-7">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
        </svg>
      </a>
    </div>

    <div
      ref="messagesContainer"
      class="chat-messages flex flex-1 overflow-y-auto gap-1 px-2 py-2"
      :class="embedded ? 'flex-col' : 'flex-col-reverse'"
    >
      <Message v-for="message in messages" :key="message.id" :message="message" :room="room" />
    </div>

    <div v-if="user?.is_guest" class="chat-composer flex w-full">
      <div class="chat-guest-banner text-center">
        <Link :href="route('guest.to-register')" class="font-medium text-brand-accent hover:text-brand-accent-hover underline">{{ __('Register') }}</Link>{{ __(' or ') }}<Link :href="route('guest.to-login')" class="font-medium text-brand-accent hover:text-brand-accent-hover underline">{{ __('Login') }}</Link>{{ __('to chat with other players') }}
      </div>
    </div>

    <div v-else class="chat-composer relative flex w-full">
      <form
        @submit.prevent="sendMessage"
        class="flex w-full text-sm relative"
        role="form"
        aria-label="Message form"
      >
        <div class="chat-input-wrap">
          <TextInput
            ref="inputRef"
            v-model="body"
            autocomplete="off"
            :inputClass="[
              'border-0',
              'focus:ring-0',
              'focus:ring-offset-0',
              'focus:border-transparent',
              'focus:outline-none',
              'bg-transparent',
              'text-base',
              embedded ? 'py-2' : 'py-3',
              'pl-4',
              'pr-12',
            ]"
            class="flex-grow"
            @click="showEmojiPicker = false"
            @input="handleInput"
            :placeholder="__('Type your message...')"
            aria-label="Message input"
            aria-describedby="message-char-count"
            maxlength="500"
          />
          <button
            type="button"
            class="absolute right-2 p-1.5 text-white/50 transition-colors duration-200 hover:text-brand-accent focus:outline-none"
            @click="showEmojiPicker = !showEmojiPicker"
            aria-label="Open emoji picker"
            aria-expanded="showEmojiPicker"
            aria-controls="emoji-picker"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
            </svg>
          </button>
        </div>
        <div id="message-char-count" class="sr-only" aria-live="polite">
          {{ body.length }} characters remaining out of 500
        </div>
        <button
          type="submit"
          class="chat-send-btn"
          :disabled="!body.trim()"
          aria-label="Send message"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/><path d="M6 12h16"/></svg>
        </button>
      </form>
      <EmojiPicker
        v-if="showEmojiPicker"
        :native="true"
        @select="onSelectEmoji"
        class="absolute right-2 z-50 shadow-xl border border-white/10 bg-brand-deep retro-dropdown-panel"
        :class="embedded ? 'top-full mt-1' : 'bottom-full'"
        id="emoji-picker"
        role="dialog"
        aria-label="Emoji picker"
        :group-names="{
          smileys_people: 'Smileys & Personnes',
          animals_nature: 'Animaux & Nature',
          food_drink: 'Nourriture & Boissons',
          activities: 'Activités',
          travel_places: 'Voyages & Lieux',
          objects: 'Objets',
          symbols: 'Symboles',
          flags: 'Drapeaux',
          recent: 'Récents'
        }"
        :static-texts="{
          placeholder: 'Rechercher un emoji',
          skinTone: 'Teint de peau'
        }"
      />
    </div>
  </div>
  <AlertModeratorsModal :room="room" :show="alertingModerators" @reported="reported = true" @close="alertingModerators = false" />
</template>
