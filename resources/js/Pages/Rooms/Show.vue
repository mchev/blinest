<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import RoomLayout from '@/Layouts/RoomLayout.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import Spinner from '@/Components/Spinner.vue'
import Chat from '@/Components/Chat/Chat.vue'
import UserGestureModal from '@/Components/UserGestureModal.vue'

import Player from './partials/Player.vue'
import UserInput from './partials/UserInput.vue'
import Answers from './partials/Answers.vue'
import Ranking from './partials/Ranking.vue'
import FinishedRoundModal from './partials/FinishedRoundModal.vue'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const round = ref(null)
const joined = ref(false)
const users = ref(null)
const data = ref(null)
const roundFinished = ref(false)
const showSidebar = ref(true)
const currentTime = ref(0)

onMounted(() => {
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
      round.value = null
      roundFinished.value = true
    })
    .listen('TrackPlayed', (e) => {
      round.value = e.round
      props.room.value = e.room
    })
}
</script>
<template>
  <Head :title="room.name" />
  <RoomLayout>
    <div v-if="!joined" class="flex h-full w-full items-center justify-center">
      <Spinner />
      <h2>{{ __('Loading') }}...</h2>
    </div>

    <Transition name="slide-right">
      <div v-if="joined" class="h-full md:flex">
        <div class="relative flex-1 overflow-y-auto p-4 md:px-12 md:py-8" scroll-region>
          <article class="mb-4 flex items-center">
            <h2 class="mr-8 text-xl font-bold">{{ room.name }}</h2>
          </article>

          <div class="mb-4 md:mb-8">
            <Player :room="room" :channel="channel" @track:currentTime="currentTime = $event" />
            <UserInput :channel="channel" :currentTime="currentTime" :room="room" />
          </div>

          <div class="grid md:grid-cols-2 md:gap-8">
            <Answers class="mb-4 md:mb-8" :users="users" :channel="channel" />
            <Ranking class="mb-4 md:mb-8" :room="room" :users="users" :channel="channel" :data="data" />
          </div>

          <Card>
            <div class="flex items-center justify-between px-4 text-sm">
              <div>
                <div class="mx-auto flex flex-wrap items-center gap-4">
                  <span class="uppercase text-neutral-500">Modos</span>
                  <span v-for="moderator in room.moderators" class="flex items-center" :class="{ 'font-bold text-teal-500': users.find((x) => moderator.id === x.id) }"><img :src="moderator.photo" :alt="moderator.name" :title="moderator.name" class="mr-1 h-8 w-8 rounded-full" /> {{ moderator.name }}</span>
                </div>
              </div>
              <div class="flex items-center">
                <button class="btn-secondary bg-neutral-900 btn-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1 h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                  </svg>
                  Envoyer une suggestion
                </button>
              </div>
            </div>
          </Card>

          <button v-if="room.is_chat_active" class="absolute right-0 top-5 hidden rounded-l-lg bg-neutral-800 p-2 md:block" @click="showSidebar = !showSidebar" :title="__('Hide/Show chatbox')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </button>
        </div>

        <div v-if="showSidebar && room.is_chat_active" class="flex h-96 w-full flex-shrink-0 flex-col rounded-tl border-neutral-700 bg-neutral-800 transition-all duration-300 md:h-full md:w-1/5">
          <Chat :room="room" />
        </div>
      </div>
    </Transition>

    <FinishedRoundModal :show="roundFinished" @close="roundFinished = false" />
    <UserGestureModal />
  </RoomLayout>
</template>
