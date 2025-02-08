<script setup>
import { ref, onUnmounted, onMounted } from 'vue'
import Spinner from '@/Components/Spinner.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  track: {
    type: Object,
    required: true
  }
})

const audio = new Audio()
const loading = ref(false)
const isPlaying = ref(false)
const error = ref(false)
const progress = ref(0)
const youtubePlayerId = `youtube-player-${props.track.id}`
const youtubePlayer = ref(null)
const isYoutubeTrack = ref(false)

onMounted(() => {
  if (!window.YT && !window.YTScriptLoaded) {
    window.YTScriptLoaded = true
    const tag = document.createElement('script')
    tag.src = 'https://www.youtube.com/iframe_api'
    const firstScriptTag = document.getElementsByTagName('script')[0]
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)
  }
})

onUnmounted(() => {
  cleanup()
})

const cleanup = () => {
  stop()
  audio.removeEventListener('timeupdate', updateProgress)
  if (youtubePlayer.value) {
    youtubePlayer.value.destroy()
    youtubePlayer.value = null
  }
}

const updateProgress = () => {
  progress.value = (audio.currentTime / audio.duration) * 100
}

const play = () => {
  error.value = false
  loading.value = true
  isPlaying.value = true
  
  isYoutubeTrack.value = props.track.provider === 'youtube'

  if (isYoutubeTrack.value) {
    if (window.YT && window.YT.Player) {
      initYoutubePlayer()
    } else {
      window.onYouTubeIframeAPIReady = () => {
        initYoutubePlayer()
      }
    }
  } else {
    document.querySelectorAll('[id^="youtube-player-"]').forEach(player => {
      if (window.YT) {
        const ytPlayer = YT.get(player.id)
        if (ytPlayer) {
          ytPlayer.stopVideo()
        }
      }
    })
    
    audio.src = props.track.audio ?? props.track.preview_url
    audio.crossOrigin = 'anonymous'
    
    audio.addEventListener('error', () => {
      error.value = true
      loading.value = false
      isPlaying.value = false
    }, { once: true })

    audio.addEventListener('canplaythrough', () => {
      loading.value = false
      audio.play().catch((e) => {
        console.error('Audio playback failed:', e)
        error.value = true 
        isPlaying.value = false
      })
    }, { once: true })

    audio.addEventListener('ended', () => {
      isPlaying.value = false
      progress.value = 0
    })

    audio.addEventListener('timeupdate', updateProgress)
  }
}

const initYoutubePlayer = () => {
  const existingPlayer = YT.get(youtubePlayerId)
  if (existingPlayer) {
    youtubePlayer.value = existingPlayer
    youtubePlayer.value.playVideo()
    return
  }

  youtubePlayer.value = new YT.Player(youtubePlayerId, {
    height: '1',
    width: '1',
    videoId: props.track.preview_url,
    playerVars: {
      'autoplay': 1,
      'controls': 0,
      'playsinline': 1
    },
    events: {
      'onStateChange': (event) => {
        if (event.data === YT.PlayerState.ENDED) {
          isPlaying.value = false
          progress.value = 0
        }
        if (event.data === YT.PlayerState.PLAYING) {
          loading.value = false
          document.querySelectorAll('[id^="youtube-player-"]').forEach(player => {
            if (player.id !== youtubePlayerId && window.YT) {
              const ytPlayer = YT.get(player.id)
              if (ytPlayer && ytPlayer !== youtubePlayer.value) {
                ytPlayer.stopVideo()
              }
            }
          })
        }
      },
      'onError': () => {
        error.value = true
        loading.value = false
        isPlaying.value = false
      }
    }
  })
}

const stop = () => {
  isPlaying.value = false
  progress.value = 0
  if (isYoutubeTrack.value && youtubePlayer.value) {
    youtubePlayer.value.destroy()
    youtubePlayer.value = null
  } else {
    audio.pause()
    audio.currentTime = 0
  }
}
</script>

<template>
  <div class="flex items-center">
    <div class="youtube-player-container hidden">
      <div :id="youtubePlayerId"></div>
    </div>
    <div class="relative">
      <Icon v-if="!isPlaying && !error" 
            name="play" 
            class="h-10 w-10 flex-shrink-0 cursor-pointer hover:text-red-500 transition-colors" 
            @click="play" />
            
      <div v-else-if="loading" 
           class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-800">
        <Spinner class="h-6 w-6 block mx-auto" />
      </div>
      
      <Icon v-else-if="error" 
            name="exclamation-circle" 
            class="h-10 w-10 flex-shrink-0 text-red-500" 
            title="Failed to load audio" />

      <div v-else class="relative">
        <Icon name="stop" 
              class="h-10 w-10 flex-shrink-0 cursor-pointer hover:text-red-500 transition-colors relative z-20" 
              @click="stop" />

        <svg v-if="isPlaying && !loading"
             class="absolute z-10 inset-0 -rotate-90 transform pointer-events-none"
             viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="45" 
                  class="fill-none stroke-neutral-800 stroke-[10]" />
          <circle cx="50" cy="50" r="45"
                  class="fill-none stroke-red-500 stroke-[10] transition-all duration-200"
                  :style="{
                    strokeDasharray: `${progress * 2.83}, 283`
                  }" />
        </svg>
      </div>
    </div>
  </div>
</template>
