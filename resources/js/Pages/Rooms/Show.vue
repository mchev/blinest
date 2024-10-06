<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import RoomLayout from '@/Layouts/RoomLayout.vue'
import Icon from '@/Components/Icon.vue'
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

const props = defineProps({
  room: Object,
  public_rooms: Object,
})

const user = usePage().props.auth.user
const channel = `rooms.${props.room.id}`
const round = ref(null)
const joined = ref(false)
const users = ref([])
const data = ref(null)
const roundFinished = ref(false)
const sendingSuggestion = ref(false)
const displayChat = ref(true)
const currentTime = ref(0)
const users_podium = ref([])
const teams_podium = ref([])

onMounted(() => {
  if (user) {
    Echo.join(channel)
      .here((usersHere) => {
        users.value = usersHere
        joining()
        dispatchUserCount(usersHere.length)
        axios.post(`/rooms/${props.room.id}/update-user-count`, {
          count: usersHere.length
        });
      })
      .joining((user) => {
        users.value.push(user)
        dispatchUserCount(users.value.length)
      })
      .leaving((user) => {
        users.value = users.value.filter((u) => u.id !== user.id)
        dispatchUserCount(users.value.length)
      })
      .error((error) => {
        console.error(error)
      })
  }
})

const dispatchUserCount = (count) => {
  Echo.private(`room.count.${props.room.id}`)
    .whisper('updatedUserCount', {
      count: count
    })
}

onUnmounted(() => {
  Echo.leave(channel)
  axios.post(`/rooms/${props.room.id}/update-user-count`, {
    count: users.value.length
  })
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
    <Modal v-if="!user" :show="true" :maxWidth="'3xl'">
      <LoginForm :isFromModal="true" />
    </Modal>

    <div v-if="!joined && user" class="flex h-full w-full items-center justify-center">
      <Spinner />
      <h2>{{ __('Loading') }}...</h2>
    </div>

    <Transition name="slide-right">
      <div v-if="joined || !user" class="h-full md:flex">
        <div class="relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8" scroll-region>
          <article class="mb-4 flex flex-wrap gap-2 items-center justify-between">
            <div class="flex items-center">
              <h1 class="mr-2 text-xl font-bold">{{ room.name }}</h1>
            </div>
            <div class="flex items-center gap-2">
              <RoomActions :room="room" :channel="channel" :round="round" @displayChat="displayChat = $event"/>
            </div>
          </article>

          <Tip class="bg-orange-400 text-orange-800" v-if="!room.is_autostart && (!round || !round.is_playing) && !room.is_playing">
            <span class="font-bold">{{ __('This room is in manual start mode') }}</span>
            <br />
            {{ __('The person responsible for the room (moderators) must be present to start the game') }}
          </Tip>

          <div class="mb-4 md:mb-8" v-if="user">
            <Player :room="room" :channel="channel" @track:currentTime="currentTime = $event" />
            <UserInput :channel="channel" :currentTime="currentTime" :room="room" />
          </div>

          <div class="grid md:grid-cols-2 md:gap-8">
            <Answers class="mb-4 md:mb-8" :users="users" :channel="channel" />
            <Ranking class="mb-4 md:mb-8" :room="room" :users="users" :channel="channel" :data="data" />
          </div>

          <Card class="mb-8">
            <div class="flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between">
              <div>
                <div class="mx-auto flex flex-wrap items-center gap-4">
                  <span class="uppercase text-neutral-500">{{ __('Mods') }}</span>
                  <span v-for="moderator in room.moderators" class="flex items-center" :class="{ 'font-bold text-teal-500': users.find((x) => moderator.id === x.id) }"><img :src="moderator.photo" :alt="moderator.name" :title="moderator.name" class="mr-1 h-8 w-8 rounded-full" /> {{ moderator.name }}</span>
                </div>
              </div>
              <div class="flex items-center gap-4" v-if="user">
                <button v-if="user.can.sendSuggestion" class="btn-secondary btn-sm bg-neutral-900" @click="sendingSuggestion = true">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                  </svg>
                  {{ __('Send a suggestion') }}
                </button>
                <span class="uppercase text-neutral-500">{{ room.tracks_count }}&nbsp;{{ __('audios') }}</span>
              </div>
            </div>
          </Card>

          <Card>
            <ul class="flex items-center flex-wrap">
              <li class="badge" v-for="proom in public_rooms" :key="'room-' + proom.id">
                <Link :href="route('rooms.show', proom.slug)" class="flex items-center gap-2">
                  <img :src="proom.photo" :alt="proom.name" class="rounded-full h-5 w-5 shadow"/>
                  {{ proom.name }}
                </Link>
              </li>
            </ul>
          </Card>


        </div>

        <div v-if="user && displayChat && room.is_chat_active" class="flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5">
          <Chat :room="room" />
        </div>
      </div>
    </Transition>

    <FinishedRoundModal v-if="round" :show="roundFinished" :round="round" :users_podium="users_podium" :teams_podium="teams_podium" @close="roundFinished = false" />
    <SendSuggestionModal v-if="user && user.can.sendSuggestion" :show="sendingSuggestion" :room="room" @close="sendingSuggestion = false" />
  </RoomLayout>
</template>