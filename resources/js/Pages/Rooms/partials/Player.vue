<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const props = defineProps({
  room: Object,
})

const channel = `rooms.${props.room.id}`
const audio = new Audio()
const track = ref(null)
const loading = ref(true)
const isPlaying = ref(false)
const error = ref(null)
const percent = ref(0)
const duration = ref(0)

onMounted(() => {
  Echo.channel(channel).listen('TrackPlayed', (e) => {
    console.log(e)
    track.value = e.data.track
    play()
  })
})

onUnmounted(() => {
  Echo.leave(`rooms.${props.room.id}`)
})

const emit = defineEmits(['track:ended', 'track:paused', 'track:stopped'])

const play = () => {
  if (isPlaying.value) {
    stop()
  }

  loading.value = true
  isPlaying.value = true

  audio.src = track.value.preview_url

  audio.addEventListener('loadeddata', () => {
    duration.value = audio.duration
  })

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
    percent.value = parseInt((100 / duration.value) * (audio.currentTime + 0.25))
  })

  audio.addEventListener('ended', () => {
    isPlaying.value = false
    emit('track:ended', props.track)
  })
}

const pause = () => {
  audio.pause()
  emit('track:paused', props.track)
}

const stop = () => {
  audio.pause()
  emit('track:stopped', props.track)
}
</script>
<template>
  <div id="player" class="flex h-8 w-full items-center justify-center rounded-full bg-teal-200">
    <div v-if="error" class="text-red-500">
      {{ error }}
    </div>
    <div v-else-if="loading" class="h-8 w-full animate-pulse rounded-full bg-green-600 dark:bg-green-300" />
    <div v-else class="shine h-8 rounded-full bg-gradient-to-br from-teal-300 to-teal-400 transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" />
  </div>

  <button type="button" @click="play">Play</button>
  <button type="button" @click="pause">Pause</button>
  <button type="button" @click="stop">Stop</button>
</template>

<style scoped>
.shine {
  position: relative;
}

.shine::after {
  content: '';
  opacity: 0;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: #fff;
  border-radius: 10px;
  animation: animate-shine 2.5s ease-out infinite;
  animation-delay: 10s;
}

@keyframes animate-shine {
  0% {
    opacity: 0;
    width: 0;
  }
  50% {
    opacity: 0.25;
  }
  100% {
    opacity: 0;
    width: 95%;
  }
}
</style>
