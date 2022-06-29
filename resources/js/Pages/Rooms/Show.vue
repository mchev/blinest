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
import Controls from './partials/Controls.vue'
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
  Echo.channel(channel).listen('RoundStarted', (e) => {
    console.warn('Round started')
    round.value = e.round
    roundFinished.value = false
  })

  Echo.channel(channel).listen('RoundFinished', (e) => {
    console.warn('Round finished')
    round.value = null
    roundFinished.value = true
  })

  Echo.channel(channel).listen('TrackPlayed', (e) => {
    data.value = e.data
  })
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
        <article class="prose mb-4 dark:prose-invert">
          <h2>{{ room.name }}</h2>
        </article>

        <div class="mb-8">
          <Player :room="room" :channel="channel" />
          <UserInput :data="data" :channel="channel" />
        </div>

        <div class="grid md:grid-cols-2 md:gap-8">
          <Answers class="mb-8" :users="users" :channel="channel" />
          <Ranking class="mb-8" :users="users" :channel="channel" :data="data" />
        </div>

        <Controls :channel="channel" :room="room" :round="round" />
      </div>
    </Transition>

    <FinishedRoundModal :show="roundFinished" @close="roundFinished = false" />
  </Layout>
</template>
