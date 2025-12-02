<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import LevelBadge from '@/Components/LevelBadge.vue'

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

// Animation states
const isUpdating = ref(false)
const isLevelUp = ref(false)
const previousLevel = ref(props.level)
const isScoreUpdated = ref(false)

// Update when props change
watch(() => props.level, (newLevel) => {
  const wasLevelUp = newLevel > previousLevel.value
  previousLevel.value = newLevel
  currentLevel.value = newLevel
  
  if (wasLevelUp) {
    triggerLevelUpAnimation()
  }
})
watch(() => props.currentXp, (newXp) => {
  currentXpValue.value = newXp
})
watch(() => props.xpForNextLevel, (newXpForNext) => {
  currentXpForNextLevel.value = newXpForNext
})

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
watch(() => user?.level_metrics, (newMetrics) => {
  if (newMetrics) {
    levelMetrics.value = newMetrics
  }
})

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
  <dropdown placement="bottom-end">
    <template #default>
      <div 
        :class="[
          'cursor-pointer flex items-center justify-center h-10 w-10 transition-all duration-300',
          isScoreUpdated ? 'animate-score-update' : ''
        ]"
      >
        <LevelBadge 
          :level="currentLevel" 
          :current-xp="currentXpValue"
          :xp-for-next-level="currentXpForNextLevel"
          size="lg" 
          variant="compact"
          :is-level-up="isLevelUp"
          :is-updating="isUpdating"
        />
      </div>
    </template>

    <template #dropdown>
      <div class="w-72 sm:w-80 p-4 space-y-3">
        <!-- Header -->
        <div class="text-center">
          <div class="flex items-center justify-center gap-3 mb-3">
            <LevelBadge :level="currentLevel" size="lg" variant="default" />
            <span :class="['text-2xl font-bold', levelColor]">
              {{ __('Level') }} {{ currentLevel }}
            </span>
          </div>
          <p class="text-sm text-neutral-400">
            {{ currentXpValue }} / {{ currentXpForNextLevel }} {{ __('XP') }}
          </p>
        </div>

        <!-- Barre de progression -->
        <div class="space-y-1">
          <div class="flex justify-between text-xs font-semibold text-neutral-400">
            <span>{{ __('Progress to next level') }}</span>
            <span>{{ Math.round(progressPercentage) }}%</span>
          </div>
          <div class="relative h-2 w-full overflow-hidden rounded-full bg-neutral-800">
            <div
              :class="[
                'h-full transition-all duration-500 ease-out rounded-full',
                currentLevel >= 50 ? 'bg-purple-500' :
                currentLevel >= 30 ? 'bg-yellow-500' :
                currentLevel >= 20 ? 'bg-blue-500' :
                currentLevel >= 10 ? 'bg-green-500' :
                'bg-neutral-500',
              ]"
              :style="{ width: `${progressPercentage}%` }"
            />
          </div>
        </div>

        <!-- Stats -->
        <div class="pt-2 border-t border-neutral-700">
          <div class="flex justify-between text-xs mb-2">
            <span class="text-neutral-400">{{ __('XP needed') }}</span>
            <span :class="['font-bold', levelColor]">
              {{ currentXpForNextLevel - currentXpValue }} {{ __('XP') }}
            </span>
          </div>
        </div>

        <!-- Métriques détaillées -->
        <div v-if="metricsXp" class="pt-2 border-t border-neutral-700">
          <h4 class="text-xs font-semibold text-neutral-400 uppercase tracking-wider mb-3">
            {{ __('XP Breakdown') }}
          </h4>
          <div class="space-y-2">
            <!-- Total score -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="text-xs font-medium text-neutral-300 mb-0.5">{{ metricsXp.score.label }}</div>
                <div class="text-[10px] text-neutral-500">{{ metricsXp.score.value.toLocaleString() }} points</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.score.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Ancienneté -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                  <div class="text-xs font-medium text-neutral-300">{{ metricsXp.seniority.label }}</div>
                  <span v-if="metricsXp.seniority.xp >= metricsXp.seniority.max" class="text-[9px] px-1 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-medium" :title="__('Maximum reached')">MAX</span>
                </div>
                <div class="text-[10px] text-neutral-500 mt-0.5">{{ metricsXp.seniority.value.toLocaleString() }} mois</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.seniority.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Streak -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                  <div class="text-xs font-medium text-neutral-300">{{ metricsXp.streak.label }}</div>
                  <span v-if="metricsXp.streak.xp >= metricsXp.streak.max" class="text-[9px] px-1 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-medium" :title="__('Maximum reached')">MAX</span>
                </div>
                <div class="text-[10px] text-neutral-500 mt-0.5">{{ metricsXp.streak.value.toLocaleString() }} jours</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.streak.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Rooms créées -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                  <div class="text-xs font-medium text-neutral-300">{{ metricsXp.rooms.label }}</div>
                  <span v-if="metricsXp.rooms.xp >= metricsXp.rooms.max" class="text-[9px] px-1 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-medium" :title="__('Maximum reached')">MAX</span>
                </div>
                <div class="text-[10px] text-neutral-500 mt-0.5">{{ metricsXp.rooms.value.toLocaleString() }} rooms</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.rooms.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Playlists créées -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                  <div class="text-xs font-medium text-neutral-300">{{ metricsXp.playlists.label }}</div>
                  <span v-if="metricsXp.playlists.xp >= metricsXp.playlists.max" class="text-[9px] px-1 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-medium" :title="__('Maximum reached')">MAX</span>
                </div>
                <div class="text-[10px] text-neutral-500 mt-0.5">{{ metricsXp.playlists.value.toLocaleString() }} playlists</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.playlists.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Tracks likées -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                  <div class="text-xs font-medium text-neutral-300">{{ metricsXp.likes.label }}</div>
                  <span v-if="metricsXp.likes.xp >= metricsXp.likes.max" class="text-[9px] px-1 py-0.5 rounded bg-yellow-500/20 text-yellow-400 font-medium" :title="__('Maximum reached')">MAX</span>
                </div>
                <div class="text-[10px] text-neutral-500 mt-0.5">{{ metricsXp.likes.value.toLocaleString() }} likes</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.likes.xp.toLocaleString() }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
            
            <!-- Team -->
            <div class="flex items-start justify-between gap-3 py-2 px-2.5 rounded-lg bg-neutral-800/30 hover:bg-neutral-800/50 transition-colors border border-neutral-700/50">
              <div class="flex-1 min-w-0">
                <div class="text-xs font-medium text-neutral-300 mb-0.5">{{ metricsXp.team.label }}</div>
                <div class="text-[10px] text-neutral-500">{{ metricsXp.team.value }}</div>
              </div>
              <div class="flex-shrink-0 text-right">
                <div class="text-sm font-bold text-green-400">{{ metricsXp.team.xp }}</div>
                <div class="text-[10px] text-neutral-500">XP</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lien vers plus d'infos -->
        <div class="pt-2 border-t border-neutral-700">
          <Link
            :href="route('level-system')"
            class="flex items-center gap-2 text-xs text-blue-400 hover:text-blue-300 transition-colors cursor-pointer"
            @click.stop
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="h-4 w-4"
            >
              <circle cx="12" cy="12" r="10" />
              <path d="M12 16v-4M12 8h.01" />
            </svg>
            {{ __('How does it work?') }}
          </Link>
        </div>
      </div>
    </template>
  </dropdown>
</template>
