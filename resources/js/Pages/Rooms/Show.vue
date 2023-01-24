<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import RoomLayout from '@/Layouts/RoomLayout.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import Spinner from '@/Components/Spinner.vue'
import Chat from '@/Components/Chat/Chat.vue'
import Share from '@/Components/Share.vue'
import Tip from '@/Components/Tip.vue'
import Modal from '@/Components/Modal.vue'
import LoginForm from '@/Pages/Auth/LoginForm.vue'

import Controls from './partials/Controls.vue'
import Player from './partials/Player.vue'
import UserInput from './partials/UserInput.vue'
import Answers from './partials/Answers.vue'
import Ranking from './partials/Ranking.vue'
import FinishedRoundModal from './partials/FinishedRoundModal.vue'
import SendSuggestionModal from './partials/SendSuggestionModal.vue'

const props = defineProps({
  room: Object,
})

const user = usePage().props.auth.user
const channel = `rooms.${props.room.id}`
const round = ref(null)
const joined = ref(false)
const users = ref([])
const data = ref(null)
const roundFinished = ref(false)
const sendingSuggestion = ref(false)
const showSidebar = ref(true)
const currentTime = ref(0)
const users_podium = ref([])
const teams_podium = ref([])

onMounted(() => {
  if(user) {
    Echo.join(channel)
      .here((usersHere) => {
        users.value = usersHere
        joining()
      })
      .joining((user) => {
        users.value.push(user)
      })
      .leaving((user) => {
        users.value = users.value.filter((u) => u.id !== user.id)
      })
      .error((error) => {
        console.error(error)
      })
  }
})

onUnmounted(() => {
  Echo.leave(channel)
})

const joining = () => {
  axios.get(`/rooms/${props.room.id}/joined`).then(() => {
    joined.value = true
    listenRounds()
  })
}

const listenRounds = () => {
  Echo.channel(channel)
    .listen('RoundStarted', (e) => {
      console.warn('Round started')
      round.value = e.round
      roundFinished.value = false
    })
    .listen('RoundFinished', (e) => {
      console.warn('Round finished')
      round.value = e.round
      users_podium.value = e.users_podium
      teams_podium.value = e.teams_podium
      roundFinished.value = true
    })
    .listen('TrackPlayed', (e) => {
      round.value = e.round
      props.room.value = e.room
    })
}
</script>
<template>
  <RoomLayout>

    <Modal v-if="!user" :show="true">
      <LoginForm :isFromModal="true" />
    </Modal>

    <div v-if="!joined && user" class="flex h-full w-full items-center justify-center">
      <Spinner />
      <h2>{{ __('Loading') }}...</h2>
    </div>

    <Transition name="slide-right">
      <div v-if="joined || !user" class="h-full md:flex">
        <div class="relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8" scroll-region>
          <article class="mb-4 flex items-center">
            <h2 class="mr-2 text-xl font-bold">{{ room.name }}</h2>
            <Share :url="room.url" class="w-5"/>
          </article>

          <Tip class="bg-orange-400 text-orange-800" v-if="!room.is_autostart && (!round || !round.is_playing) && !room.is_playing">
            <span class="font-bold">{{ __('This room is in manual start mode.') }}</span>
            <br> {{ __('The person responsible for the room (moderators) must be present to start the game.') }}
          </Tip>

          <div class="mb-4 md:mb-8" v-if="user">
            <Player :room="room" :channel="channel" @track:currentTime="currentTime = $event" />
            <UserInput :channel="channel" :currentTime="currentTime" :room="room" />
          </div>

          <div class="grid md:grid-cols-2 md:gap-8">
            <Answers class="mb-4 md:mb-8" :users="users" :channel="channel" />
            <Ranking class="mb-4 md:mb-8" :room="room" :users="users" :channel="channel" :data="data" />
          </div>

          <template v-if="user">
            <Controls v-if="room.moderators.find(x => user.id === x.id)" :room="room" :round="round" :channel="channel" class="mb-4" />
          </template>

          <Card>
            <div class="flex items-center flex-col lg:flex-row lg:justify-between gap-4 text-sm">
              <div>
                <div class="mx-auto flex flex-wrap items-center gap-4">
                  <span class="uppercase text-neutral-500">Modos</span>
                  <span v-for="moderator in room.moderators" class="flex items-center" :class="{ 'font-bold text-teal-500': users.find((x) => moderator.id === x.id) }"><img :src="moderator.photo" :alt="moderator.name" :title="moderator.name" class="mr-1 h-8 w-8 rounded-full" /> {{ moderator.name }}</span>
                </div>
              </div>
              <div class="flex items-center gap-4" v-if="user">
                <button class="btn-secondary bg-neutral-900 btn-sm" @click="sendingSuggestion = true">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                  </svg>
                  {{ __('Send a suggestion') }}
                </button>
                <span class="uppercase text-neutral-500">{{ room.tracks_count }} audios</span>
              </div>
            </div>
          </Card>

          <button v-if="user && room.is_chat_active" class="absolute right-0 top-5 hidden rounded-l-lg bg-neutral-800 p-2 md:block" @click="showSidebar = !showSidebar" :title="__('Hide/Show chatbox')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </button>
        </div>

        <div v-if="user && showSidebar && room.is_chat_active" class="flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5">
          <Chat :room="room" />
        </div>
      </div>
    </Transition>

    <FinishedRoundModal v-if="round" :show="roundFinished" :round="round" :users_podium="users_podium" :teams_podium="teams_podium" @close="roundFinished = false" />  
    <SendSuggestionModal v-if="user" :show="sendingSuggestion" :room="room" @close="sendingSuggestion = false" />

  </RoomLayout>
</template>
