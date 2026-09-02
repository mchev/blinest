<script setup>
import { computed } from 'vue'
import { useDonationGoal } from '@/composables/useDonationGoal'

const { goal, donationUrl, pitchLine, daysUnit, ctaLabel, progressSegments, translate } = useDonationGoal()

const progressFillPercent = computed(() => Math.min(100, (progressSegments.value.carryover_percent ?? 0) + (progressSegments.value.raised_percent ?? 0)))
</script>

<template>
  <a v-if="goal && donationUrl" :href="donationUrl" target="_blank" rel="noopener noreferrer external nofollow" class="group flex w-full items-center gap-2.5 rounded-lg border border-white/10 bg-brand-deep/80 px-3 py-2 text-left transition hover:border-rose-400/30 hover:bg-brand-deep" :aria-label="translate('Donation mobile bar aria')" :title="pitchLine" data-umami-event="Faire un don">
    <span class="shrink-0 text-[10px] font-bold uppercase tracking-[0.18em] text-rose-300/90">
      {{ goal.goal_reached ? translate('Donation mobile bar reached') : translate('Donation card kicker') }}
    </span>

    <span class="relative h-1 min-w-0 flex-1 overflow-hidden rounded-full bg-white/10">
      <span class="absolute inset-y-0 left-0 flex overflow-hidden rounded-full transition-all duration-500" :style="{ width: `${progressFillPercent}%` }">
        <span
          v-if="progressSegments.carryover_percent > 0"
          class="h-full bg-amber-400/90"
          :style="{ width: progressFillPercent > 0 ? `${(progressSegments.carryover_percent / progressFillPercent) * 100}%` : '0%' }"
        />
        <span
          v-if="progressSegments.raised_percent > 0"
          class="h-full bg-emerald-500"
          :style="{ width: progressFillPercent > 0 ? `${(progressSegments.raised_percent / progressFillPercent) * 100}%` : '0%' }"
        />
      </span>
    </span>

    <span class="shrink-0 whitespace-nowrap text-[11px] font-bold tabular-nums" :class="goal.goal_reached ? 'text-emerald-400' : 'text-white/85'"> {{ goal.days_remaining }} {{ daysUnit }} </span>

    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-white/40 transition group-hover:text-white/70" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
      <path fill-rule="evenodd" d="M4.25 5.5A2.25 2.25 0 016.5 3.25h5.379a.75.75 0 010 1.5H6.5a.75.75 0 00-.75.75v6.75a.75.75 0 00.75.75h6.75a.75.75 0 00.75-.75V9.56a.75.75 0 011.5 0v3.004A2.25 2.25 0 0113.25 15h-6.75A2.25 2.25 0 014.5 12.75V5.5z" clip-rule="evenodd" />
      <path d="M14.25 2.25a.75.75 0 011.5 0v5.5a.75.75 0 01-1.5 0V4.81l-6.22 6.22a.75.75 0 11-1.06-1.06l6.22-6.22h-2.44a.75.75 0 010-1.5h5.5z" />
    </svg>
  </a>
</template>
