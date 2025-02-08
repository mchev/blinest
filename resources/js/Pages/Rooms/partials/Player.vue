<script setup>
import { ref, onMounted, onUnmounted, computed, onBeforeUnmount, watch } from 'vue'
import UserGestureModal from '@/Components/UserGestureModal.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
    validator: (room) => {
      return room.track_duration && room.pause_between_tracks
    }
  },
  channel: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['track:played', 'track:ended', 'track:paused', 'track:stopped', 'track:currentTime'])

// Core player state
const audio = ref(new Audio())
const track = ref(null)
const loading = ref(true)
const isPlaying = ref(false)
const error = ref(null)
const percent = ref(0)
const usersWithAllAnswers = ref([])
const countdown = ref(0)
const countdowning = ref(false)
const waitingForNextTrack = ref(false)

// YouTube specific state
const youtubePlayer = ref(null)
const isYoutubeTrack = computed(() => track.value?.provider === 'youtube')
const windowYTScriptLoaded = ref(false)

// Device detection
const isIOS = computed(() => /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)

// Intervals/timers refs for cleanup
const countdownInterval = ref(null)
const progressInterval = ref(null)

// Volume handling
const volume = ref(parseFloat(localStorage.getItem('volume') || '1'))
watch(volume, (newVolume) => {
  audio.value.volume = newVolume
  if (youtubePlayer.value) {
    youtubePlayer.value.setVolume(newVolume * 100)
  }
})

const triggerUserGesture = async () => {
  try {
    await audio.value.play()
  } catch (error) {
    console.error('Error during user gesture:', error)
  }
}

const initializeAudio = () => {
  audio.value.muted = true
  audio.value.volume = volume.value
  
  if (!window.YT && !windowYTScriptLoaded.value) {
    windowYTScriptLoaded.value = true
    const tag = document.createElement('script')
    tag.src = 'https://www.youtube.com/iframe_api'
    const firstScriptTag = document.getElementsByTagName('script')[0]
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
  }
}

const getYoutubeVideoId = (url) => {
  if (!url) return null
  try {
    if (url.includes('youtube.com')) {
      const urlParams = new URLSearchParams(new URL(url).search)
      return urlParams.get('v')
    }
    return url // Assume direct video ID
  } catch {
    return null
  }
}

const cleanupYoutubePlayer = () => {
  if (youtubePlayer.value) {
    youtubePlayer.value.destroy()
    youtubePlayer.value = null
  }
}

const initYoutubePlayer = (videoId) => {
  cleanupYoutubePlayer()
  
  const player = new YT.Player('youtube-player', {
    height: '0',
    width: '0',
    videoId,
    playerVars: {
      autoplay: 1,
      controls: 0,
      disablekb: 1,
      origin: window.location.origin
    },
    events: {
      onReady: (event) => {
        youtubePlayer.value = event.target
        youtubePlayer.value.setVolume(volume.value * 100)
        loading.value = false
        startYoutubeProgress()
      },
      onStateChange: (event) => {
        if (event.data === YT.PlayerState.ENDED) {
          handleAudioEnded()
        }
      },
      onError: (event) => {
        const errorMessages = {
          2: 'Invalid YouTube video ID',
          5: 'HTML5 player error',
          100: 'Video not found',
          101: 'Video playback not allowed',
          150: 'Video playback not allowed'
        }
        error.value = `YouTube Error: ${errorMessages[event.data] || 'Unknown error'}`
        loading.value = false
        isPlaying.value = false
      }
    }
  })
}

const play = async () => {
  if (isPlaying.value) await stop()

  loading.value = true
  error.value = null
  isPlaying.value = true

  if (isYoutubeTrack.value) {
    const videoId = getYoutubeVideoId(track.value?.preview_url)
    if (!videoId) {
      error.value = 'Invalid YouTube URL'
      loading.value = false
      isPlaying.value = false
      return
    }

    if (window.YT?.Player) {
      initYoutubePlayer(videoId)
    } else {
      window.onYouTubeIframeAPIReady = () => initYoutubePlayer(videoId)
    }
    return
  }

  try {
    audio.value.src = track.value.audio
    audio.value.crossOrigin = 'anonymous'
    audio.value.load()
    audio.value.muted = false
    addAudioEventListeners()
  } catch (e) {
    error.value = `Error loading audio: ${e.message}`
    loading.value = false
    isPlaying.value = false
  }
}

