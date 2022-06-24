<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Spinner from '@/Components/Spinner.vue'
import Modal from '@/Components/Modal.vue'
import Player from './partials/Player.vue'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const joined = ref(false)
const roundFinished = ref(false)
const roundFinishedModal = ref(false)

onMounted(() => {
  Echo.join(channel)
    .here((users) => {
      userHasJoinedTheChannel()
    })
    .joining((user) => {})
    .leaving((user) => {})
    .error((error) => {
      console.error(error)
    })
})

onUnmounted(() => {
  Echo.leave(channel)
})

const userHasJoinedTheChannel = () => {
  axios.get(`/rooms/${props.room.id}/joined`).then(() => {
    joined.value = true
    listenRounds()
  })
}

const listenRounds = () => {
  Echo.channel(channel).listen('RoundStarted', (round) => {
    console.warn('Round started')
    roundFinished.value = false
    roundFinishedModal.value = false
  })

  Echo.channel(channel).listen('RoundFinished', (round) => {
    console.warn('Round finished')
    roundFinished.value = true
    roundFinishedModal.value = true
    // Get scores
    // Wait for new round
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

    <Transition name="slide-fade">
      <div v-if="joined">
        <article class="prose dark:prose-invert">
          <h2>{{ room.name }}</h2>
          <ul>
            <li>Find a way to only start when there is users</li>
            <li>Player : listen for users when right anwsers (display avatar instead of username?)</li>
          </ul>
        </article>

        <Player :room="room" @track:ended="trackEnded" @track:paused="trackPaused" @track:stopped="trackStopped" />
      </div>
    </Transition>

    <Modal :show="roundFinishedModal" @close="roundFinishedModal = false"> Test </Modal>
  </Layout>
</template>
