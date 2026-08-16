<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import LiveFeed from './LiveFeed.vue'
import Ranking from './Ranking.vue'
import Answers from './Answers.vue'
import Chat from '@/Components/Chat/Chat.vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: 'live',
  },
  room: {
    type: Object,
    required: true,
  },
  roomState: {
    type: Object,
    required: true,
  },
  round: {
    type: Object,
    default: null,
  },
  channel: {
    type: String,
    required: true,
  },
  currentTrack: {
    type: Object,
    default: null,
  },
  playlistPlayedTracks: {
    type: Array,
    default: () => [],
  },
  liveEvents: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const page = usePage()

function t(key, replace = {}) {
  let translation = page.props.language?.[key] ?? key

  Object.entries(replace).forEach(([placeholder, value]) => {
    translation = translation.replace(`:${placeholder}`, String(value))
  })

  return translation
}

const tabs = computed(() => {
  const items = [
    { id: 'live', label: t('Live') },
    { id: 'rank', label: t('Ranking') },
  ]

  if (props.room.is_chat_active) {
    items.push({ id: 'chat', label: t('Chat') })
  }

  items.push({ id: 'playlist', label: t('Playlist') })

  return items
})

const activeTab = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const selectTab = (tabId) => {
  activeTab.value = tabId
}

const panelClass = (tabId) => [
  'room-mobile-hud__panel-pane h-full min-h-0',
  activeTab.value === tabId ? 'flex flex-col' : 'hidden',
]
</script>

<template>
  <div class="room-mobile-hud">
    <div class="room-mobile-hud__tabs" role="tablist" :aria-label="__('Room panels')">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        type="button"
        role="tab"
        class="room-mobile-hud__tab"
        :class="{ 'room-mobile-hud__tab--active': activeTab === tab.id }"
        :aria-selected="activeTab === tab.id"
        @click="selectTab(tab.id)"
      >
        <span class="room-mobile-hud__tab-icon" aria-hidden="true">
          <svg v-if="tab.id === 'live'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 01.359.852L12.982 9.75h7.268a.75.75 0 01.548 1.262l-10.5 11.25a.75.75 0 01-1.272-.71l1.992-7.302H3.75a.75.75 0 01-.548-1.262l10.5-11.25a.75.75 0 01.913-.143z" clip-rule="evenodd" />
          </svg>
          <Icon v-else-if="tab.id === 'rank'" name="trophy" class="h-full w-full" />
          <svg v-else-if="tab.id === 'chat'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.678 3.348-3.97z" clip-rule="evenodd" />
          </svg>
          <Icon v-else-if="tab.id === 'playlist'" name="playlist" class="h-full w-full" />
        </span>
        <span class="room-mobile-hud__tab-label">{{ tab.label }}</span>
      </button>
    </div>

    <div class="room-mobile-hud__panel flex min-h-0 flex-1 flex-col overflow-hidden">
      <LiveFeed
        :class="panelClass('live')"
        :events="liveEvents"
      />

      <div :class="panelClass('rank')">
        <Ranking
          compact
          class="h-full"
          :room="room"
          :room-state="roomState"
          :track="currentTrack"
        />
      </div>

      <div v-if="room.is_chat_active" :class="panelClass('chat')">
        <Chat :room="room" embedded />
      </div>

      <div :class="panelClass('playlist')">
        <Answers
          compact
          class="h-full"
          :users="roomState.users"
          :channel="channel"
          :round="round"
          :room-id="room.id"
          :initial-played-tracks="playlistPlayedTracks"
        />
      </div>
    </div>
  </div>
</template>
