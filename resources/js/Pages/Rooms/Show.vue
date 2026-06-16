<script setup>
import { ref, onMounted, onUnmounted, onBeforeUnmount } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import RoomLayout from '@/Layouts/RoomLayout.vue'
import Card from '@/Components/Card.vue'
import Spinner from '@/Components/Spinner.vue'
import Chat from '@/Components/Chat/Chat.vue'
import Tip from '@/Components/Tip.vue'
import Modal from '@/Components/Modal.vue'
import LoginForm from '@/Pages/Auth/LoginForm.vue'

import RoomActions from './partials/RoomActions.vue'
import Player from './partials/Player.vue'
import UserInput from './partials/UserInput.vue'
import Answers from './partials/Answers.vue'
import Ranking from './partials/Ranking.vue'
import FinishedRoundModal from './partials/FinishedRoundModal.vue'
import SendSuggestionModal from './partials/SendSuggestionModal.vue'
import { measureServerTimeOffset } from '@/composables/useTrackTiming'

const props = defineProps({
  room: {
    type: Object,
    required: true,
    validator: (v) => v && typeof v.id !== 'undefined',
  },
  public_rooms: {
    type: Object,
    default: () => ({}),
  },
})

const user = usePage().props.auth.user
const room = ref({ ...props.room })
const channel = `rooms.${room.value.id}`
const round = ref(null)
const joined = ref(false)
/** Single source of truth: server RoomState (users, scores, roundId) + client-side answersByUser from NewScore */
const roomState = ref({
  users: [],
  scores: {},
  roundId: null,
  answersByUser: {},
})

function normalizeScores(scores) {
  return scores && typeof scores === 'object' && !Array.isArray(scores) ? scores : {}
}
const roundFinished = ref(false)
const sendingSuggestion = ref(false)
const displayChat = ref(true)
const currentTime = ref(0)
const users_podium = ref([])
const teams_podium = ref([])
const initialTrack = ref(null)
const initialStartTime = ref(0)
const initialInterTrackPause = ref(null)
const currentTrack = ref(null)
/** Extraits déjà joués dans le round courant (hydratation GET /joined, pas de requête en plus) */
const playlistPlayedTracks = ref([])
/** Presence + events: one subscription (Echo.join = presence channel, .here/.joining/.leaving = list). */
let roundsChannel = null
/** Heartbeat to refresh Redis presence so home page count stays accurate (prune removes after 90s without heartbeat). */
let presenceHeartbeatTimer = null
/** 'connected' | 'reconnecting' for connection indicator */
const connectionState = ref('connected')
/** Handler for beforeunload so we can remove it (same reference for add/removeEventListener). */
function onBeforeUnloadPresenceLeft() {
  callPresenceLeft({ useBeacon: true })
}

function dispatchUserCount(count) {
  Echo.private(`room.count.${room.value.id}`).whisper('updatedUserCount', { count })
}

function startPresenceHeartbeat() {
  if (presenceHeartbeatTimer) return
  presenceHeartbeatTimer = setInterval(() => {
    if (!joined.value) return
    axios.post(`/rooms/${room.value.id}/presence-joined`).catch(() => {})
  }, 25000)
}

function stopPresenceHeartbeat() {
  if (presenceHeartbeatTimer) {
    clearInterval(presenceHeartbeatTimer)
    presenceHeartbeatTimer = null
  }
}

/**
 * Notify server that the user left the room (remove from presence list).
 * Used on navigation (onBeforeUnmount) and on tab/window close (beforeunload).
 * On beforeunload we use fetch(keepalive) + X-XSRF-TOKEN so the request is sent
 * when the tab closes (axios would be cancelled).
 */
function callPresenceLeft(options = {}) {
  const { useBeacon = false } = options
  const url = `/rooms/${room.value.id}/presence-left`
  if (useBeacon && typeof window !== 'undefined') {
    const token = document.cookie
      .split('; ')
      .find((row) => row.startsWith('XSRF-TOKEN='))
      ?.split('=')[1]
    const headers = new Headers()
    headers.set('X-Requested-With', 'XMLHttpRequest')
    headers.set('Accept', 'application/json')
    if (token) headers.set('X-XSRF-TOKEN', decodeURIComponent(token))
    if (typeof fetch !== 'undefined') {
      fetch(url, { method: 'POST', headers, credentials: 'same-origin', keepalive: true }).catch(() => {})
    }
    return
  }
  axios.post(url).catch(() => {})
}

