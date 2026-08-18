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
  items: {
    type: Object,
    required: true,
  },
  sort: {
    type: String,
    default: 'elo',
  },
})

const getMedalEmoji = (index, currentPage) => {
  if (currentPage !== 1) {
    return null
  }

  if (index === 0) {
    return '🥇'
  }

  if (index === 1) {
    return '🥈'
  }

  if (index === 2) {
    return '🥉'
  }

  return null
}

const getRealIndex = (index, currentPage, perPage) => {
  return (currentPage - 1) * perPage + index + 1
}

const formatNumber = (num) => {
  if (!num && num !== 0) {
    return '0'
  }

  return new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 1,
    minimumFractionDigits: 0,
  }).format(num)
}

const formatSeconds = (seconds) => {
  if (seconds === null || seconds === undefined) {
    return '—'
  }

  return `${formatNumber(seconds)}s`
}

const rowClass = (index, currentPage) => {
  if (currentPage === 1 && index === 0) {
    return 'border-yellow-500/30 bg-yellow-500/5'
  }

  if (currentPage === 1 && index === 1) {
    return 'border-gray-400/25 bg-gray-400/5'
  }

  if (currentPage === 1 && index === 2) {
    return 'border-amber-600/25 bg-amber-600/5'
  }

  return 'border-neutral-800 bg-neutral-900/40'
}

const metricClass = (metric) => {
  return props.sort === metric ? 'border-yellow-500/50 bg-yellow-500/10' : 'border-neutral-700/60 bg-neutral-800/50'
}
</script>

<template>
  <div class="space-y-2.5 sm:space-y-3">
    <article
      v-for="(entry, index) in items.data"
      :key="entry.user.id"
      class="rounded-xl border p-3 shadow-sm transition-colors hover:border-neutral-700 sm:p-4"
      :class="rowClass(index, items.current_page)"
    >
      <div class="flex flex-col gap-3">
        <div class="flex items-start gap-3">
          <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-neutral-800 text-sm font-bold sm:h-11 sm:w-11">
            <span v-if="getMedalEmoji(index, items.current_page)" class="text-lg">{{ getMedalEmoji(index, items.current_page) }}</span>
            <span v-else class="text-neutral-400">#{{ getRealIndex(index, items.current_page, items.per_page) }}</span>
          </div>

          <Link v-if="entry.user?.id && !entry.user?.is_guest" :href="route('user.profile', { user: entry.user.id })" class="shrink-0">
            <img :src="entry.user.photo" :alt="entry.user.name" class="h-10 w-10 rounded-full border-2 border-neutral-600 object-cover sm:h-11 sm:w-11" loading="lazy" />
          </Link>
          <img v-else :src="entry.user.photo" :alt="entry.user.name" class="h-10 w-10 shrink-0 rounded-full border-2 border-neutral-600 object-cover sm:h-11 sm:w-11" loading="lazy" />

          <div class="min-w-0 flex-1">
            <Link v-if="entry.user?.id && !entry.user?.is_guest" :href="route('user.profile', { user: entry.user.id })" class="block truncate text-base font-bold text-white hover:text-yellow-400">
              {{ entry.user.name }}
            </Link>
            <span v-else class="block truncate text-base font-bold text-neutral-400">{{ entry.user?.name || __('Deleted user') }}</span>

            <div class="mt-2 flex flex-wrap gap-2">
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
            </div>
          </div>
        </div>

        <dl class="grid grid-cols-2 gap-2 border-t border-neutral-800 pt-2 text-xs sm:grid-cols-3 lg:grid-cols-5">
          <div class="rounded-md border px-2 py-1.5" :class="metricClass('week')">
            <dt class="text-[10px] uppercase tracking-wide text-neutral-500">
              <MetricTooltip :label="__('Top Week')" :tooltip="__('Points earned in the last 7 days in official rooms. This is a score, not a rank.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
            </dt>
            <dd class="font-semibold text-white">{{ formatNumber(entry.stats.week_score) }}</dd>
          </div>
          <div class="rounded-md border px-2 py-1.5" :class="metricClass('avg_time')">
            <dt class="text-[10px] uppercase tracking-wide text-neutral-500">
              <MetricTooltip :label="__('Avg. response time')" :tooltip="__('Average time to complete an excerpt (all answers found), in seconds. Lower is faster.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
            </dt>
            <dd class="font-semibold text-white">{{ formatSeconds(entry.stats.avg_response_time) }}</dd>
          </div>
          <div class="rounded-md border px-2 py-1.5" :class="metricClass('best_round')">
            <dt class="text-[10px] uppercase tracking-wide text-neutral-500">
              <MetricTooltip :label="__('Best round')" :tooltip="__('Your highest score achieved in a single round.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
            </dt>
            <dd class="font-semibold text-white">{{ entry.stats.best_round_score != null ? formatNumber(entry.stats.best_round_score) : '—' }}</dd>
          </div>
          <div class="rounded-md border border-neutral-700/60 bg-neutral-800/50 px-2 py-1.5">
            <dt class="text-[10px] uppercase tracking-wide text-neutral-500">
              <MetricTooltip :label="__('Best streak')" :tooltip="__('Your longest streak of consecutive round wins in the same room.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
            </dt>
            <dd class="font-semibold text-white">{{ entry.stats.best_win_streak || '—' }}</dd>
          </div>
          <div class="rounded-md border border-neutral-700/60 bg-neutral-800/50 px-2 py-1.5">
            <dt class="text-[10px] uppercase tracking-wide text-neutral-500">
              <MetricTooltip :label="__('Rounds played')" :tooltip="__('Number of complete rounds played in official rooms.')" label-class="text-[10px] font-medium uppercase tracking-wide text-neutral-500" />
            </dt>
            <dd class="font-semibold text-white">{{ entry.stats.rounds_played || 0 }}</dd>
          </div>
        </dl>
      </div>
    </article>
  </div>
</template>
