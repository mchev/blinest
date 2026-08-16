<script setup>
import { ref, onMounted, computed, onBeforeUnmount, watch } from 'vue'
import axios from 'axios'
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
  },
  initialTrack: {
    type: Object,
    default: null
  },
  initialStartTime: {
    type: Number,
    default: 0
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
const pendingStartTime = ref(0) // Store startTime to apply after audio loads
const hasHandledInitialTrack = ref(false) // Flag to prevent double playback
const userGestureModal = ref(null) // Reference to UserGestureModal
let playerChannel = null // Store channel reference to prevent multiple listeners

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
    // Preserve the startTime if we're joining mid-track
    const preservedTime = pendingStartTime.value > 0 ? pendingStartTime.value : 0
    await audio.value.play()
    audio.value.pause()
    // Only reset to 0 if we don't have a startTime to preserve
    audio.value.currentTime = preservedTime
    if (preservedTime > 0) {
      currentTime.value = preservedTime
      const calculatedPercent = (100 / props.room.track_duration) * (preservedTime + 0.25)
      percent.value = Math.min(100, Math.round(calculatedPercent))
    }
    // Now that we have user interaction, try to play again
    if (isPlaying.value && track.value) {
      await audio.value.play()
    }
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

const initYoutubePlayer = (videoId, startTime = 0) => {
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
        // Set start time if provided
        if (startTime > 0) {
          youtubePlayer.value.seekTo(startTime, true)
          currentTime.value = startTime
          // Update percent based on startTime
          const calculatedPercent = (100 / props.room.track_duration) * (startTime + 0.25)
          percent.value = Math.min(100, Math.round(calculatedPercent))
        }
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
        const errorCode = event.data
        const videoUrl = track.value?.preview_url
        const trackProvider = track.value?.provider

        error.value = `YouTube Error (${errorCode}): ${errorMessages[errorCode] || 'Unknown error'}
          Details:
          - Video ID: ${videoId}
          - Video URL: ${videoUrl}
          - Provider: ${trackProvider}
          - Browser: ${navigator.userAgent}
          ${errorCode === 5 ? `
          Possible causes:
          - Browser doesn't support HTML5 video
          - YouTube player failed to initialize
          - Network connectivity issues
          ` : ''}`
        loading.value = false
        isPlaying.value = false
      }
    }
  })
}

const play = async (startTime = 0) => {
  if (isPlaying.value) await stop()

  // Clear waitingForNextTrack when we start playing
  waitingForNextTrack.value = false
  
  loading.value = true
  error.value = null
  isPlaying.value = true
  pendingStartTime.value = startTime
  currentTime.value = startTime
  
  // Initialize percent based on startTime if joining mid-track
  if (startTime > 0) {
    const calculatedPercent = (100 / props.room.track_duration) * (startTime + 0.25)
    percent.value = Math.min(100, Math.round(calculatedPercent))
  } else {
    percent.value = 0
  }

  if (isYoutubeTrack.value) {
    const videoId = getYoutubeVideoId(track.value?.preview_url)
    if (!videoId) {
      error.value = 'Invalid YouTube URL'
      loading.value = false
      isPlaying.value = false
      return
    }

    if (window.YT?.Player) {
      initYoutubePlayer(videoId, startTime)
    } else {
      window.onYouTubeIframeAPIReady = () => initYoutubePlayer(videoId, startTime)
    }
    return
  }

  try {
    // Set up audio source
    audio.value.src = track.value.audio
    audio.value.crossOrigin = 'anonymous'
    audio.value.muted = false
    
    // Add event listeners before loading
    addAudioEventListeners()
    
    // Load the audio - handleCanPlayThrough will apply the startTime
    audio.value.load()
    
    // If we have a startTime, also try to set it early (but handleCanPlayThrough is the main handler)
    if (startTime > 0) {
      // Try to set it as soon as metadata is available
      const trySetStartTime = () => {
        if (audio.value.readyState >= 1) { // HAVE_METADATA
          try {
            audio.value.currentTime = startTime
            currentTime.value = startTime
          } catch (e) {
            // Ignore - handleCanPlayThrough will handle it
          }
        }
      }
      audio.value.addEventListener('loadedmetadata', trySetStartTime, { once: true })
    }
  } catch (e) {
    error.value = `Error loading audio: ${e.message}`
    loading.value = false
    isPlaying.value = false
  }
}

