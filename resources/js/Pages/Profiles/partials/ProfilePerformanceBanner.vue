<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  performance: {
    type: Object,
    required: true,
  },
  rank: {
    type: Object,
    required: true,
  },
})

const formatScore = (value) => {
  if (value === null || value === undefined) {
    return '0'
  }

  return new Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(value)
}
</script>

<template>
  <section class="rounded-2xl border border-white/10 bg-brand-deep/60 p-4 sm:p-5">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Performance') }}</h2>
      <Link v-if="rank.position" :href="rank.rankings_url" class="inline-flex items-center gap-2 rounded-lg border border-brand-secondary/30 bg-brand-secondary/10 px-3 py-1.5 text-sm font-semibold text-brand-secondary transition hover:bg-brand-secondary/20">
        <span>#{{ rank.position }}</span>
        <span class="text-white/60">{{ __('Global ELO rank') }}</span>
      </Link>
      <span v-else class="text-sm text-white/45">{{ __('Not ranked yet') }}</span>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
      <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
        <p class="text-xl font-black tabular-nums text-white">{{ performance.rounds_played }}</p>
        <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('Rounds played') }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
        <p class="text-xl font-black tabular-nums text-brand-secondary">{{ formatScore(performance.best_round_score) }}</p>
        <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('Best round') }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
        <p class="text-xl font-black tabular-nums text-white">{{ performance.best_win_streak }}</p>
        <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('Best win streak') }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
        <p class="text-xl font-black tabular-nums text-amber-300">{{ performance.consecutive_days_streak }}</p>
        <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('Day streak') }}</p>
      </div>
      <div class="col-span-2 rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center sm:col-span-1">
        <p class="text-xl font-black tabular-nums text-white">{{ formatScore(performance.week_score) }}</p>
        <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('This week') }}</p>
      </div>
    </div>
  </section>
</template>