async function hydrateRoomFromJoinedPayload(data) {
  initialInterTrackPause.value = data.interTrackPause ?? null

  if (data.round && data.track) {
    if (data.room) {
      Object.assign(room.value, data.room)
    }

    await measureServerTimeOffset(room.value.id, { samples: 1, maxAgeMs: 3000 }).catch(() => {})

    round.value = data.round
    initialTrack.value = data.interTrackPause ? null : data.track
    currentTrack.value = data.track
    initialStartTime.value = data.interTrackPause ? 0 : (data.startTime || 0)
    playlistPlayedTracks.value = Array.isArray(data.playedTracks) ? data.playedTracks : []

    if (!data.interTrackPause && data.round.id && data.track.id) {
      axios.post(`/rounds/${data.round.id}/tracks/${data.track.id}/listened`).catch(() => {})
    }
  } else {
    initialTrack.value = null
    initialStartTime.value = 0
    initialInterTrackPause.value = null
    currentTrack.value = null
    playlistPlayedTracks.value = Array.isArray(data.playedTracks) ? data.playedTracks : []
  }
}

function resyncRoomAfterReconnect() {
  if (!joined.value) return
  axios.get(`/rooms/${room.value.id}/joined`).then(async (response) => {
    await hydrateRoomFromJoinedPayload(response.data)
    if (response.data.scores) roomState.value.scores = normalizeScores(response.data.scores)
    if (response.data.roundId != null) roomState.value.roundId = response.data.roundId
    axios.post(`/rooms/${room.value.id}/presence-joined`).then((res) => {
      if (res.data?.scores != null) roomState.value.scores = normalizeScores(res.data.scores)
      if (res.data?.roundId != null) roomState.value.roundId = res.data.roundId
    }).catch(() => {})
  }).catch(() => {})
}

onMounted(() => {
  if (user) {
    // Presence channel: list from Reverb (.here = everyone on channel, .joining/.leaving = live updates).
    // When alone, .here() can be [] (Reverb sends list before adding us); ensure we appear.
    roundsChannel = Echo.join(channel)
      .error((err) => console.error(err))
      .here((users) => {
        const hasMe = users.some((u) => u.id === user.id)
        const list = hasMe ? users : [{ ...user }, ...users]
        roomState.value.users = list
        if (list.length > 0 && list[0].id === user.id) dispatchUserCount(list.length)
      })
      .joining((userJoining) => {
        if (!roomState.value.users.some((u) => u.id === userJoining.id)) {
          roomState.value.users = [...roomState.value.users, userJoining]
          if (roomState.value.users[0]?.id === user.id) dispatchUserCount(roomState.value.users.length)
        }
      })
      .leaving((userLeaving) => {
        roomState.value.users = roomState.value.users.filter((u) => u.id !== userLeaving.id)
        if (roomState.value.users[0]?.id === user.id) dispatchUserCount(roomState.value.users.length)
      })
      .listen('RoomState', (e) => {
        if (e.scores != null) roomState.value.scores = normalizeScores(e.scores)
        if (e.roundId != null) roomState.value.roundId = e.roundId
      })
      .listen('NewScore', (e) => {
        const score = e.score || e
        const uid = score.user_id
        if (uid != null) {
          roomState.value.scores = { ...roomState.value.scores, [uid]: score.total ?? 0 }
          if (score.answers && Array.isArray(score.answers)) {
            const prev = roomState.value.answersByUser[uid] || []
            roomState.value.answersByUser = {
              ...roomState.value.answersByUser,
              [uid]: [...prev, ...score.answers],
            }
          }
        }
      })
      .listen('RoundStarted', (e) => {
        round.value = e.round
        playlistPlayedTracks.value = []
        roundFinished.value = false
        roomState.value.roundId = e.round?.id ?? null
        roomState.value.scores = {}
        roomState.value.answersByUser = {}
        if (e.round?.id) fetchRoundScores(e.round.id)
      })
      .listen('RoundFinished', (e) => {
        round.value = e.round
        users_podium.value = e.users_podium
        teams_podium.value = e.teams_podium
        roundFinished.value = true
      })
      .listen('TrackPlayed', (e) => {
        round.value = e.round
        if (e.room) Object.assign(room.value, e.room)
        initialTrack.value = null
        initialStartTime.value = 0
        initialInterTrackPause.value = null
        currentTrack.value = e.track
        roomState.value.answersByUser = {}
        measureServerTimeOffset(room.value.id, { samples: 1, maxAgeMs: 3000 }).catch(() => {})
      })
      .listen('UserEloUpdated', (e) => {
        const u = roomState.value.users.find((x) => x.id === e.user_id)
        if (u) u.elo = e.elo
      })

    joining()

    const conn = window.Echo?.connector?.pusher?.connection
    if (conn) {
      conn.bind('state_change', (states) => {
        if (states.current === 'connecting' || states.current === 'reconnecting') {
          connectionState.value = 'reconnecting'
        } else if (states.current === 'connected') {
          connectionState.value = 'connected'
          if (states.previous === 'disconnected' || states.previous === 'unavailable' || states.previous === 'failed') {
            resyncRoomAfterReconnect()
          }
        }
      })
    }
  }
  window.addEventListener('beforeunload', onBeforeUnloadPresenceLeft)
})

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', onBeforeUnloadPresenceLeft)
  stopPresenceHeartbeat()
  callPresenceLeft()
})