const setupEventListeners = () => {
  window.addEventListener('volume-localstorage-changed', handleVolumeChange)

  // Clean up existing listeners to prevent duplicates
  if (playerChannel) {
    playerChannel.stopListening('TrackPlayed')
    playerChannel.stopListening('TrackEnded')
    playerChannel.stopListening('TrackPaused')
    playerChannel.stopListening('TrackResumed')
    playerChannel.stopListening('UserHasFoundAllTheAnswers')
  }

  // Presence channel (same as Show.vue) so we receive TrackPlayed / TrackEnded
  playerChannel = Echo.join(props.channel)
  playerChannel
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
  
  // Clean up Echo listeners only (do not Echo.leave - Show.vue owns the presence subscription)
  if (playerChannel) {
    playerChannel.stopListening('TrackPlayed')
    playerChannel.stopListening('TrackEnded')
    playerChannel.stopListening('TrackPaused')
    playerChannel.stopListening('TrackResumed')
    playerChannel.stopListening('UserHasFoundAllTheAnswers')
    playerChannel = null
  }
  
  window.removeEventListener('volume-localstorage-changed', handleVolumeChange)
  removeAudioEventListeners()
  clearInterval(countdownInterval.value)
  clearInterval(progressInterval.value)
}

const handleVolumeChange = (event) => {
  volume.value = event.detail.volume
}

const handleTrackPlayed = (e) => {
  // Prevent playing the same track multiple times
  if (track.value && track.value.id === e.track.id && isPlaying.value) {
    return // Already playing this track
  }
  track.value = e.track
  waitingForNextTrack.value = false
  
  // Enregistrer que le joueur a écouté cette track (même sans trouver de réponse)
  if (e.round && e.round.id && e.track && e.track.id) {
    axios.post(`/rounds/${e.round.id}/tracks/${e.track.id}/listened`).catch(() => {
      // Ignorer les erreurs silencieusement (peut arriver si le round est terminé)
    })
  }
  
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
    loadedmetadata: handleCanPlayThrough,
    loadeddata: handleCanPlayThrough,
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
    loadedmetadata: handleCanPlayThrough,
    loadeddata: handleCanPlayThrough,
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

  const errorCode = audio.value.error.code
  const errorMessage = audio.value.error.message
  const trackUrl = track.value?.audio
  const trackProvider = track.value?.provider

  // If we get an invalid audio URL error (code 4), try to reload once
  if (errorCode === 4 && !audio.value.dataset.retried) {
    audio.value.dataset.retried = 'true'
    audio.value.load()
    return
  }

  error.value = `Audio Error (${errorCode}): ${errorMessages[errorCode] || errorMessage}
    ${errorCode === 4 ? `
    Possible causes:
    - Unsupported audio format
    - Invalid audio URL: ${trackUrl}
    - Provider: ${trackProvider}
    - Browser: ${navigator.userAgent}
    ` : ''}`
    
  isPlaying.value = false
  loading.value = false
}

const applyStartTime = () => {
  if (pendingStartTime.value > 0 && audio.value.readyState >= 2) {
    try {
      const timeDiff = Math.abs(audio.value.currentTime - pendingStartTime.value)
      if (timeDiff > 0.5) {
        audio.value.currentTime = pendingStartTime.value
        currentTime.value = pendingStartTime.value
        // Update percent based on current time
        const calculatedPercent = (100 / props.room.track_duration) * (pendingStartTime.value + 0.25)
        percent.value = Math.min(100, Math.round(calculatedPercent))
        return true
      }
    } catch (e) {
      console.warn('Could not set currentTime:', e)
    }
  }
  return false
}

