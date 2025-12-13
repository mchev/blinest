<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
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
  totalXp: {
    type: Number,
    default: 0,
  },
  levelMetrics: {
    type: Object,
    default: null,
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const page = usePage()

const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

const progressPercentage = computed(() => {
  if (props.xpForNextLevel === 0) return 100
  return Math.min((props.currentXp / props.xpForNextLevel) * 100, 100)
})

const levelColor = computed(() => {
  if (props.level >= 50) return 'text-purple-500'
  if (props.level >= 30) return 'text-yellow-500'
  if (props.level >= 20) return 'text-blue-500'
  if (props.level >= 10) return 'text-green-500'
  return 'text-neutral-400'
})

const levelBgColor = computed(() => {
  if (props.level >= 50) return 'bg-purple-500'
  if (props.level >= 30) return 'bg-yellow-500'
  if (props.level >= 20) return 'bg-blue-500'
  if (props.level >= 10) return 'bg-green-500'
  return 'bg-neutral-500'
})

const metricsXp = computed(() => {
  if (!props.levelMetrics) return null

  const metrics = props.levelMetrics

  return {
    score: {
      label: __('Total score'),
      value: metrics.score_public_rooms,
      xp: metrics.score_public_rooms,
      max: null,
    },
    seniority: {
      label: __('Seniority'),
      value: Math.round(metrics.seniority_months),
      xp: Math.min(Math.round(metrics.seniority_months) * 50, 600),
      max: 600,
    },
    streak: {
      label: __('Consecutive days streak'),
      value: metrics.consecutive_days_streak,
      xp: Math.min(metrics.consecutive_days_streak * 10, 300),
      max: 300,
    },
    rooms: {
      label: __('Rooms created'),
      value: metrics.rooms_created_count,
      xp: Math.min(metrics.rooms_created_count * 100, 1000),
      max: 1000,
    },
    playlists: {
      label: __('Playlists created'),
      value: metrics.playlists_created_count,
      xp: Math.min(metrics.playlists_created_count * 20, 2000),
      max: 2000,
    },
    likes: {
      label: __('Tracks liked'),
      value: metrics.tracks_liked_count,
      xp: Math.min(metrics.tracks_liked_count * 5, 1000),
      max: 1000,
    },
    team: {
      label: __('Team'),
      value: metrics.has_team ? __('Yes') : __('No'),
      xp: metrics.has_team ? 200 : 0,
      max: 200,
    },
  }
})
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <LevelBadge :level="level" :size="compact ? 'sm' : 'md'" variant="default" :clickable="false" />
        <div>
          <h3 :class="['text-lg font-bold', levelColor]">
            {{ __('Level') }} {{ level }}
          </h3>
          <p class="text-xs text-neutral-400">
            {{ __('Total XP') }}: <span :class="['font-semibold', levelColor]">{{ totalXp.toLocaleString('fr-FR') }} {{ __('XP') }}</span>
          </p>
        </div>
      </div>
    </div>

    <div class="rounded-lg border border-neutral-700 bg-neutral-800/50 p-4">
      <div class="mb-3">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium text-neutral-400">{{ __('XP in this level') }}</span>
          <span :class="['text-xs font-bold', levelColor]">
            {{ currentXp }} / {{ xpForNextLevel }} {{ __('XP') }}
          </span>
        </div>
        <div class="relative h-2 w-full overflow-hidden rounded-full bg-neutral-700">
          <div
            :class="['h-full transition-all duration-500 ease-out rounded-full', levelBgColor]"
            :style="{ width: `${progressPercentage}%` }"
          />
        </div>
        <div class="flex justify-between text-xs text-neutral-500 mt-1">
          <span>{{ __('Progress to next level') }}</span>
          <span>{{ Math.round(progressPercentage) }}%</span>
        </div>
      </div>

      <div class="pt-3 border-t border-neutral-700">
        <div class="flex justify-between items-center">
          <span class="text-xs text-neutral-400">{{ __('XP needed') }}</span>
          <span :class="['text-sm font-bold', levelColor]">
            {{ xpForNextLevel - currentXp }} {{ __('XP') }}
          </span>
        </div>
      </div>
    </div>

    <div v-if="metricsXp && !compact" class="rounded-lg border border-neutral-700 bg-neutral-800/50 p-4">
      <h4 class="text-sm font-bold text-neutral-300 mb-3">
        {{ __('XP Breakdown') }}
      </h4>
      <div class="space-y-2">
        <div
          v-for="(metric, key) in metricsXp"
          :key="key"
          class="flex items-center justify-between py-2 px-3 bg-neutral-900/50 rounded-lg"
        >
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
              <span class="text-xs font-semibold text-neutral-300 truncate">{{ metric.label }}</span>
              <span
                v-if="metric.max && metric.xp >= metric.max"
                class="text-xs bg-yellow-500/20 text-yellow-400 px-1.5 py-0.5 rounded font-semibold flex-shrink-0"
              >
                {{ __('MAX') }}
              </span>
            </div>
            <p class="text-xs text-neutral-500 truncate">
              {{ metric.value }} {{ key === 'seniority' ? __('months') : key === 'streak' ? __('days') : key === 'team' ? '' : key === 'score' ? __('points') : '' }}
            </p>
          </div>
          <div class="text-right ml-2 flex-shrink-0">
            <p :class="['text-sm font-bold', metric.xp > 0 ? levelColor : 'text-neutral-500']">
              {{ metric.xp }}
            </p>
            <p class="text-xs text-neutral-500">{{ __('XP') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

