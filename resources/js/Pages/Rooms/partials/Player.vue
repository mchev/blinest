<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import UserGestureModal from '@/Components/UserGestureModal.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true
  },
  channel: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['track:played', 'track:ended', 'track:paused', 'track:stopped', 'track:currentTime'])

const audio = ref(new Audio())
const track = ref(null)
const loading = ref(true)
const isPlaying = ref(false)
const error = ref(null)
const percent = ref(0)
const usersWithAllAnswers = ref([])
const isIOS = ref(/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)
const countdown = ref(0)
const countdowning = ref(false)
const waitingForNextTrack = ref(false)

const triggerUserGesture = () => {
  console.log('User gesture triggered')
  audio.value.play().catch(error => console.error('Error playing audio:', error))
}

onMounted(() => {
  console.log('Player component mounted')
  initializeAudio()
  setupEventListeners()
})

onUnmounted(() => {
  console.log('Player component unmounted')
  cleanup()
})

const initializeAudio = () => {
  console.log('Initializing audio')
  audio.value.muted = true
  audio.value.volume = parseFloat(localStorage.getItem('volume') || '1')
}

const setupEventListeners = () => {
  console.log('Setting up event listeners')
  window.addEventListener('volume-localstorage-changed', handleVolumeChange)

  const channel = Echo.channel(props.channel)
  channel.listen('TrackPlayed', handleTrackPlayed)
        .listen('TrackEnded', handleTrackEnded)
        .listen('TrackPaused', pause)
        .listen('TrackResumed', resume)
        .listen('UserHasFoundAllTheAnswers', handleUserFoundAllAnswers)
}

const cleanup = () => {
  console.log('Cleaning up')
  stop()
  Echo.leave(props.channel)
  window.removeEventListener('volume-localstorage-changed', handleVolumeChange)
  removeAudioEventListeners()
}

const handleVolumeChange = (event) => {
  console.log('Volume changed:', event.detail.volume)
  audio.value.volume = event.detail.volume
}

const handleTrackPlayed = (e) => {
  console.log('Track played')
  track.value = e.track
  waitingForNextTrack.value = false
  play()
}

const handleTrackEnded = () => {
  console.log('Track ended')
  usersWithAllAnswers.value = []
  stop()
  waitingForNextTrack.value = true
  startCountdown()
}

const handleUserFoundAllAnswers = (e) => {
  console.log('User found all answers')
  usersWithAllAnswers.value.push(e.user)
}

const play = () => {
  console.log('Playing track')
  if (isPlaying.value) stop()

  loading.value = true
  error.value = null
  isPlaying.value = true

  audio.value.src = track.value.preview_url
  audio.value.crossOrigin = 'anonymous'
  audio.value.load()
  audio.value.muted = false

  addAudioEventListeners()
}

const addAudioEventListeners = () => {
  console.log('Adding audio event listeners')
  audio.value.addEventListener('error', handleAudioError)
  audio.value.addEventListener('canplaythrough', handleCanPlayThrough)
  audio.value.addEventListener('timeupdate', handleTimeUpdate)
  audio.value.addEventListener('ended', handleAudioEnded)
}

const removeAudioEventListeners = () => {
  console.log('Removing audio event listeners')
  audio.value.removeEventListener('error', handleAudioError)
  audio.value.removeEventListener('canplaythrough', handleCanPlayThrough)
  audio.value.removeEventListener('timeupdate', handleTimeUpdate)
  audio.value.removeEventListener('ended', handleAudioEnded)
}

const handleAudioError = () => {
  console.error('Audio error:', audio.value.error)
  error.value = audio.value.error.code === 13
    ? `Erreur de lecture média. Veuillez vérifier votre périphérique de sortie audio. (${audio.value.error.message})`
    : audio.value.error.message
  isPlaying.value = false
}

const handleCanPlayThrough = () => {
  if (!waitingForNextTrack.value) {
    console.log('Audio can play through')
    loading.value = false
    if (isIOS.value) {
      audio.value.pause()
      audio.value.currentTime = 0
    }
    audio.value.play().catch(error => console.error('Error playing audio:', error))
  }
}

const handleTimeUpdate = () => {
  emit('track:currentTime', audio.value.currentTime)
  percent.value = Math.min(100, parseInt((100 / props.room.track_duration) * (audio.value.currentTime + 0.25)))
}

const handleAudioEnded = () => {
  console.log('Audio ended')
  if (!waitingForNextTrack.value) {
    isPlaying.value = false
    loading.value = true
    emit('track:ended', track.value)
  }
}

const pause = () => {
  console.log('Pausing audio')
  audio.value.pause()
  emit('track:paused', track.value)
}

const resume = () => {
  console.log('Resuming audio')
  audio.value.play().catch(error => console.error('Error resuming audio:', error))
}

const stop = () => {
  console.log('Stopping audio')
  audio.value.pause()
  audio.value.currentTime = 0
  isPlaying.value = false
  loading.value = false
  waitingForNextTrack.value = true
  emit('track:stopped', track.value)
}

const startCountdown = () => {
  console.log('Starting countdown')
  countdown.value = parseInt(props.room.pause_between_tracks)
  countdowning.value = true
  const interval = setInterval(() => {
    if (countdown.value <= 0) {
      console.log('Countdown finished')
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
    <TransitionGroup 
      name="user-answer"
      tag="ul"
      class="absolute w-full"
    >
      <li 
        v-for="(user, index) in usersWithAllAnswers" 
        :key="user.id" 
        class="absolute z-20 rounded-full bg-teal-600 p-2 text-xs text-white shadow-lg hover:z-30"
        :style="`
          left: calc(${(100 / props.room.track_duration) * user.time}% - 1rem);
          top: ${-48 + index * 5}px;
        `"
      >
        <span class="whitespace-nowrap select-none max-w-16 truncate">{{ user.name }}</span>
        <div class="absolute left-1/2 top-full h-0 w-0 -translate-x-1/2 border-t-[8px] border-l-[8px] border-r-[8px] border-t-teal-600 border-l-transparent border-r-transparent"></div>
      </li>
    </TransitionGroup>
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