onUnmounted(() => {
  if (roundsChannel) {
    roundsChannel.stopListening('RoundStarted')
    roundsChannel.stopListening('RoundFinished')
    roundsChannel.stopListening('TrackPlayed')
    roundsChannel.stopListening('UserEloUpdated')
    roundsChannel.stopListening('RoomState')
    roundsChannel.stopListening('NewScore')
  }
  Echo.leave(channel)
})

const joining = () => {
  axios.get(`/rooms/${room.value.id}/joined`).then(async (response) => {
    await hydrateRoomFromJoinedPayload(response.data)
    // Scores/roundId from server. User list = only from presence channel (.here/.joining/.leaving).
    if (response.data.scores != null) roomState.value.scores = normalizeScores(response.data.scores)
    if (response.data.roundId != null) {
      roomState.value.roundId = response.data.roundId
    }
    joined.value = true
    // Notify server for Redis count (home page) and to broadcast scores/roundId. User list = from Echo .here/.joining/.leaving.
    axios.post(`/rooms/${room.value.id}/presence-joined`).then((res) => {
      if (res.data?.scores != null) roomState.value.scores = normalizeScores(res.data.scores)
      if (res.data?.roundId != null) roomState.value.roundId = res.data.roundId
    }).catch(() => {})
    startPresenceHeartbeat()
  }).catch((err) => {
    console.error('Error joining room:', err)
  })
}

const fetchRoundScores = async (roundId) => {
  if (!roundId) return
  try {
    const res = await axios.get(`/rounds/${roundId}/scores`)
    if (res.data.scores) {
      roomState.value.scores = { ...roomState.value.scores, ...res.data.scores }
    }
  } catch (e) {
    console.error('Error fetching round scores:', e)
  }
}