const setupEventListeners = () => {
  window.addEventListener('volume-localstorage-changed', handleVolumeChange)

  const channel = Echo.channel(props.channel)
  channel
    .listen('TrackPlayed', handleTrackPlayed)
    .listen('TrackEnded', handleTrackEnded)
    .listen('TrackPaused', pause)
    .listen('TrackResumed', resume)
    .listen('UserHasFoundAllTheAnswers', handleUserFoundAllAnswers)
    .error((error) => {
      console.error('Echo channel error:', error)
    })
}

const cleanup = () => {
  stop()
  Echo.leave(props.channel)
  window.removeEventListener('volume-localstorage-changed', handleVolumeChange)
  removeAudioEventListeners()
  clearInterval(countdownInterval.value)
  clearInterval(progressInterval.value)
}

const handleVolumeChange = (event) => {
  volume.value = event.detail.volume
}

const handleTrackPlayed = (e) => {
  track.value = e.track
  waitingForNextTrack.value = false
  play()
}

const handleTrackEnded = () => {
  usersWithAllAnswers.value = []
  stop()
  waitingForNextTrack.value = true
  startCountdown()
}

const handleUserFoundAllAnswers = (e) => {
  if (!usersWithAllAnswers.value.some(user => user.id === e.user.id)) {
    usersWithAllAnswers.value.push(e.user)
  }
}

const addAudioEventListeners = () => {
  if (isYoutubeTrack.value) return

  const events = {
    error: handleAudioError,
    canplaythrough: handleCanPlayThrough,
    timeupdate: handleTimeUpdate,
    ended: handleAudioEnded
  }

  Object.entries(events).forEach(([event, handler]) => {
    audio.value.addEventListener(event, handler)
  })
}

const removeAudioEventListeners = () => {
  const events = {
    error: handleAudioError,
    canplaythrough: handleCanPlayThrough,
    timeupdate: handleTimeUpdate,
    ended: handleAudioEnded
  }

  Object.entries(events).forEach(([event, handler]) => {
    audio.value.removeEventListener(event, handler)
  })
}

const handleAudioError = () => {
  const errorMessages = {
    1: 'Fetching process aborted by user',
    2: 'Error occurred while downloading',
    3: 'Error occurred while decoding',
    4: 'Audio not supported'
  }

  error.value = audio.value.error.code === 13
    ? `Media playback error. Please check your audio output device. (${audio.value.error.message})`
    : errorMessages[audio.value.error.code] || audio.value.error.message
    
  isPlaying.value = false
}

const handleCanPlayThrough = async () => {
  if (waitingForNextTrack.value) return

  loading.value = false
  
  if (isIOS.value) {
    audio.value.pause()
    audio.value.currentTime = 0
  }
  
  try {
    await audio.value.play()
  } catch (error) {
    console.error('Error playing audio:', error)
  }
}

const handleTimeUpdate = () => {
  const currentTime = isYoutubeTrack.value && youtubePlayer.value
    ? youtubePlayer.value.getCurrentTime()
    : audio.value.currentTime

  emit('track:currentTime', currentTime)
  
  const calculatedPercent = (100 / props.room.track_duration) * (currentTime + 0.25)
  percent.value = Math.min(100, Math.round(calculatedPercent))
}

const handleAudioEnded = () => {
  if (!waitingForNextTrack.value) {
    isPlaying.value = false
    loading.value = true
    emit('track:ended', track.value)
  }
}

const pause = () => {
  if (isYoutubeTrack.value) {
    youtubePlayer.value?.pauseVideo()
  } else {
    audio.value.pause()
  }
  emit('track:paused', track.value)
}

