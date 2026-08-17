<script setup>
import { ref, watch, onBeforeUnmount, nextTick } from 'vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  previewUrl: {
    type: String,
    default: null,
  },
  artworkUrl: {
    type: String,
    default: null,
  },
  autoPlay: {
    type: Boolean,
    default: true,
  },
  countdownSeconds: {
    type: Number,
    default: 3,
  },
})

const audio = ref(null)
const isPlaying = ref(false)
const countdown = ref(null)
const needsUserClick = ref(false)
let countdownTimer = null

function togglePlay() {
  if (!audio.value) return
  if (isPlaying.value) {
    audio.value.pause()
  } else {
    audio.value.play().catch(() => {})
  }
}

function handleAutoplayClick() {
  needsUserClick.value = false
  togglePlay()
}

function onPlay() {
  isPlaying.value = true
  countdown.value = null
}

function onPause() {
  isPlaying.value = false
}

const emit = defineEmits(['ended'])

function onEnded() {
  isPlaying.value = false
  emit('ended')
}

function startCountdown() {
  if (!props.autoPlay || !props.previewUrl) return
  if (props.countdownSeconds < 1) {
    nextTick(() => {
      if (audio.value) {
        audio.value.play().catch(() => {})
      }
    })
    return
  }
  countdown.value = props.countdownSeconds
  let remaining = props.countdownSeconds
  countdownTimer = setInterval(() => {
    remaining -= 1
    countdown.value = remaining
    if (remaining <= 0) {
      clearInterval(countdownTimer)
      countdownTimer = null
      countdown.value = null
      if (audio.value) {
        audio.value.play().catch(() => {
          needsUserClick.value = true
        })
      }
    }
  }, 1000)
}

function clearCountdown() {
  if (countdownTimer) {
    clearInterval(countdownTimer)
    countdownTimer = null
  }
  countdown.value = null
}

/** Stop playback and reset; call from parent when loading next question. */
function stop() {
  clearCountdown()
  needsUserClick.value = false
  if (audio.value) {
    audio.value.pause()
    audio.value.currentTime = 0
  }
  isPlaying.value = false
}

defineExpose({ stop })

watch(
  () => props.previewUrl,
  (url) => {
    clearCountdown()
    needsUserClick.value = false
    if (audio.value && url) {
      audio.value.load()
    }
    isPlaying.value = false
    if (url && props.autoPlay) {
      nextTick(() => {
        startCountdown()
      })
    }
  },
  { immediate: true },
)

onBeforeUnmount(() => {
  clearCountdown()
})
</script>

<template>
  <div class="flex flex-col items-center gap-4">
    <div class="relative flex h-40 w-40 flex-shrink-0 overflow-hidden rounded-xl bg-neutral-700/50" :class="{ 'ring-2 ring-teal-500': isPlaying }">
      <img v-if="artworkUrl" :src="artworkUrl" alt="" class="h-full w-full object-cover" />
      <div v-else class="flex h-full w-full items-center justify-center text-neutral-500">
        <Icon name="play" class="h-16 w-16" />
      </div>
      <!-- Countdown overlay -->
      <div v-if="countdown !== null" class="absolute inset-0 flex flex-col items-center justify-center bg-black/60 text-white">
        <span class="text-sm font-medium">{{ __('Music starts in') }}</span>
        <span class="text-4xl font-bold tabular-nums">{{ countdown }}</span>
      </div>
      <!-- Autoplay blocked: click to play -->
      <div v-else-if="needsUserClick" class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-black/70 text-white">
        <p class="text-sm">{{ __('Click to play') }}</p>
        <button type="button" class="flex h-14 w-14 items-center justify-center rounded-full bg-teal-500 text-2xl text-white hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-400" :aria-label="__('Play')" @click="handleAutoplayClick">▶</button>
      </div>
      <!-- Play / Pause button (hidden during countdown) -->
      <button v-else type="button" class="absolute inset-0 flex items-center justify-center bg-black/40 transition hover:bg-black/50 focus:outline-none focus:ring-2 focus:ring-teal-500" :aria-label="isPlaying ? __('Pause') : __('Play')" @click="togglePlay">
        <span v-if="!isPlaying" class="flex h-14 w-14 items-center justify-center rounded-full bg-white/90 text-2xl text-neutral-800" aria-hidden="true"> ▶ </span>
        <span v-else class="flex h-14 w-14 items-center justify-center rounded-full bg-white/90 text-2xl text-neutral-800" aria-hidden="true"> ❚❚ </span>
      </button>
    </div>
    <audio v-if="previewUrl" ref="audio" :src="previewUrl" class="hidden" @play="onPlay" @pause="onPause" @ended="onEnded" />
    <p v-else class="text-sm text-amber-500">
      {{ __('No preview available for this track.') }}
    </p>
  </div>
</template>