</script>
<template>
  <RoomLayout>
    <Modal v-if="!user" :show="true" :maxWidth="'3xl'">
      <LoginForm :isFromModal="true" />
    </Modal>

    <div v-if="!joined && user" class="flex h-full w-full items-center justify-center space-x-4">
      <Spinner class="h-8 w-8" />
      <div class="flex flex-col">
        <h2 class="text-xl font-medium text-neutral-200">{{ __('Loading') }}...</h2>
        <p class="text-sm text-neutral-400 mt-1">{{ __('Connecting to the room') }}</p>
      </div>
    </div>

    <Transition name="slide-right">
      <div v-if="joined || !user" class="h-full md:flex">
        <div class="relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8" scroll-region>
          <article class="mb-6 flex flex-wrap gap-4 items-center justify-between">
            <div class="flex items-center space-x-3">
              <h1 class="text-2xl font-bold text-neutral-100">{{ room.name }}</h1>
              <span v-if="user" class="flex items-center gap-1.5 text-sm font-medium text-neutral-400" :class="{ 'text-amber-400': connectionState === 'reconnecting' }">
                <span class="h-2 w-2 rounded-full shrink-0" :class="connectionState === 'connected' ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse'"></span>
                {{ connectionState === 'connected' ? __('Connected') : __('Reconnecting…') }}
              </span>
            </div>
            <div class="flex items-center gap-3">
              <RoomActions :room="room" :channel="channel" :round="round" @displayChat="displayChat = $event"/>
            </div>
          </article>

          <Tip class="mb-6 bg-orange-400/10 border border-orange-400/20 text-orange-200" v-if="!room.is_autostart && (!round || !round.is_playing) && !room.is_playing">
            <div class="flex items-center space-x-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <div>
                <span class="font-bold">{{ __('This room is in manual start mode') }}</span>
                <p class="text-sm">{{ __('The person responsible for the room (moderators) must be present to start the game') }}</p>
              </div>
            </div>
          </Tip>

          <div class="mb-8 space-y-2" v-if="user">
            <Player
              :room="room"
              :channel="channel"
              :initialTrack="currentTrack || initialTrack"
              :initialStartTime="initialStartTime"
              :initialTrackTiming="round"
              :initialInterTrackPause="initialInterTrackPause"
              @track:currentTime="currentTime = $event"
            />
            <UserInput :channel="channel" :currentTime="currentTime" :room="room" :initialTrack="currentTrack || initialTrack" :initialRound="round" />
          </div>

          <div class="grid gap-8 md:grid-cols-2">
            <Answers
              class="mb-4 md:mb-0"
              :users="roomState.users"
              :channel="channel"
              :round="round"
              :room-id="room.id"
              :initial-played-tracks="playlistPlayedTracks"
            />
            <Ranking class="mb-4 md:mb-0" :room="room" :room-state="roomState" :track="currentTrack || initialTrack" />
          </div>

          <Card class="mt-8">
            <template #header v-if="room.description">
              <h3 class="text-sm">{{ room.description }}</h3>
            </template>
            <div class="flex flex-col gap-6 text-sm lg:flex-row lg:justify-between">
              <div class="w-full">
                <div class="flex flex-wrap items-center gap-4">
                  <span class="text-sm font-medium uppercase text-neutral-400">{{ __('Moderators') }}</span>
                  <div class="flex flex-wrap gap-3">
                    <span v-for="moderator in room.moderators" 
                          class="flex items-center space-x-2 rounded-full bg-neutral-700/50 px-3 py-1.5" 
                          :class="{ 'ring-2 ring-teal-500': roomState.users.find((x) => moderator.id === x.id) }">
                      <img :src="moderator.photo" 
                           :alt="moderator.name" 
                           :title="moderator.name" 
                           class="h-6 w-6 rounded-full ring-1 ring-neutral-600" />
                      <span class="font-medium" :class="{ 'text-teal-400': roomState.users.find((x) => moderator.id === x.id) }">
                        {{ moderator.name }}
                      </span>
                    </span>
                  </div>
                </div>
              </div>
              <div class="flex flex-wrap md:flex-nowrap items-center gap-2 sm:gap-3 lg:gap-4" v-if="user">
                <button type="button" class="btn-secondary btn-sm flex-shrink-0 whitespace-nowrap">
                  <span class="font-medium">{{ room.tracks_count != null ? room.tracks_count : '-' }}</span>
                  <span>{{ __('Tracks') }}</span>
                </button>
                <button v-if="room.rounds_count != null && room.rounds_count > 0"
                        type="button"
                        class="btn-secondary btn-sm flex-shrink-0 whitespace-nowrap">
                  <span class="font-medium">{{ room.rounds_count }}</span>
                  <span>{{ __('Rounds played') }}</span>
                </button>
                <button v-if="user.can.sendSuggestion" 
                        class="btn-secondary btn-sm flex-shrink-0 whitespace-nowrap" 
                        @click="sendingSuggestion = true">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                  </svg>
                  <span>{{ __('Send a suggestion') }}</span>
                </button>
              </div>
            </div>
          </Card>

          <Card class="mt-8 hidden md:block">
            <div class="flex flex-wrap items-center gap-3">
              <span class="text-sm font-medium uppercase text-neutral-400">{{ __('Public Rooms') }}</span>
              <div class="flex flex-wrap gap-2">
                <Link v-for="proom in public_rooms" 
                      :key="'room-' + proom.id"
                      :href="route('rooms.show', proom.slug)" 
                      class="flex items-center space-x-2 rounded-full bg-neutral-700/50 px-3 py-1.5 hover:bg-neutral-600/50 transition-colors">
                  <img :src="proom.photo" 
                       :alt="proom.name" 
                       class="h-5 w-5 rounded-full ring-1 ring-neutral-600"/>
                  <span class="font-medium">{{ proom.name }}</span>
                </Link>
              </div>
            </div>
          </Card>
        </div>

        <div v-if="user && displayChat && room.is_chat_active" 
             class="flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-black/20 backdrop-blur-sm transition-all duration-300 md:h-full md:w-1/5">
          <Chat :room="room" />
        </div>
      </div>
    </Transition>

    <FinishedRoundModal v-if="round" 
                       :show="roundFinished" 
                       :round="round" 
                       :room="room"
                       :users_podium="users_podium" 
                       :teams_podium="teams_podium" 
                       @close="roundFinished = false" />
    <SendSuggestionModal v-if="user && user.can.sendSuggestion" 
                        :show="sendingSuggestion" 
                        :room="room" 
                        @close="sendingSuggestion = false" />
  </RoomLayout>
</template>