const resume = async () => {
  if (isYoutubeTrack.value) {
    youtubePlayer.value?.playVideo()
  } else {
    try {
      await audio.value.play()
    } catch (error) {
      console.error('Error resuming audio:', error)
    }
  }
}

const stop = async () => {
  isPlaying.value = false
  
  if (isYoutubeTrack.value) {
    cleanupYoutubePlayer()
  } else {
    audio.value.pause()
    audio.value.currentTime = 0
    removeAudioEventListeners()
  }
  
  waitingForNextTrack.value = true
  emit('track:stopped', track.value)
}

const startCountdown = () => {
  countdown.value = parseInt(props.room.pause_between_tracks)
  countdowning.value = true
  
  clearInterval(countdownInterval.value)
  
  countdownInterval.value = setInterval(() => {
    if (countdown.value <= 0) {
      clearInterval(countdownInterval.value)
      countdowning.value = false
    } else {
      countdown.value--
    }
  }, 1000)
}

const startYoutubeProgress = () => {
  clearInterval(progressInterval.value)
  
  progressInterval.value = setInterval(() => {
    if (isYoutubeTrack.value && youtubePlayer.value && isPlaying.value) {
      handleTimeUpdate()
    } else {
      clearInterval(progressInterval.value)
    }
  }, 100)
}

onMounted(() => {
  initializeAudio()
  setupEventListeners()
})

onBeforeUnmount(() => {
  cleanup()
  cleanupYoutubePlayer()
})
</script>

<template>
  <div id="youtube-player" class="hidden"></div>
  <div id="player" class="relative flex h-4 w-full items-center rounded-t-lg bg-purple-200">
    <TransitionGroup 
      name="user-answer"
      tag="ul"
      class="absolute w-full"
    >
      <li 
        v-for="user in usersWithAllAnswers" 
        :key="user.id" 
        class="absolute z-20 rounded-full bg-teal-600 p-2 text-xs text-white shadow-lg hover:z-30 -top-10"
        :style="`left: calc(${(100 / props.room.track_duration) * user.time}% - 1rem);`"
      >
        <span class="whitespace-nowrap select-none max-w-16 truncate">{{ user.name }}</span>
        <div class="absolute left-1/2 top-full h-0 w-0 -translate-x-1/2 border-t-[8px] border-l-[8px] border-r-[8px] border-t-teal-600 border-l-transparent border-r-transparent"></div>
      </li>
    </TransitionGroup>

    <template v-if="error">
      <div class="flex h-4 w-full animate-pulse items-center justify-center rounded-t-lg text-red-500">
        {{ error }}
      </div>
    </template>

    <template v-else-if="loading && !countdowning">
      <div class="flex h-4 w-full max-w-full animate-pulse items-center justify-center rounded-t-lg bg-purple-500">
        {{ __('Loading') }}
      </div>
    </template>

    <template v-else-if="countdowning && countdown !== -1">
      <div class="flex max-w-full flex-grow flex-col">
        <div class="relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200">
          <div 
            class="flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear"
            :style="`width: ${(countdown / parseInt(props.room.pause_between_tracks)) * 100}%`"
          >
            <span class="absolute inset-0 flex items-center justify-center text-sm text-neutral-600">
              {{ __('Next track in') }} {{ countdown }}
            </span>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <div class="w-full max-w-full">
        <div 
          class="absolute top-0 left-0 z-10 h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-red-600 to-transparent transition-all duration-500 ease-linear" 
          :style="`width: ${percent}%; max-width: 18%`" 
        />
        <div 
          class="shine absolute h-4 rounded-r-lg rounded-tl-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-500 ease-linear" 
          :style="`width: ${percent}%`" 
        />
      </div>
    </template>
  </div>

  <UserGestureModal @play="triggerUserGesture" />
</template>

<style scoped>
.user-answer-move,
.user-answer-enter-active,
.user-answer-leave-active {
  transition: all 0.5s ease;
}

.user-answer-enter-from,
.user-answer-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

.user-answer-leave-active {
  position: absolute;
}
</style>