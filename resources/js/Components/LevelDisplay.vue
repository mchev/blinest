<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import LevelBadge from '@/Components/LevelBadge.vue'
import LevelModal from '@/Components/LevelModal.vue'

const props = defineProps({
  level: {
    type: Number,
    default: 1,
  },
  currentXp: {
    type: Number,
    default: 0,
  },
  xpForNextLevel: {
    type: Number,
    default: 100,
  },
})

const page = usePage()
const user = page.props.auth?.user

// Translation function for script setup
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

// Reactive state for real-time updates
const currentLevel = ref(props.level)
const currentXpValue = ref(props.currentXp)
const currentXpForNextLevel = ref(props.xpForNextLevel)
const totalXp = ref(user?.total_xp ?? 0)

// Animation states
const isUpdating = ref(false)
const isLevelUp = ref(false)
const previousLevel = ref(props.level)
const isScoreUpdated = ref(false)
const showModal = ref(false)

// Update when props change
watch(
  () => props.level,
  (newLevel) => {
    const wasLevelUp = newLevel > previousLevel.value
    previousLevel.value = newLevel
    currentLevel.value = newLevel

    if (wasLevelUp) {
      triggerLevelUpAnimation()
    }
  },
)
watch(
  () => props.currentXp,
  (newXp) => {
    currentXpValue.value = newXp
  },
)
watch(
  () => props.xpForNextLevel,
  (newXpForNext) => {
    currentXpForNextLevel.value = newXpForNext
  },
)

// Animation functions
const triggerUpdateAnimation = () => {
  isUpdating.value = true
  setTimeout(() => {
    isUpdating.value = false
  }, 600)
}

const triggerLevelUpAnimation = () => {
  isLevelUp.value = true
  setTimeout(() => {
    isLevelUp.value = false
  }, 1500)
}

const triggerScoreUpdateAnimation = () => {
  isScoreUpdated.value = true
  setTimeout(() => {
    isScoreUpdated.value = false
  }, 600)
}

// Listen for real-time level updates
onMounted(() => {
  if (user && window.Echo) {
    const channel = window.Echo.private(`App.Models.User.${user.id}`)

    channel
      .listen('.user.level.updated', (data) => {
        const wasLevelUp = data.level > currentLevel.value

        currentLevel.value = data.level
        currentXpValue.value = data.current_xp
        currentXpForNextLevel.value = data.xp_for_next_level
        totalXp.value = data.total_xp ?? 0

        // Update metrics if provided
        if (data.level_metrics) {
          levelMetrics.value = data.level_metrics
        }

        // Trigger score update animation
        triggerScoreUpdateAnimation()

        if (wasLevelUp) {
          triggerLevelUpAnimation()
        } else {
          triggerUpdateAnimation()
        }
      })
      .error((error) => {
        // Silently handle errors - level updates will sync on next page load
        if (import.meta.env.DEV) {
          console.error('Echo channel error for user level:', error)
        }
      })
  }
})

onUnmounted(() => {
  if (user && window.Echo) {
    window.Echo.leave(`App.Models.User.${user.id}`)
  }
})

// Calcul du pourcentage de progression vers le niveau suivant
const progressPercentage = computed(() => {
  if (currentXpForNextLevel.value === 0) return 100
  return Math.min((currentXpValue.value / currentXpForNextLevel.value) * 100, 100)
})

// Couleur discrète selon le niveau (style cohérent avec le site)
const levelColor = computed(() => {
  if (currentLevel.value >= 50) {
    return 'text-purple-500 hover:text-purple-400'
  }
  if (currentLevel.value >= 30) {
    return 'text-yellow-500 hover:text-yellow-400'
  }
  if (currentLevel.value >= 20) {
    return 'text-blue-500 hover:text-blue-400'
  }
  if (currentLevel.value >= 10) {
    return 'text-green-500 hover:text-green-400'
  }
  return 'text-neutral-400 hover:text-neutral-300'
})

// Reactive state for metrics
const levelMetrics = ref(user?.level_metrics || null)

// Update metrics when props change
watch(
  () => user?.level_metrics,
  (newMetrics) => {
    if (newMetrics) {
      levelMetrics.value = newMetrics
    }
  },
)

// Calcul des XP pour chaque métrique
const metricsXp = computed(() => {
  if (!levelMetrics.value) return null

  const metrics = levelMetrics.value

  return {
    score: {
      label: __('Total score'),
      value: metrics.score_public_rooms,
      xp: metrics.score_public_rooms, // 1 point = 1 XP
      max: null,
    },
    seniority: {
      label: __('Seniority'),
      value: Math.round(metrics.seniority_months),
      xp: Math.min(Math.round(metrics.seniority_months) * 50, 600), // 50 XP par mois, max 600
      max: 600,
    },
    streak: {
      label: __('Consecutive days streak'),
      value: metrics.consecutive_days_streak,
      xp: Math.min(metrics.consecutive_days_streak * 10, 300), // 10 XP par jour, max 300
      max: 300,
    },
    rooms: {
      label: __('Rooms created'),
      value: metrics.rooms_created_count,
      xp: Math.min(metrics.rooms_created_count * 100, 1000), // 100 XP par room, max 1000
      max: 1000,
    },
    playlists: {
      label: __('Playlists created'),
      value: metrics.playlists_created_count,
      xp: Math.min(metrics.playlists_created_count * 20, 2000), // 20 XP par playlist, max 2000
      max: 2000,
    },
    likes: {
      label: __('Tracks liked'),
      value: metrics.tracks_liked_count,
      xp: Math.min(metrics.tracks_liked_count * 5, 1000), // 5 XP par like, max 1000
      max: 1000,
    },
    team: {
      label: __('Team'),
      value: metrics.has_team ? __('Yes') : __('No'),
      xp: metrics.has_team ? 200 : 0, // 200 XP si dans une équipe
      max: 200,
    },
  }
})
</script>

<template>
  <div>
    <div :class="['flex h-10 w-10 cursor-pointer items-center justify-center transition-all duration-300', isScoreUpdated ? 'animate-score-update' : '']" @click="showModal = true">
      <LevelBadge :level="currentLevel" :current-xp="currentXpValue" :xp-for-next-level="currentXpForNextLevel" size="lg" variant="compact" :is-level-up="isLevelUp" :is-updating="isUpdating" />
    </div>

    <LevelModal :show="showModal" :level="currentLevel" :current-xp="currentXpValue" :xp-for-next-level="currentXpForNextLevel" :total-xp="totalXp" :level-metrics="levelMetrics" @close="showModal = false" />
  </div>
</template>
