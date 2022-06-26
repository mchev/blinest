<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Spinner from '@/Components/Spinner.vue'

import Player from './partials/Player.vue'
import UserInput from './partials/UserInput.vue'
import Answers from './partials/Answers.vue'
import Ranking from './partials/Ranking.vue'
import FinishedRoundModal from './partials/FinishedRoundModal.vue'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const joined = ref(false)
const users = ref(null)
const data = ref(null)
const roundFinished = ref(false)

onMounted(() => {
  Echo.join(channel)
    .here((usersHere) => {
      console.log(usersHere)
      users.value = usersHere
      joining()
    })
    .joining((user) => {
      users.value.push(user)
    })
    .leaving((user) => {
      users.value = users.value.filter(u => (u.id !== user.id))
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
  Echo.channel(channel).listen('RoundStarted', (round) => {
    console.warn('Round started')
    round.value = round
    roundFinished.value = false
  })

  Echo.channel(channel).listen('RoundFinished', (round) => {
    console.warn('Round finished')
    round.value = null
    roundFinished.value = true
    // Get scores
    // Wait for new round
  })

  Echo.channel(channel).listen('TrackPlayed', (e) => {
    console.log(e)
    data.value = e.data
  })

}

const trackEnded = (track) => {
  // console.log('Track ended.')
}
const trackPaused = (track) => {
  // console.log('Track paused.')
}
const trackStopped = (track) => {
  // console.log('Track stopped.')
}
</script>
<template>
  <Head :title="room.name" />
  <Layout>
    <div v-if="!joined" class="flex h-full w-full items-center justify-center">
      <Spinner />
      <h2>{{ __('Loading') }}...</h2>
    </div>

    <Transition name="slide-right">
      <div v-if="joined">

        <article class="prose dark:prose-invert mb-4">
          <h2>{{ room.name }}</h2>
        </article>

        <div class="mb-8">
          <Player :room="room" :channel="channel" @track:ended="trackEnded" @track:paused="trackPaused" @track:stopped="trackStopped" />
          <UserInput :data="data"/>
        </div>

        <div class="flex w-full gap-8">
          <Answers class="w-full md:w-1/2" :users="users"/>
          <Ranking class="w-full md:w-1/2" :users="users" :data="data"/>
        </div>

      </div>
    </Transition>

    <FinishedRoundModal :show="roundFinished" @close="roundFinished = false"/>

  </Layout>
</template>
