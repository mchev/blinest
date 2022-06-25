<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const props = defineProps({
  room: Object,
  channel: String,
})

const audio = new Audio()
const track = ref(null)
const loading = ref(true)
const isPlaying = ref(false)
const error = ref(null)
const percent = ref(0)
const shaking = ref(false)

onMounted(() => {
  Echo.channel(props.channel).listen('TrackPlayed', (e) => {
    console.log('Track played')
    track.value = e.data.track
    play()
  })
  Echo.channel(props.channel).listen('TrackEnded', (e) => {
    console.log('Track ended')
    stop()
  })
})

onUnmounted(() => {
  if (isPlaying.value) {
    stop()
  }
  Echo.leave(`rooms.${props.room.id}`)
})

const emit = defineEmits(['track:played', 'track:ended', 'track:paused', 'track:stopped'])

const play = () => {
  if (isPlaying) {
    stop()
  }

  loading.value = true
  isPlaying.value = true

  audio.src = track.value.preview_url

  audio.addEventListener('error', () => {
    error.value = audio.error.message
    isPlaying.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    let playPromise = audio.play()

    // If a user gesture is needed (https://developer.chrome.com/blog/play-returns-promise/)
    if (playPromise !== undefined) {
      playPromise.catch(function (error) {
        console.error('Need to handle when the user come directly to a room.')
      })
    }
  })

  audio.addEventListener('timeupdate', () => {
    percent.value = parseInt((100 / props.room.track_duration) * (audio.currentTime + 0.25))
    shaking.value = percent.value > 85 ? true : false
  })

  audio.addEventListener('ended', () => {
    isPlaying.value = false
    loading.value = true
    shaking.value = false
    emit('track:ended', props.track)
  })
}

const pause = () => {
  audio.pause()
  emit('track:paused', props.track)
}

const stop = () => {
  shaking.value = false
  audio.pause()
  emit('track:stopped', props.track)
}
</script>
<template>
  <div id="player" class="flex h-10 w-full items-center overflow-hidden rounded-lg bg-teal-200" :class="{ 'animate-shake': shaking }">
    <div v-if="error" class="text-red-500">
      {{ error }}
    </div>
    <div v-else-if="loading" class="flex h-10 w-full animate-pulse items-center justify-center rounded-lg bg-teal-500">
      {{ __('Loading') }}
    </div>
    <div v-else class="shine h-10 rounded-lg bg-gradient-to-br from-teal-300 to-teal-400 transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" />
  </div>
</template>
