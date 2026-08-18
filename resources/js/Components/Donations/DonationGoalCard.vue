<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useDonationGoal } from '@/composables/useDonationGoal'

defineProps({
  compact: {
    type: Boolean,
    default: false,
  },
})

const page = usePage()
const { goal, donationUrl, formatEuros } = useDonationGoal()

const __ = (key, replace = {}) => {
  let translation = page.props.language?.[key] || key

  Object.keys(replace).forEach((placeholder) => {
    translation = translation.replace(`:${placeholder}`, replace[placeholder])
  })

  return translation
}

const effectiveCents = computed(() => goal.value?.effective_cents ?? goal.value?.raised_cents ?? 0)

const statusLine = computed(() => {
  if (!goal.value) {
    return ''
  }

  if (goal.value.goal_reached) {
    return __('Donation status reached')
  }

  return __('Donation status progress', {
    days: goal.value.days_remaining,
  })
})
</script>

<template>
  <div v-if="goal" class="space-y-3">
    <div class="flex items-start justify-between gap-3">
      <div class="min-w-0 space-y-1">
        <p class="game-section-kicker">{{ __('Donation card kicker') }}</p>
        <p class="text-sm leading-snug text-white/75" :class="compact ? 'text-xs' : ''">
          {{ statusLine }}
        </p>
      </div>
      <span class="shrink-0 text-sm font-bold tabular-nums" :class="goal.goal_reached ? 'text-emerald-400' : 'text-white'"> {{ goal.percent }}% </span>
    </div>

    <div class="space-y-1.5">
      <div class="flex items-baseline justify-between gap-2 text-sm tabular-nums">
        <span class="font-semibold text-white">{{ formatEuros(effectiveCents) }}</span>
        <span class="text-white/45">/ {{ formatEuros(goal.goal_cents) }}</span>
      </div>

      <div class="h-1.5 overflow-hidden rounded-full bg-white/10">
        <div class="h-full rounded-full transition-all duration-500 ease-out" :class="goal.goal_reached ? 'bg-emerald-500' : 'bg-brand-primary'" :style="{ width: `${goal.percent}%` }" />
      </div>

      <p v-if="goal.carryover_cents > 0" class="text-xs text-amber-200/80">
        {{ __('Donation carryover included', { amount: formatEuros(goal.carryover_cents) }) }}
      </p>
      <p v-else-if="goal.surplus_cents > 0 && goal.goal_reached" class="text-xs text-emerald-300/80">
        {{ __('Donation surplus provisioned', { amount: formatEuros(goal.surplus_cents) }) }}
      </p>
    </div>

    <div class="flex flex-col gap-2 pt-1">
      <a v-if="donationUrl" :href="donationUrl" target="_blank" rel="noopener noreferrer external nofollow" data-umami-event="Faire un don" class="game-btn-play-primary w-full" :class="compact ? 'min-h-[40px] text-[11px]' : ''">
        {{ __('Donation cta') }}
      </a>
      <Link :href="route('docs.support')" class="text-center text-xs text-white/50 underline-offset-2 transition-colors hover:text-white hover:underline">
        {{ __('Donation transparency link') }}
      </Link>
    </div>
  </div>
</template>
