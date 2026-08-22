<script setup>
import { onMounted, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useDonationGoal } from '@/composables/useDonationGoal'
import DonationMonthlySupporters from '@/Components/Donations/DonationMonthlySupporters.vue'

defineProps({
  compact: {
    type: Boolean,
    default: false,
  },
})

const { goal, donationUrl, monthlySupporters, cardTitle, pitchLine, daysUnit, progressAriaLabel, ctaLabel, translate } = useDonationGoal()

const showConfetti = ref(false)

function triggerConfetti() {
  if (!goal.value?.goal_reached) {
    showConfetti.value = false

    return
  }

  showConfetti.value = true
  window.setTimeout(() => {
    showConfetti.value = false
  }, 4200)
}

onMounted(triggerConfetti)

watch(
  () => goal.value?.goal_reached,
  (reached) => {
    if (reached) {
      triggerConfetti()
    }
  },
)
</script>

<template>
  <div v-if="goal" class="donation-goal-card relative overflow-hidden">
    <div v-if="goal.goal_reached && showConfetti" class="donation-confetti pointer-events-none absolute inset-0 z-20" aria-hidden="true">
      <span v-for="index in 14" :key="`confetti-${index}`" class="donation-confetti__piece" :style="{ '--piece': index }" />
    </div>

    <div class="relative z-10 space-y-3">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 space-y-1">
          <div class="flex flex-wrap items-center gap-2">
            <p class="game-section-kicker">{{ translate('Donation card kicker') }}</p>
            <span v-if="goal.goal_reached" class="donation-goal-badge">
              {{ translate('Donation goal badge') }}
            </span>
          </div>
          <p class="font-semibold leading-snug text-white" :class="compact ? 'text-sm' : 'text-base'">
            {{ cardTitle }}
          </p>
          <p class="leading-snug text-white/70" :class="compact ? 'text-xs' : 'text-sm'">
            {{ pitchLine }}
          </p>
        </div>

        <div class="shrink-0 text-right">
          <p class="text-2xl font-black tabular-nums leading-none" :class="goal.goal_reached ? 'text-emerald-400' : 'text-white'">
            {{ goal.days_remaining }}
          </p>
          <p class="mt-1 max-w-[5.5rem] text-[10px] font-semibold uppercase leading-tight tracking-wide text-white/45">
            {{ daysUnit }}
          </p>
        </div>
      </div>

      <DonationMonthlySupporters :supporters="monthlySupporters" :max-visible="compact ? 6 : 10" :compact="compact" />

      <div class="h-1.5 overflow-hidden rounded-full bg-white/10" role="progressbar" :aria-valuenow="goal.percent" aria-valuemin="0" aria-valuemax="100" :aria-label="progressAriaLabel">
        <div class="h-full rounded-full transition-all duration-500 ease-out" :class="goal.goal_reached ? 'bg-emerald-500' : 'bg-brand-primary'" :style="{ width: `${goal.percent}%` }" />
      </div>

      <div class="flex flex-col gap-2 pt-1">
        <a v-if="donationUrl" :href="donationUrl" target="_blank" rel="noopener noreferrer external nofollow" data-umami-event="Faire un don" class="game-btn-play-primary w-full" :class="compact ? 'min-h-[40px] text-[11px]' : ''">
          {{ ctaLabel }}
        </a>
        <Link :href="route('docs.support')" class="text-center text-xs text-white/50 underline-offset-2 transition-colors hover:text-white hover:underline">
          {{ translate('Donation transparency link') }}
        </Link>
      </div>
    </div>
  </div>
</template>
