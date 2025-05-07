<script setup>
import { ref, onMounted, computed, onBeforeUnmount, watch } from 'vue'
import UserGestureModal from '@/Components/UserGestureModal.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
    validator: (room) => room.track_duration && room.pause_between_tracks
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
const currentTime = ref(0)

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
  localStorage.setItem('volume', newVolume.toString())
})

const triggerUserGesture = async () => {
  try {
    await audio.value.play()
    audio.value.pause()
    audio.value.currentTime = 0
  } catch (error) {
    console.error('Error during user gesture:', error)
  }
}

const initializeAudio = () => {
  audio.value.muted = true
  audio.value.volume = volume.value
  
  if (!window.YT && !windowYTScriptLoaded.value) {
    loadYouTubeAPI()
  }
}

const loadYouTubeAPI = () => {
  windowYTScriptLoaded.value = true
  const tag = document.createElement('script')
  tag.src = 'https://www.youtube.com/iframe_api'
  const firstScriptTag = document.getElementsByTagName('script')[0]
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
}

const getYoutubeVideoId = (url) => {
  if (!url) return null
  try {
    if (url.includes('youtube.com')) {
      const urlParams = new URLSearchParams(new URL(url).search)
      return urlParams.get('v')
    } else if (url.includes('youtu.be')) {
      return new URL(url).pathname.substring(1)
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
        } else if (event.data === YT.PlayerState.PLAYING) {
          isPlaying.value = true
        } else if (event.data === YT.PlayerState.PAUSED) {
          isPlaying.value = false
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
  currentTime.value = 0

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
  loading.value = false
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
  const time = isYoutubeTrack.value && youtubePlayer.value
    ? youtubePlayer.value.getCurrentTime()
    : audio.value.currentTime

  currentTime.value = time
  emit('track:currentTime', time)
  
  const calculatedPercent = (100 / props.room.track_duration) * (time + 0.25)
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
  isPlaying.value = false
  emit('track:paused', track.value)
}

const resume = async () => {
  if (isYoutubeTrack.value) {
    youtubePlayer.value?.playVideo()
  } else {
    try {
      await audio.value.play()
      isPlaying.value = true
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
  
  <div id="player" class="relative mb-1 rounded-lg bg-neutral-800 shadow-lg border border-neutral-700">
    <!-- User answers markers - moved outside the player container to be visible -->
    <TransitionGroup 
      name="user-answer"
      tag="ul"
      class="absolute w-full"
      style="top: -2rem;"
    >
      <li 
        v-for="user in usersWithAllAnswers" 
        :key="user.id" 
        class="absolute z-20 rounded-md bg-teal-600 px-2 py-1 text-xs font-medium text-white shadow-lg hover:z-30 transform transition-transform duration-200 hover:scale-110"
        :style="`left: calc(${(100 / props.room.track_duration) * user.time}% - 1rem);`"
      >
        <div class="flex items-center space-x-1">
          <img :src="user.photo" class="h-4 w-4 rounded-full" v-if="user.photo" />
          <span class="whitespace-nowrap select-none max-w-16 truncate">{{ user.name }}</span>
        </div>
        <div class="absolute left-1/2 top-full h-0 w-0 -translate-x-1/2 border-t-[6px] border-l-[6px] border-r-[6px] border-t-teal-600 border-l-transparent border-r-transparent"></div>
      </li>
    </TransitionGroup>

    <div class="overflow-hidden rounded-lg">
      <!-- Error state -->
      <template v-if="error">
        <div class="flex h-10 w-full items-center justify-center rounded-lg bg-red-900/30 text-red-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          {{ error }}
        </div>
      </template>

      <!-- Loading state -->
      <template v-else-if="loading && !countdowning">
        <div class="flex h-10 w-full items-center justify-center rounded-lg bg-purple-900/30">
          <div class="flex items-center space-x-2">
            <div class="h-4 w-4 animate-spin rounded-full border-2 border-purple-500 border-t-transparent"></div>
            <span class="text-sm font-medium text-purple-400">{{ __('Loading') }}</span>
          </div>
        </div>
      </template>

      <!-- Countdown state -->
      <template v-else-if="countdowning && countdown !== -1">
        <div class="flex max-w-full flex-grow flex-col">
          <div class="relative h-10 w-full overflow-hidden rounded-lg bg-neutral-800">
            <div 
              class="flex h-10 items-center justify-center rounded-lg bg-gradient-to-r from-purple-800 to-purple-600 text-white transition-all duration-1000 ease-linear"
              :style="`width: ${(countdown / parseInt(props.room.pause_between_tracks)) * 100}%`"
            >
            </div>
            <span class="absolute inset-0 flex items-center justify-center text-sm font-medium text-white">
              {{ __('Next track in') }} {{ countdown }}s
            </span>
          </div>
        </div>
      </template>

      <!-- Playing state -->
      <template v-else>
        <div class="relative h-10 w-full">
          <!-- Red zone indicator (first 18%) -->
          <div 
            class="absolute top-0 left-0 z-10 h-10 rounded-r-lg bg-gradient-to-r from-red-700 to-red-600/30 transition-all duration-500 ease-linear" 
            :style="`width: ${Math.min(percent, 18)}%`" 
          />
          
          <!-- Progress bar -->
          <div 
            class="absolute top-0 left-0 h-10 bg-gradient-to-r from-purple-700 to-purple-500 transition-all duration-500 ease-linear" 
            :style="`width: ${percent}%`" 
          >
            <div class="absolute inset-0 opacity-20">
              <div class="shine-wave"></div>
            </div>
          </div>
          
          <!-- Progress indicator -->
          <div 
            class="absolute top-0 h-10 w-1 bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)] transition-all duration-500 ease-linear" 
            :style="`left: ${percent}%`" 
          ></div>
          
          <!-- Time indicator -->
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-sm font-medium text-white">
              {{ Math.floor(currentTime / 60) }}:{{ String(Math.floor(currentTime % 60)).padStart(2, '0') }} / 
              {{ Math.floor(props.room.track_duration / 60) }}:{{ String(Math.floor(props.room.track_duration % 60)).padStart(2, '0') }}
            </span>
          </div>
        </div>
      </template>
    </div>
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

.shine-wave {
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 0.2) 50%,
    rgba(255, 255, 255, 0) 100%
  );
  animation: shine 2s infinite;
}

@keyframes shine {
  0% {
    left: -100%;
  }
  100% {
    left: 200%;
  }
}
</style>