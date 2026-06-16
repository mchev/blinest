<script setup>
import { ref, onMounted, computed, onBeforeUnmount, watch } from 'vue'
import axios from 'axios'
import UserGestureModal from '@/Components/UserGestureModal.vue'
import PlayerTimeline from '@/Pages/Rooms/partials/PlayerTimeline.vue'
import {
  getElapsedSeconds,
  getInterTrackCountdown,
  getServerNowMs,
  measureServerTimeOffset,
  msUntilDeadline,
} from '@/composables/useTrackTiming'

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
  },
  initialTrackTiming: {
    type: Object,
    default: null
  },
  initialInterTrackPause: {
    type: Object,
    default: null
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
const barProgress = ref(0)
const audioLevels = ref(null)
const bassLevel = ref(0)
const remainingSecondsLive = ref(0)
const usersWithAllAnswers = ref([])
const countdown = ref(0)
const countdownProgress = ref(0)
const countdowning = ref(false)
const interTrackPauseSeconds = ref(parseInt(props.room.pause_between_tracks, 10) || 0)
const waitingForNextTrack = ref(false)
const currentTime = ref(0)
const pendingStartTime = ref(0) // Store startTime to apply after audio loads
const playbackBootstrapped = ref(false)
const userGestureModal = ref(null) // Reference to UserGestureModal
const trackTiming = ref(props.initialTrackTiming)
const activeInterTrackPause = ref(null)
const interTrackHolding = ref(false)
const pendingNextTrack = ref(null)
let playerChannel = null // Store channel reference to prevent multiple listeners
let trackEndTimer = null
let visualLoopId = null
let fallbackCountdownEndAt = null
let nextTrackPreloader = null
let lastServerSyncAt = 0

// Audio analysis (real-time waveform levels)
let audioContext = null
let analyser = null
let analyserSource = null
let analyserData = null
let bucketRanges = null
let audioPlaybackStarting = false
let activePlaybackTrackId = null
let lastAppliedTrackKey = null
let playbackGeneration = 0

// YouTube specific state
const youtubePlayer = ref(null)
const isYoutubeTrack = computed(() => track.value?.provider === 'youtube')
const windowYTScriptLoaded = ref(false)

// Device detection
const isIOS = computed(() => /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)

const trackDurationSeconds = computed(() => (
  trackTiming.value?.track_duration ?? props.room.track_duration
))

const usesServerClock = computed(() => Boolean(trackTiming.value?.current_track_started_at))

const speedBonusZonePercent = computed(() => 18)

const remainingSeconds = computed(() => {
  if (usesServerClock.value && trackTiming.value?.answer_deadline_at) {
    return Math.max(0, Math.ceil(msUntilDeadline(trackTiming.value.answer_deadline_at, 0) / 1000))
  }

  return Math.max(0, Math.ceil(trackDurationSeconds.value - currentTime.value))
})

const isInSpeedZone = computed(() => (
  percent.value > 0 && percent.value < speedBonusZonePercent.value
))

const timelineVariant = computed(() => {
  if ((countdowning.value || interTrackHolding.value) && countdown.value !== -1) {
    return 'countdown'
  }

  if (loading.value && !countdowning.value && !isPlaying.value && !track.value) {
    return 'loading'
  }

  return 'playing'
})

const clearTrackEndTimer = () => {
  if (trackEndTimer) {
    clearTimeout(trackEndTimer)
    trackEndTimer = null
  }
}

const shouldRunVisualLoop = () => {
  if (countdowning.value || interTrackHolding.value) {
    return true
  }

  return isPlaying.value && !waitingForNextTrack.value
}

const holdInterTrackAtEnd = () => {
  interTrackHolding.value = true
  countdowning.value = true
  countdown.value = 0
  countdownProgress.value = 100
}

const warmNextTrackAudio = (nextTrack, { aggressive = false } = {}) => {
  if (!nextTrack?.audio || nextTrack.provider === 'youtube') {
    return
  }

  if (!nextTrackPreloader) {
    nextTrackPreloader = new Audio()
    // Keep it silent no matter what the browser decides to do.
    nextTrackPreloader.muted = true
    nextTrackPreloader.volume = 0
    // Start cheap: metadata only, we can ramp up near the end of the countdown.
    nextTrackPreloader.preload = 'metadata'
    nextTrackPreloader.crossOrigin = 'anonymous'
  }

  if (nextTrackPreloader.src !== nextTrack.audio) {
    try {
      nextTrackPreloader.pause()
    } catch {}
    nextTrackPreloader.src = nextTrack.audio
    // Cheap warm-up.
    nextTrackPreloader.preload = 'metadata'
    nextTrackPreloader.load()
  }

  if (aggressive) {
    // Near the transition, ask the browser to buffer more.
    nextTrackPreloader.preload = 'auto'
    try {
      nextTrackPreloader.load()
    } catch {}
  }
}

const updateProgress = (elapsedSeconds) => {
  const duration = trackDurationSeconds.value

  if (!duration || duration <= 0) {
    return
  }

  currentTime.value = elapsedSeconds
  barProgress.value = Math.min(100, (100 / duration) * elapsedSeconds)
  percent.value = Math.round(barProgress.value)
  emit('track:currentTime', elapsedSeconds)
}

const tickVisuals = () => {
  if (countdowning.value) {
    if (activeInterTrackPause.value?.next_track_at) {
      const state = getInterTrackCountdown(
        activeInterTrackPause.value.next_track_at,
        interTrackPauseSeconds.value
      )

      countdown.value = state.remainingSeconds
      countdownProgress.value = state.progressPercent

      if (state.remainingMs <= 2000 && pendingNextTrack.value) {
        warmNextTrackAudio(pendingNextTrack.value, { aggressive: true })
      }

      if (state.isComplete) {
        if (waitingForNextTrack.value) {
          holdInterTrackAtEnd()
        } else {
          countdowning.value = false
          countdown.value = 0
          activeInterTrackPause.value = null
        }
      }

      return
    }

    if (fallbackCountdownEndAt) {
      const remainingMs = Math.max(0, fallbackCountdownEndAt - getServerNowMs())
      const totalMs = (interTrackPauseSeconds.value || 1) * 1000

      countdown.value = Math.ceil(remainingMs / 1000)
      countdownProgress.value = Math.min(
        100,
        ((totalMs - remainingMs) / totalMs) * 100
      )

      if (remainingMs <= 0) {
        if (waitingForNextTrack.value) {
          holdInterTrackAtEnd()
          fallbackCountdownEndAt = null
        } else {
          countdowning.value = false
          countdown.value = 0
          fallbackCountdownEndAt = null
        }
      }
    }

    return
  }

  if (!isPlaying.value || waitingForNextTrack.value) {
    return
  }

  if (!isYoutubeTrack.value && analyser && analyserData) {
    analyser.getByteFrequencyData(analyserData)
    const bucketCount = 32
    const buckets = new Array(bucketCount).fill(0)
    const ranges = bucketRanges && bucketRanges.length === bucketCount
      ? bucketRanges
      : null

    for (let i = 0; i < bucketCount; i += 1) {
      let start = 0
      let end = analyserData.length

      if (ranges) {
        start = ranges[i][0]
        end = ranges[i][1]
      }

      let sum = 0
      let samples = 0
      for (let j = start; j < end; j += 1) {
        sum += analyserData[j]
        samples += 1
      }

      const avg = samples > 0 ? sum / samples : 0
      buckets[i] = Math.min(1, Math.max(0, avg / 255))
    }

    // Light smoothing to avoid flicker, without lag.
    const prev = Array.isArray(audioLevels.value) ? audioLevels.value : null
    audioLevels.value = prev
      ? buckets.map((v, idx) => (prev[idx] * 0.65) + (v * 0.35))
      : buckets

    const bass = (audioLevels.value[0] + audioLevels.value[1] + audioLevels.value[2] + audioLevels.value[3]) / 4
    bassLevel.value = (bassLevel.value * 0.7) + (bass * 0.3)
  }

  if (usesServerClock.value) {
    const elapsed = getElapsedSeconds(trackTiming.value.current_track_started_at)
    updateProgress(elapsed)

    if (trackTiming.value?.answer_deadline_at) {
      remainingSecondsLive.value = Math.max(0, Math.ceil(msUntilDeadline(trackTiming.value.answer_deadline_at, 0) / 1000))
    } else {
      remainingSecondsLive.value = Math.max(0, Math.ceil(trackDurationSeconds.value - currentTime.value))
    }

    return
  }

  const time = isYoutubeTrack.value && youtubePlayer.value
    ? youtubePlayer.value.getCurrentTime()
    : audio.value.currentTime

  updateProgress(time)
  remainingSecondsLive.value = Math.max(0, Math.ceil(trackDurationSeconds.value - currentTime.value))
}

const startVisualLoop = () => {
  stopVisualLoop()

  const tick = () => {
    tickVisuals()

    if (shouldRunVisualLoop()) {
      visualLoopId = requestAnimationFrame(tick)
    } else {
      visualLoopId = null
    }
  }

  tickVisuals()
  visualLoopId = requestAnimationFrame(tick)
}

const stopVisualLoop = () => {
  if (visualLoopId !== null) {
    cancelAnimationFrame(visualLoopId)
    visualLoopId = null
  }
}

const ensureAudioContext = async () => {
  if (isYoutubeTrack.value) {
    return
  }

  if (!audioContext) {
    const Ctx = window.AudioContext || window.webkitAudioContext
    if (!Ctx) {
      return
    }

    audioContext = new Ctx()
  }

  if (audioContext.state === 'suspended') {
    await audioContext.resume().catch(() => {})
  }
}

const setupAudioAnalyser = async () => {
  if (isYoutubeTrack.value) {
    audioLevels.value = null
    return
  }

  await ensureAudioContext()
  if (!audioContext) {
    return
  }

  // MediaElementSource can only be created once per HTMLMediaElement per AudioContext.
  // We create it lazily and keep it around.
  if (!analyserSource) {
    try {
      analyserSource = audioContext.createMediaElementSource(audio.value)
    } catch {
      return
    }
  }

  if (!analyser) {
    analyser = audioContext.createAnalyser()
    analyser.fftSize = 2048
    analyser.smoothingTimeConstant = 0.75
    analyserData = new Uint8Array(analyser.frequencyBinCount)
  }

  try {
    analyserSource.disconnect()
  } catch {
    // Ignore if not connected yet.
  }

  try {
    analyser.disconnect()
  } catch {
    // Ignore if not connected yet.
  }

  analyserSource.connect(analyser)
  analyser.connect(audioContext.destination)

  // Precompute log-spaced frequency buckets: 60 Hz -> 10 kHz.
  // This makes the visualizer feel more “musical” (less packed in the highs).
  const bucketCount = 32
  const fMin = 60
  const fMax = 10000
  const nyquist = audioContext.sampleRate / 2
  const binCount = analyser.frequencyBinCount
  const hzToBin = (hz) => Math.max(0, Math.min(binCount - 1, Math.floor((hz / nyquist) * binCount)))

  const edges = new Array(bucketCount + 1).fill(0).map((_, i) => {
    const t = i / bucketCount
    const hz = fMin * Math.pow(fMax / fMin, t)

    return hzToBin(hz)
  })

  bucketRanges = []
  for (let i = 0; i < bucketCount; i += 1) {
    const start = edges[i]
    const end = Math.max(start + 1, edges[i + 1])
    bucketRanges.push([start, Math.min(binCount, end)])
  }
}

const syncProgressFromServerClock = () => {
  if (!usesServerClock.value) {
    return
  }

  updateProgress(getElapsedSeconds(trackTiming.value.current_track_started_at))
}

const resolvePlaybackStartTime = (requestedStartTime = 0) => {
  const duration = trackDurationSeconds.value

  if (usesServerClock.value && trackTiming.value?.current_track_started_at) {
    const fromClock = getElapsedSeconds(trackTiming.value.current_track_started_at)
    const elapsed = requestedStartTime > 0
      ? Math.max(requestedStartTime, fromClock)
      : fromClock

    return Math.min(duration, Math.max(0, elapsed))
  }

  return Math.min(duration, Math.max(0, requestedStartTime))
}

const ensureServerTimeSynced = async (force = false) => {
  if (!props.room?.id) {
    return
  }

  if (!force && Date.now() - lastServerSyncAt < 3000) {
    return
  }

  await measureServerTimeOffset(props.room.id, {
    samples: force ? 3 : 1,
    maxAgeMs: force ? 0 : 3000,
  }).catch(() => {})
  lastServerSyncAt = Date.now()
}

const detachPlayback = ({ resetLevels = true } = {}) => {
  isPlaying.value = false
  stopVisualLoop()

  if (resetLevels) {
    audioLevels.value = null
  }

  activePlaybackTrackId = null

  if (isYoutubeTrack.value) {
    cleanupYoutubePlayer()
  } else {
    audio.value.pause()
    removeAudioEventListeners()
  }
}

const finishTrackPlayback = (interTrackPause = null) => {
  clearTrackEndTimer()
  usersWithAllAnswers.value = []
  detachPlayback({ resetLevels: false })
  waitingForNextTrack.value = true
  loading.value = false
  error.value = null
  startInterTrackCountdown(interTrackPause)
}

const resolveInterTrackPause = (interTrackPause = null) => {
  if (interTrackPause?.next_track_at) {
    return interTrackPause
  }

  const deadline = trackTiming.value?.answer_deadline_at
  if (!deadline) {
    return null
  }

  const pauseSeconds = parseInt(props.room.pause_between_tracks, 10) || 0
  const deadlineMs = new Date(deadline).getTime()

  if (!Number.isFinite(deadlineMs)) {
    return null
  }

  return {
    next_track_at: new Date(deadlineMs + pauseSeconds * 1000).toISOString(),
    pause_between_tracks: pauseSeconds,
  }
}

const startInterTrackCountdown = (interTrackPause = null) => {
  stopVisualLoop()

  const pause = resolveInterTrackPause(interTrackPause)
  countdowning.value = true
  loading.value = false

  if (!pause?.next_track_at) {
    const seconds = parseInt(props.room.pause_between_tracks, 10) || 0

    interTrackPauseSeconds.value = seconds
    activeInterTrackPause.value = null
    fallbackCountdownEndAt = getServerNowMs() + seconds * 1000
    countdown.value = seconds
    countdownProgress.value = 0
    startVisualLoop()

    return
  }

  interTrackPauseSeconds.value = pause.pause_between_tracks
    ?? parseInt(props.room.pause_between_tracks, 10)
    ?? 0
  activeInterTrackPause.value = pause
  fallbackCountdownEndAt = null

  const state = getInterTrackCountdown(
    pause.next_track_at,
    interTrackPauseSeconds.value
  )

  countdown.value = state.remainingSeconds
  countdownProgress.value = state.progressPercent

  if (state.remainingMs <= 2000 && pendingNextTrack.value) {
    warmNextTrackAudio(pendingNextTrack.value, { aggressive: true })
  }

  if (state.isComplete && waitingForNextTrack.value) {
    holdInterTrackAtEnd()
  }

  startVisualLoop()
}

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
    await ensureAudioContext()
    // Preserve the startTime if we're joining mid-track
    const preservedTime = pendingStartTime.value > 0 ? pendingStartTime.value : 0
    await audio.value.play()
    audio.value.pause()
    // Only reset to 0 if we don't have a startTime to preserve
    audio.value.currentTime = preservedTime
    if (preservedTime > 0) {
      currentTime.value = preservedTime
      updateProgress(preservedTime)
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
          updateProgress(startTime)
        }
        loading.value = false
        countdowning.value = false
        interTrackHolding.value = false
        countdown.value = 0
        countdownProgress.value = 0
        startVisualLoop()
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
  if (!track.value) {
    return
  }

  if (isPlaying.value) {
    detachPlayback()
  }

  // Clear waitingForNextTrack when we start playing
  waitingForNextTrack.value = false

  loading.value = true
  error.value = null
  isPlaying.value = true
  startVisualLoop()

  const syncPromise = trackTiming.value?.current_track_started_at
    ? ensureServerTimeSynced()
    : Promise.resolve()

  if (isYoutubeTrack.value) {
    await syncPromise
    const playbackStartTime = resolvePlaybackStartTime(startTime)
    pendingStartTime.value = playbackStartTime
    currentTime.value = playbackStartTime
    remainingSecondsLive.value = Math.max(0, Math.ceil(trackDurationSeconds.value - currentTime.value))

    if (usesServerClock.value) {
      syncProgressFromServerClock()
    } else if (playbackStartTime > 0) {
      updateProgress(playbackStartTime)
    } else {
      barProgress.value = 0
      percent.value = 0
    }

    const videoId = getYoutubeVideoId(track.value?.preview_url)
    if (!videoId) {
      error.value = 'Invalid YouTube URL'
      loading.value = false
      isPlaying.value = false
      return
    }

    if (window.YT?.Player) {
      initYoutubePlayer(videoId, playbackStartTime)
    } else {
      window.onYouTubeIframeAPIReady = () => initYoutubePlayer(videoId, playbackStartTime)
    }
    return
  }

  try {
    playbackGeneration += 1
    activePlaybackTrackId = null
    audioPlaybackStarting = false

    audio.value.pause()
    audio.value.src = track.value.audio
    audio.value.crossOrigin = 'anonymous'
    audio.value.muted = false

    addAudioEventListeners()
    audio.value.load()

    await syncPromise

    const playbackStartTime = resolvePlaybackStartTime(startTime)
    pendingStartTime.value = playbackStartTime
    currentTime.value = playbackStartTime
    remainingSecondsLive.value = Math.max(0, Math.ceil(trackDurationSeconds.value - currentTime.value))

    if (usesServerClock.value) {
      syncProgressFromServerClock()
    } else if (playbackStartTime > 0) {
      updateProgress(playbackStartTime)
    } else {
      barProgress.value = 0
      percent.value = 0
    }

    // If we have a startTime, also try to set it early (but handleCanPlayThrough is the main handler)
    if (playbackStartTime > 0) {
      // Try to set it as soon as metadata is available
      const trySetStartTime = () => {
        if (audio.value.readyState >= 1) { // HAVE_METADATA
          try {
            audio.value.currentTime = playbackStartTime
            currentTime.value = playbackStartTime
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
    playerChannel.stopListening('TrackEnded')
    playerChannel.stopListening('TrackPaused')
    playerChannel.stopListening('TrackResumed')
    playerChannel.stopListening('UserHasFoundAllTheAnswers')
  }

  // TrackPlayed is handled via props from Show.vue (single Echo subscription).
  playerChannel = Echo.join(props.channel)
  playerChannel
    .listen('TrackEnded', handleTrackEnded)
    .listen('TrackPaused', pause)
    .listen('TrackResumed', resume)
    .listen('UserHasFoundAllTheAnswers', handleUserFoundAllAnswers)
    .error((error) => {
      console.error('Echo channel error:', error)
    })
}

const cleanup = () => {
  clearTrackEndTimer()
  stopVisualLoop()
  stop()
  
  // Clean up Echo listeners only (do not Echo.leave - Show.vue owns the presence subscription)
  if (playerChannel) {
    playerChannel.stopListening('TrackEnded')
    playerChannel.stopListening('TrackPaused')
    playerChannel.stopListening('TrackResumed')
    playerChannel.stopListening('UserHasFoundAllTheAnswers')
    playerChannel = null
  }
  
  window.removeEventListener('volume-localstorage-changed', handleVolumeChange)
  removeAudioEventListeners()
}

const beginInitialInterTrackPause = (interTrackPause) => {
  interTrackHolding.value = false
  waitingForNextTrack.value = true
  loading.value = false
  startInterTrackCountdown(interTrackPause)
}

const handleVolumeChange = (event) => {
  volume.value = event.detail.volume
}

const isStaleTrackEndedEvent = (event) => {
  if (waitingForNextTrack.value && countdowning.value) {
    return true
  }

  const endedTrackId = event?.track?.id
  const activeTrackId = track.value?.id

  if (!endedTrackId || !activeTrackId || endedTrackId === activeTrackId) {
    return false
  }

  const eventSequence = event?.round?.current
  const activeSequence = trackTiming.value?.track_sequence

  if (eventSequence != null && activeSequence != null && eventSequence < activeSequence) {
    return true
  }

  return !waitingForNextTrack.value && isPlaying.value
}

const applyIncomingTrack = async (trackData, roundData, startTime = 0) => {
  if (!trackData) {
    return
  }

  const sequence = roundData?.track_sequence ?? roundData?.current ?? 0
  const trackKey = `${trackData.id}:${sequence}:${startTime}`

  if (
    trackKey === lastAppliedTrackKey
    && isPlaying.value
    && !waitingForNextTrack.value
    && track.value?.id === trackData.id
  ) {
    return
  }

  lastAppliedTrackKey = trackKey
  clearTrackEndTimer()

  const fromInterTrack = waitingForNextTrack.value || interTrackHolding.value || countdowning.value

  if (!fromInterTrack) {
    stopVisualLoop()
    activeInterTrackPause.value = null
    fallbackCountdownEndAt = null
    countdowning.value = false
    interTrackHolding.value = false
    countdown.value = 0
    countdownProgress.value = 0
    audioLevels.value = null
    bassLevel.value = 0
  }

  audioPlaybackStarting = false
  activePlaybackTrackId = null

  track.value = trackData
  trackTiming.value = roundData ?? null
  waitingForNextTrack.value = false
  pendingNextTrack.value = null

  if (roundData?.id && trackData.id) {
    axios.post(`/rounds/${roundData.id}/tracks/${trackData.id}/listened`).catch(() => {})
  }

  await play(startTime)
}

const handleTrackEnded = (e) => {
  if (isStaleTrackEndedEvent(e)) {
    return
  }

  clearTrackEndTimer()
  ensureServerTimeSynced(true)

  if (e?.next_track) {
    pendingNextTrack.value = e.next_track
    warmNextTrackAudio(e.next_track, { aggressive: false })
  }

  const deadline = trackTiming.value?.answer_deadline_at
  const wait = deadline ? msUntilDeadline(deadline, 300) : 0
  const interTrackPause = e?.next_track_at
    ? {
        next_track_at: e.next_track_at,
        pause_between_tracks: e.pause_between_tracks,
      }
    : null

  const beginPause = () => finishTrackPlayback(interTrackPause)

  if (wait > 50) {
    trackEndTimer = setTimeout(beginPause, wait)

    return
  }

  beginPause()
}

const handleUserFoundAllAnswers = (e) => {
  if (!usersWithAllAnswers.value.some(user => user.id === e.user.id)) {
    usersWithAllAnswers.value.push(e.user)
  }
}

const addAudioEventListeners = () => {
  if (isYoutubeTrack.value) {
    return
  }

  removeAudioEventListeners()

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
        updateProgress(pendingStartTime.value)
        return true
      }
    } catch (e) {
      console.warn('Could not set currentTime:', e)
    }
  }
  return false
}

const beginAudioPlayback = async () => {
  const trackId = track.value?.id
  const generation = playbackGeneration

  if (
    waitingForNextTrack.value
    || isYoutubeTrack.value
    || audioPlaybackStarting
    || (trackId && activePlaybackTrackId === trackId)
  ) {
    return
  }

  audioPlaybackStarting = true

  try {
    if (generation !== playbackGeneration) {
      return
    }

    const targetStartTime = pendingStartTime.value

    // Apply the start time BEFORE playing - this is critical
    if (targetStartTime > 0 && audio.value.readyState >= 2) {
      try {
        audio.value.currentTime = targetStartTime
        currentTime.value = targetStartTime
        updateProgress(targetStartTime)
      } catch (e) {
        console.warn('Could not set currentTime before play:', e)
      }
    }

    loading.value = false
    countdowning.value = false
    interTrackHolding.value = false
    activeInterTrackPause.value = null
    countdown.value = 0
    countdownProgress.value = 0
    startVisualLoop()

    await setupAudioAnalyser()

    if (generation !== playbackGeneration) {
      return
    }

    // For iOS, we need to pause and set time before playing
    if (isIOS.value && targetStartTime > 0) {
      audio.value.pause()
      try {
        audio.value.currentTime = targetStartTime
        currentTime.value = targetStartTime
      } catch (e) {
        console.warn('Player::beginAudioPlayback - Could not set currentTime on iOS:', e)
      }
    }

    try {
      await audio.value.play()

      if (generation !== playbackGeneration) {
        audio.value.pause()
        return
      }

      activePlaybackTrackId = trackId ?? null

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
              updateProgress(targetStartTime)
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
  } finally {
    audioPlaybackStarting = false
  }
}

const handleCanPlayThrough = async () => {
  await beginAudioPlayback()
}

const handleTimeUpdate = () => {
  if (usesServerClock.value) {
    return
  }

  const time = isYoutubeTrack.value && youtubePlayer.value
    ? youtubePlayer.value.getCurrentTime()
    : audio.value.currentTime

  updateProgress(time)
}

const handleAudioEnded = () => {
  if (waitingForNextTrack.value) {
    return
  }

  if (usesServerClock.value && trackTiming.value?.answer_deadline_at) {
    const wait = msUntilDeadline(trackTiming.value.answer_deadline_at, 300)
    if (wait > 50) {
      return
    }
  }

  isPlaying.value = false
  emit('track:ended', track.value)
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
      startVisualLoop()
    } catch (error) {
      console.error('Error resuming audio:', error)
    }
  }
}

