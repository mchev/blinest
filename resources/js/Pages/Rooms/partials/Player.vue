<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  room: Object,
  channel: String,
})

const emit = defineEmits(['track:played', 'track:ended', 'track:paused', 'track:stopped', 'track:currentTime'])

const audio = new Audio()
const track = ref(null)
const loading = ref(true)
const isPlaying = ref(false)
const error = ref(null)
const percent = ref(0)
const usersWithAllAnswers = ref([])

onMounted(() => {
  window.addEventListener('volume-localstorage-changed', (event) => {
    audio.volume = event.detail.volume
  })
  Echo.channel(props.channel)
    .listen('TrackPlayed', (e) => {
      console.log('Track played')
      track.value = e.track
      play()
    })
    .listen('TrackEnded', (e) => {
      console.log('Track ended')
      usersWithAllAnswers.value = []
      stop()
    })
    .listen('TrackPaused', () => {
      pause()
    })
    .listen('TrackResumed', () => {
      resume()
    })
    .listen('UserHasFoundAllTheAnswers', (e) => {
      usersWithAllAnswers.value.push(e.user)
    })
})

onUnmounted(() => {
  stop()
  Echo.leave(props.channel)
})

const play = () => {
  if (isPlaying.value) {
    stop()
  }

  loading.value = true
  error.value = null
  isPlaying.value = true

  audio.src = track.value.preview_url
  audio.crossOrigin = 'anonymous'

  audio.addEventListener('error', () => {
    error.value = audio.error.message
    isPlaying.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
    let playPromise = audio.play()
  })

  audio.addEventListener('timeupdate', () => {
    emit('track:currentTime', audio.currentTime)
    percent.value = parseInt((100 / props.room.track_duration) * (audio.currentTime + 0.25))
  })

  audio.addEventListener('ended', () => {
    isPlaying.value = false
    loading.value = true
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
  audio.pause()
  emit('track:stopped', props.track)
}
</script>
<template>
  <div id="player" class="relative flex h-4 w-full items-center rounded-t-lg bg-purple-200">
    <transition-group name="list" tag="ul" v-if="usersWithAllAnswers">
      <li v-for="user in usersWithAllAnswers" :key="user.id" class="absolute -top-8 rounded bg-teal-600 p-1 text-xs text-white" :style="'left:calc(' + (100 / props.room.track_duration) * user.time + '% - 0.25rem)'">
        {{ user.name }}
        <div class="absolute left-1 top-full mt-1 h-full h-0 w-full w-0 translate-y-[-50%] border-t-[8px] border-l-[8px] border-r-[8px] border-t-transparent border-l-transparent border-r-transparent border-t-teal-600"></div>
      </li>
    </transition-group>
    <div v-if="error" class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg text-red-500">
      {{ error }}
    </div>
    <div v-else-if="loading" class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500">
      {{ __('Loading') }}
    </div>
    <div v-else class="shine h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" :class="audio.currentTime < room.track_duration * 0.15 ? 'from-orange-300 to-orange-600' : 'from-purple-300 to-purple-400'"/>
  </div>
</template>
