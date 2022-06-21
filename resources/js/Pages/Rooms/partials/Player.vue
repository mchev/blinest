<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  track: String
})

const audio = new Audio()
const loading = ref(true)
const error = ref(null)
const percent = ref(0)
const duration = ref(0)

onMounted(() => {
  //play()
})

onUnmounted(() => {
  stop()
})

const emit = defineEmits(['track:ended', 'track:paused', 'track:stopped'])

const play = () => {
  loading.value = true
  audio.src = props.track.src

  audio.addEventListener('loadeddata', () => {
    duration.value = audio.duration
  })

  audio.addEventListener('error', () => {
    error.value = audio.error.message
  })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    audio.play()
  })

  audio.addEventListener('timeupdate', () => {
    percent.value = parseInt((100 / duration.value) * (audio.currentTime + 0.25))
    console.log(percent.value)
  })

  audio.addEventListener('ended', () => {
    console.log('Finnish!')
    emit('track:ended', props.track)
  })
}

const pause = () => {
  audio.pause()
  emit('track:paused', props.track)
}

const stop = () => {
  audio.pause()
  audio.currentTime = 0
  audio.pause()
  emit('track:stopped', props.track)
}
</script>
<template>
  <div id="player" class="bg-teal-200 h-8 w-full rounded-full">
    <div v-if="error">
      {{ error }}
    </div>
    <div v-else-if="loading" class="h-8 w-full animate-pulse rounded-full bg-green-600 dark:bg-green-300" />
    <div v-else class="shine from-teal-300 to-teal-400 h-8 rounded-full bg-gradient-to-br transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" />
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