const stop = async () => {
  detachPlayback()
  waitingForNextTrack.value = true
  emit('track:stopped', track.value)
}

const bootstrapPlaybackFromProps = async () => {
  if (props.initialInterTrackPause?.next_track_at) {
    beginInitialInterTrackPause(props.initialInterTrackPause)

    return
  }

  if (!props.initialTrack) {
    waitingForNextTrack.value = true
    loading.value = true

    return
  }

  await applyIncomingTrack(
    props.initialTrack,
    props.initialTrackTiming ?? null,
    props.initialStartTime || 0
  )
}

// Watch for prop updates after initial bootstrap (reconnect resync)
watch(() => [props.initialTrack, props.initialStartTime, props.initialTrackTiming, props.initialInterTrackPause], async (newValue, oldValue) => {
  if (!playbackBootstrapped.value) {
    return
  }

  const [newTrack, newStartTime, newTiming, newInterTrackPause] = newValue || [null, null, null, null]
  const [oldTrack, oldStartTime] = oldValue || [null, null, null, null]

  if (newTiming) {
    trackTiming.value = newTiming
  }

  if (newInterTrackPause?.next_track_at) {
    beginInitialInterTrackPause(newInterTrackPause)

    return
  }

  const trackChanged = newTrack && (!oldTrack || oldTrack.id !== newTrack.id)
  const resyncSameTrack = newTrack
    && oldTrack
    && oldTrack.id === newTrack.id
    && newStartTime !== oldStartTime
    && Math.abs(newStartTime - currentTime.value) > 1

  if (trackChanged || resyncSameTrack) {
    await applyIncomingTrack(newTrack, trackTiming.value, newStartTime || 0)
  }
})

