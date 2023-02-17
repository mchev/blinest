<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import UserGestureModal from '@/Components/UserGestureModal.vue'

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
const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream
const countdown = ref(0)
const countdowning = ref(false)

const triggerUserGesture = () => {
  console.log('User Gesture')
  audio.play()
}

onMounted(() => {
  audio.muted = true
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
      startCountdown()
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
  audio.load() // IOS Hack - Important
  audio.muted = false

  audio.addEventListener('error', () => {
    error.value = audio.error.message
    isPlaying.value = false
  })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
    if (isIOS) {
      console.log('iOS Player')
      audio.pause() // IOS Hack
      audio.currentTime = 0 // IOS Hack
      audio.volume = localStorage.getItem('volume')
      audio.play()
    } else {
      let playPromise = audio.play()
    }
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

const startCountdown = () => {
  countdown.value = parseInt(props.room.pause_between_tracks)
  countdowning.value = true
  let interval = setInterval(() => {
    if (countdown.value === -1) {
      clearInterval(interval)
      countdowning.value = false
    } else {
      countdown.value--
    }
  }, 1000)
}
</script>
<template>
  <div id="player" class="relative flex h-4 w-full items-center rounded-t-lg bg-purple-200">
    <transition-group name="list" tag="ul" v-if="usersWithAllAnswers.length">
      <li v-for="user in usersWithAllAnswers" :key="user.id" class="absolute -top-8 z-20 rounded bg-teal-600 p-1 text-xs text-white" :style="'left:calc(' + (100 / props.room.track_duration) * user.time + '% - 0.25rem)'">
        {{ user.name }}
        <div class="absolute left-1 top-full mt-1 h-full h-0 w-full w-0 translate-y-[-50%] border-t-[8px] border-l-[8px] border-r-[8px] border-t-transparent border-l-transparent border-r-transparent border-t-teal-600"></div>
      </li>
    </transition-group>
    <div v-if="error" class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg text-red-500">
      {{ error }}
    </div>
    <div v-else-if="loading && !countdowning" class="flex h-4 w-full max-w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500">
      {{ __('Loading') }}
    </div>
    <div v-else-if="countdowning && countdown != -1" class="flex max-w-full flex-grow flex-col">
      <div class="relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200">
        <div class="flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear" :style="'width:' + (countdown / parseInt(props.room.pause_between_tracks)) * 100 + '%'">
          <span class="absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600">{{ __('Next track in') }} {{ countdown }}</span>
        </div>
      </div>
    </div>
    <div v-else class="w-full max-w-full">
      <div class="absolute top-0 left-0 z-10 h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-red-600 to-transparent transition-all duration-500 ease-linear" :style="'width:' + percent + '%; max-width: 18%'" />
      <div class="shine absolute h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-500 ease-linear" :style="'width:' + percent + '%'" />
    </div>
  </div>
  <UserGestureModal @play="triggerUserGesture" />
</template>
