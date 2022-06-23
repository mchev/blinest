<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Player from './partials/Player.vue'

const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`

onMounted(() => {
  Echo.join(channel)
    .here((users) => {
      console.log(users)
    })
    .joining((user) => {})
    .leaving((user) => {})

  Echo.channel(channel).listen('RoundStarted', (round) => {
    console.warn('Round started');
  })

  Echo.channel(channel).listen('RoundFinished', (round) => {
    console.warn('Round finished');
    // Get scores
    // Wait for new round
  })

})

onUnmounted(() => {
  Echo.leave(channel)
})

const trackEnded = (track) => {
  console.log('Track ended.')
}
const trackPaused = (track) => {
  console.log('Track paused.')
}
const trackStopped = (track) => {
  console.log('Track stopped.')
}
</script>
<template>
  <Head title="Create Room" />
  <Layout>
    <article class="prose">
      <h2>{{ room.name }}</h2>
      <ul>
        <li>Find a way to only start when there is users</li>
        <li>Player : listen for users when right anwsers (display avatar instead of username?)</li>
      </ul>
    </article>
    <Player :room="room" @track:ended="trackEnded" @track:paused="trackPaused" @track:stopped="trackStopped" />
    <!--     
    Scores
    Team scores
    Chat
    Player
    Answers
-->
  </Layout>
</template>