onMounted(async () => {
  initializeAudio()
  setupEventListeners()
  await bootstrapPlaybackFromProps()
  playbackBootstrapped.value = true
})

onBeforeUnmount(() => {
  cleanup()
  cleanupYoutubePlayer()
})
</script>

<template>
  <div id="youtube-player" class="hidden"></div>
  
  <div id="player" class="relative mb-1">
    <div class="relative">
      <TransitionGroup
        name="user-answer"
        tag="ul"
        class="pointer-events-none absolute inset-x-0 bottom-full z-40 mb-1.5 h-8"
      >
        <li
          v-for="user in usersWithAllAnswers"
          :key="user.id"
          class="absolute bottom-0 z-20 rounded-md bg-teal-600 px-2 py-1 text-xs font-medium text-white shadow-lg"
          :style="`left: calc(${(100 / trackDurationSeconds) * user.time}% - 1rem);`"
        >
          <div class="flex items-center gap-1">
            <img v-if="user.photo" :src="user.photo" class="h-4 w-4 rounded-full" />
            <span class="max-w-16 select-none truncate whitespace-nowrap">{{ user.name }}</span>
          </div>
          <div class="absolute left-1/2 top-full h-0 w-0 -translate-x-1/2 border-l-[6px] border-r-[6px] border-t-[6px] border-l-transparent border-r-transparent border-t-teal-600" />
        </li>
      </TransitionGroup>

    <template v-if="error">
      <div class="flex h-auto w-full flex-col p-3 text-red-300">
        <div class="mb-2 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <span class="font-medium">{{ error.split('\n')[0] }}</span>
        </div>
        <div class="whitespace-pre-line pl-7 text-sm">
          {{ error.split('\n').slice(1).join('\n') }}
        </div>
      </div>
    </template>

    <PlayerTimeline
      v-else
      :key="`${track?.id ?? 'idle'}:${trackTiming?.track_sequence ?? 0}`"
      :variant="timelineVariant"
      :progress="timelineVariant === 'countdown' ? countdownProgress : barProgress"
      :remaining-seconds="remainingSecondsLive"
      :countdown="countdown"
      :in-speed-zone="isInSpeedZone"
      :speed-zone-percent="speedBonusZonePercent"
      :levels="audioLevels"
      :bass="bassLevel"
    />
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
  transition: opacity 0.35s ease, transform 0.35s ease;
}

.user-answer-enter-from,
.user-answer-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

.user-answer-leave-active {
  position: absolute;
}
</style>