<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Spinner from '@/Components/Spinner.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  track: Object,
})

const audio = new Audio()
const loading = ref(false)
const isPlaying = ref(false)
const error = ref(false)

onUnmounted(() => {
  stop()
})

const play = () => {
  error.value = false
  loading.value = true
  isPlaying.value = true
  
  audio.src = props.track.preview_url
  audio.crossOrigin = 'anonymous'  // Add CORS header
  
  audio.addEventListener('error', () => {
    error.value = true
    loading.value = false
    isPlaying.value = false
  }, { once: true })

  audio.addEventListener('canplaythrough', () => {
    loading.value = false
    audio.play().catch(() => {
      error.value = true
      isPlaying.value = false
    })
  }, { once: true })
}

const stop = () => {
  isPlaying.value = false
  audio.pause()
}
</script>
<template>
  <Icon v-if="!isPlaying && !error" 
        name="play" 
        class="mr-2 h-10 w-10 flex-shrink-0 cursor-pointer" 
        @click="play" />
  <div v-else-if="loading" 
       class="mr-2 flex h-10 w-10 items-center justify-center rounded-full bg-neutral-800">
    <Spinner class="h-6 w-6 block mx-auto" />
  </div>
  <Icon v-else-if="error" 
        name="exclamation-circle" 
        class="mr-2 h-10 w-10 flex-shrink-0 text-red-500" 
        title="Failed to load audio" />
  <Icon v-else 
        name="stop" 
        class="mr-2 h-10 w-10 flex-shrink-0 cursor-pointer" 
        @click="stop" />
</template>
