<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
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

  Echo.channel(channel).listen('TrackPlayed', (e) => {
    console.log(e)
  })
})

onUnmounted(() => {
  Echo.leave(`rooms.${props.room.id}`)
})

const track = ref({
  src: 'https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview124/v4/8a/1e/03/8a1e0314-b2f1-2ac0-cff5-7298164da844/mzaf_10401051262985820957.plus.aac.p.m4a',
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
    <h2>{{ room.name }}</h2>
    <Player :track="track" @track:ended="trackEnded" @track:paused="trackPaused" @track:stopped="trackStopped" />
    <!--     
    Scores
    Team scores
    Chat
    Player
    Answers
-->
  </Layout>
</template>
