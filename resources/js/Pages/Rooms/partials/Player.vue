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
  Echo.channel(props.channel)
    .listen('TrackPlayed', (e) => {
      console.log('Track played')
      track.value = e.track
      play()
    })
    .listen('TrackEnded', (e) => {
      console.log('Track ended')
      stop()
    })
    .listen('TrackPaused', () => {
      pause()
    })
    .listen('TrackResumed', () => {
      resume()
    })
})

onUnmounted(() => {
  if (isPlaying.value) {
    stop()
  }
  Echo.leave(props.channel)
})

const emit = defineEmits(['track:played', 'track:ended', 'track:paused', 'track:stopped'])

const play = () => {
  if (isPlaying.value) {
    stop()
  }

  loading.value = true
  error.value = null
  isPlaying.value = true

  audio.src = track.value.preview_url

  audio.addEventListener('error', () => {
    error.value = audio.error.message
    isPlaying.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
    let playPromise = audio.play()
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

const resume = () => {
  audio.play()
}

const stop = () => {
  shaking.value = false
  audio.pause()
  emit('track:stopped', props.track)
}
</script>
<template>
  <div id="player" class="flex h-4 w-full items-center overflow-hidden rounded-t-lg bg-purple-200">
    <div v-if="error" class="text-red-500">
      {{ error }}
    </div>
    <div v-else-if="loading" class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500">
      {{ __('Loading') }}
    </div>
    <div v-else class="shine h-4 rounded-r-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" />
  </div>
</template>
