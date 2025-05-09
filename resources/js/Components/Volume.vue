<script setup>
import { ref, watch, onMounted, computed } from 'vue'

const volume = ref(0.7)
const isMuted = ref(false)
const previousVolume = ref(0.7)

onMounted(() => {
  volume.value = localStorage.getItem('volume') ?? 0.7
  dispatch()
})

watch(volume, (value) => {
  localStorage.setItem('volume', value)
  dispatch()
})

const volumePercentage = computed(() => {
  return Math.round(volume.value * 100)
})

const volumeIcon = computed(() => {
  if (isMuted.value || volume.value === 0) {
    return 'volume-off'
  } else if (volume.value < 0.5) {
    return 'volume-low'
  } else {
    return 'volume-high'
  }
})

const toggleMute = () => {
  if (isMuted.value) {
    isMuted.value = false
    volume.value = previousVolume.value
  } else {
    previousVolume.value = volume.value
    isMuted.value = true
    volume.value = 0
  }
}

const dispatch = () => {
  window.dispatchEvent(
    new CustomEvent('volume-localstorage-changed', {
      detail: {
        volume: volume.value,
      },
    }),
  )
}
</script>
<template>
  <div class="flex items-center space-x-2 bg-neutral-800 rounded-lg p-2 border border-neutral-700">
    <button 
      @click="toggleMute" 
      class="text-neutral-300 hover:text-white transition-colors focus:outline-none"
      :title="isMuted ? 'Unmute' : 'Mute'"
    >
      <svg v-if="volumeIcon === 'volume-off'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
      </svg>
      <svg v-else-if="volumeIcon === 'volume-low'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
      </svg>
    </button>
    
    <input
      id="volume-slider"
      type="range"
      min="0"
      max="1"
      step="0.01"
      v-model="volume"
      class="w-full h-2 bg-neutral-700 rounded-lg appearance-none cursor-pointer accent-purple-500 hover:accent-purple-600 focus:outline-none focus:ring-1 focus:ring-purple-500"
      :title="`Volume: ${volumePercentage}%`"
    />
    
    <span class="text-xs font-medium text-neutral-300 min-w-[2.5rem] text-center">
      {{ volumePercentage }}%
    </span>
  </div>
</template>