const handleCanPlayThrough = async () => {
  if (waitingForNextTrack.value) return

  const targetStartTime = pendingStartTime.value

  // Apply the start time BEFORE playing - this is critical
  if (targetStartTime > 0 && audio.value.readyState >= 2) {
    try {
      audio.value.currentTime = targetStartTime
      currentTime.value = targetStartTime
      const calculatedPercent = (100 / props.room.track_duration) * (targetStartTime + 0.25)
      percent.value = Math.min(100, Math.round(calculatedPercent))
    } catch (e) {
      console.warn('Could not set currentTime before play:', e)
    }
  }

  loading.value = false
  
  // For iOS, we need to pause and set time before playing
  if (isIOS.value && targetStartTime > 0) {
    audio.value.pause()
    try {
      audio.value.currentTime = targetStartTime
      currentTime.value = targetStartTime
    } catch (e) {
      console.warn('Player::handleCanPlayThrough - Could not set currentTime on iOS:', e)
    }
  }
  
  try {
    await audio.value.play()
    
    // CRITICAL: Verify and re-apply startTime AFTER play() - browsers often reset it
    if (targetStartTime > 0) {
      // Wait a tiny bit for the browser to settle, then check and fix
      setTimeout(() => {
        const actualTime = audio.value.currentTime
        const timeDiff = Math.abs(actualTime - targetStartTime)
        
        if (timeDiff > 0.2) { // If more than 200ms off, fix it
          try {
            audio.value.currentTime = targetStartTime
            currentTime.value = targetStartTime
            const calculatedPercent = (100 / props.room.track_duration) * (targetStartTime + 0.25)
            percent.value = Math.min(100, Math.round(calculatedPercent))
          } catch (e) {
            console.warn('Could not fix startTime:', e)
          }
        }
        
        // Clear pendingStartTime after we've applied it
        pendingStartTime.value = 0
      }, 50) // Small delay to let browser settle
    }
  } catch (error) {
    // If NotAllowedError, show user gesture modal
    if (error.name === 'NotAllowedError' && userGestureModal.value) {
      userGestureModal.value.showModal()
    }
    // If play failed, try to apply startTime when it succeeds
    if (targetStartTime > 0) {
      audio.value.addEventListener('play', () => {
        try {
          audio.value.currentTime = targetStartTime
          currentTime.value = targetStartTime
          pendingStartTime.value = 0
        } catch (e) {
          console.warn('Could not apply startTime on play event:', e)
        }
      }, { once: true })
    }
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

const markerPositionStyle = (user) => {
  const percent = Math.min(
    94,
    Math.max(6, (100 / props.room.track_duration) * (user.time ?? 0)),
  )

  return {
    left: `${percent}%`,
  }
}

// Watch for initialTrack to be set (in case it's set after mount)
watch(() => [props.initialTrack, props.initialStartTime], (newValue, oldValue) => {
  // Safely destructure newValue and oldValue
  const [newTrack, newStartTime] = newValue || [null, null]
  const [oldTrack, oldStartTime] = oldValue || [null, null]
  
  // If initialTrack was null and is now set (joining mid-track after mount)
  // This is the key case: we mounted without a track, now we have one
  if (!oldTrack && newTrack && hasHandledInitialTrack.value) {
    track.value = newTrack
    const startTime = newStartTime || 0
    waitingForNextTrack.value = false
    loading.value = false
    play(startTime)
    return
  }
  
  // Play if we have a new track and:
  // 1. We don't have a track yet, OR
  // 2. The track ID has changed (new track)
  const shouldPlay = newTrack && (
    !track.value || 
    (track.value && track.value.id !== newTrack.id)
  )
  
  if (shouldPlay) {
    track.value = newTrack
    const startTime = newStartTime || 0
    waitingForNextTrack.value = false
    loading.value = false
    play(startTime)
  } else if (newTrack && track.value && track.value.id === newTrack.id && newStartTime !== oldStartTime && newStartTime !== currentTime.value) {
    // If it's the same track but the start time changed (e.g., a re-sync)
    play(newStartTime) // Re-play from the new start time
  }
}, { immediate: true, deep: true }) // immediate: true to catch initial values

onMounted(() => {
  initializeAudio()
  setupEventListeners()
  
  // If we have an initial track at mount time, play it immediately
  if (props.initialTrack) {
    track.value = props.initialTrack
    const startTime = props.initialStartTime || 0
    hasHandledInitialTrack.value = true
    waitingForNextTrack.value = false
    loading.value = false
    play(startTime)
  } else {
    // If no initial track at mount, mark as handled and wait for watcher to catch it
    hasHandledInitialTrack.value = true
    waitingForNextTrack.value = true
    // The watcher with immediate: true will catch it if it's set synchronously
    // Otherwise it will catch it when joining() sets it
  }
})

onBeforeUnmount(() => {
  cleanup()
  cleanupYoutubePlayer()
})
</script>

<template>
  <div id="youtube-player" class="hidden"></div>

  <div class="room-player-shell room-player-shell--mobile">
    <TransitionGroup
      name="user-answer"
      tag="ul"
      class="room-player__markers"
      :class="{ 'room-player__markers--empty': usersWithAllAnswers.length === 0 }"
    >
      <li
        v-for="user in usersWithAllAnswers"
        :key="user.id"
        class="room-player__marker"
        :style="markerPositionStyle(user)"
        :title="user.name"
      >
        <img v-if="user.photo" :src="user.photo" :alt="user.name" class="room-player__marker-avatar" />
        <span class="room-player__marker-name">{{ user.name }}</span>
        <div class="room-player__marker-tail" aria-hidden="true"></div>
      </li>
    </TransitionGroup>

    <div id="player" class="room-player">
      <div class="room-player__track overflow-hidden">
      <!-- Error state -->
      <template v-if="error">
        <div class="flex flex-col h-auto w-full p-3 bg-brand-primary/10 text-white/90">
          <div class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-brand-primary" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-brand-primary-light">{{ error.split('\n')[0] }}</span>
          </div>
          <div class="text-sm whitespace-pre-line pl-7 text-white/70">
            {{ error.split('\n').slice(1).join('\n') }}
          </div>
        </div>
      </template>

      <!-- Loading state -->
      <template v-else-if="loading && !countdowning">
        <div class="flex h-6 w-full items-center justify-center bg-brand-midnight/60">
          <div class="flex items-center space-x-2">
            <div class="h-4 w-4 animate-spin rounded-full border-2 border-brand-accent border-t-transparent"></div>
            <span class="text-sm font-medium text-white/80">{{ __('Loading') }}</span>
          </div>
        </div>
      </template>

      <!-- Countdown state -->
      <template v-else-if="countdowning && countdown !== -1">
        <div class="flex max-w-full flex-grow flex-col">
          <div class="relative h-6 w-full overflow-hidden bg-brand-midnight">
            <div
              class="flex h-6 items-center justify-center bg-brand-accent/80 text-white transition-all duration-1000 ease-linear"
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
        <div class="relative h-6 w-full">
          <!-- Red zone indicator (first 18%) -->
          <div
            class="absolute top-0 left-0 z-10 h-6 bg-brand-primary/80 transition-all duration-500 ease-linear"
            :style="`width: ${Math.min(percent, 18)}%`"
          />

          <!-- Progress bar -->
          <div
            class="absolute top-0 left-0 h-6 bg-brand-accent/90 transition-all duration-500 ease-linear"
            :style="`width: ${percent}%`"
          >
            <div class="absolute inset-0 opacity-10">
              <div class="shine-wave"></div>
            </div>
          </div>
        </div>
      </template>
      </div>
    </div>
  </div>

  <UserGestureModal ref="userGestureModal" @play="triggerUserGesture" />
</template>

<style scoped>
.user-answer-move {
  transition: transform 0.35s ease;
}

.user-answer-enter-active,
.user-answer-leave-active {
  transition: opacity 0.35s ease;
}

.user-answer-enter-from,
.user-answer-leave-to {
  opacity: 0;
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
    rgba(255, 255, 255, 0.1) 50%,
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