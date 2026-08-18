<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import MetricTooltip from '@/Components/MetricTooltip.vue'

const page = usePage()

const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (replaceKey) {
    translation = translation.replace(':' + replaceKey, replace[replaceKey])
  })
  return translation
}

const props = defineProps({
  position: {
    type: Number,
    default: null,
  },
  entry: {
    type: Object,
    default: null,
  },
  sort: {
    type: String,
    default: 'elo',
  },
})

const user = page.props.auth?.user

const formatNumber = (num) => {
  if (!num && num !== 0) {
    return '0'
  }

  return new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 1,
    minimumFractionDigits: 0,
  }).format(num)
}

const positionBadge = () => {
  if (props.position === 1) {
    return '🥇'
  }

  if (props.position === 2) {
    return '🥈'
  }

  if (props.position === 3) {
    return '🥉'
  }

  return `#${props.position}`
}

const metricClass = (metric) => {
  return props.sort === metric ? 'border-yellow-500/50 bg-yellow-500/10' : 'border-neutral-700/60 bg-neutral-900/60'
}
</script>

<template>
  <div v-if="user && position && entry" class="mt-6 rounded-xl border-2 border-yellow-500/30 bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 p-4 shadow-lg sm:mt-8 sm:p-6">
    <div class="flex flex-col gap-4">
      <div class="flex items-center gap-3 sm:gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border-2 border-yellow-500/30 bg-gradient-to-br from-yellow-500/20 to-yellow-600/10 text-xl font-bold text-yellow-400 sm:h-16 sm:w-16 sm:text-2xl">
          {{ positionBadge() }}
        </div>
        <div class="min-w-0 flex-1">
          <h3 class="truncate text-base font-bold text-white sm:text-lg">{{ user.name }}</h3>
          <p class="text-xs text-neutral-400 sm:text-sm">{{ __('Your ranking') }}</p>
        </div>
        <Link :href="route('user.profile', user)" class="shrink-0 rounded-lg bg-yellow-500/20 px-3 py-1.5 text-xs font-medium text-yellow-400 transition-colors hover:bg-yellow-500/30 sm:px-4 sm:py-2 sm:text-sm">
          {{ __('View Profile') }}
        </Link>
      </div>

      <div class="flex flex-wrap gap-2">
        <div class="inline-flex items-baseline gap-1.5 rounded-md border px-2 py-1" :class="metricClass('level')">
          <MetricTooltip :label="__('Level')" :tooltip="__('Your experience level based on XP earned while playing.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
          <span class="text-sm font-bold text-white">{{ entry.stats.level }}</span>
        </div>
        <div class="inline-flex items-baseline gap-1.5 rounded-md border px-2 py-1" :class="metricClass('elo')">
          <MetricTooltip :label="__('ELO')" :tooltip="__('Your competitive skill rating. It can go up or down depending on your results.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
          <span class="text-sm font-bold text-white">{{ formatNumber(entry.stats.elo) }}</span>
        </div>
        <div class="inline-flex items-baseline gap-1.5 rounded-md border px-2 py-1" :class="metricClass('score')">
          <MetricTooltip :label="__('Score')" :tooltip="__('Total points earned in official rooms across all games played.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
          <span class="text-sm font-bold text-white">{{ formatNumber(entry.stats.score) }}</span>
          <span class="text-[10px] uppercase text-neutral-500">{{ __('PTS') }}</span>
        </div>
        <div v-if="entry.stats.week_score > 0" class="inline-flex items-baseline gap-1.5 rounded-md border border-neutral-700/60 bg-neutral-900/60 px-2 py-1" :class="metricClass('week')">
          <MetricTooltip :label="__('Top Week')" :tooltip="__('Points earned in the last 7 days in official rooms. This is a score, not a rank.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
          <span class="text-sm font-bold text-white">{{ formatNumber(entry.stats.week_score) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
