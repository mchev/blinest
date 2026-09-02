import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useTranslate } from '@/composables/useTranslate'

export function resolveDonationPitch(goal, translate) {
  if (!goal) {
    return ''
  }

  if (goal.goal_reached) {
    return translate('Donation pitch reached')
  }

  if (goal.percent >= 67) {
    return translate('Donation pitch almost there')
  }

  if (goal.percent >= 34) {
    return translate('Donation pitch halfway')
  }

  return translate('Donation pitch default')
}

export function resolveDonationDaysUnit(goal, translate) {
  if (!goal) {
    return ''
  }

  if (goal.goal_reached) {
    return translate('Donation days unit reached')
  }

  return translate('Donation days unit')
}

export function resolveProgressSegments(goal) {
  if (!goal) {
    return { carryover_percent: 0, raised_percent: 0, surplus_percent: 0 }
  }

  if (goal.progress_segments) {
    return goal.progress_segments
  }

  const goalCents = goal.goal_cents ?? 0

  if (goalCents <= 0) {
    return { carryover_percent: 0, raised_percent: 0, surplus_percent: 0 }
  }

  const carryoverCents = goal.carryover_cents ?? 0
  const raisedCents = goal.raised_cents ?? 0
  const effectiveCents = goal.effective_cents ?? carryoverCents + raisedCents
  const carryoverTowardGoal = Math.min(carryoverCents, goalCents)
  const raisedTowardGoal = Math.min(raisedCents, Math.max(0, goalCents - carryoverCents))
  const surplusCents = Math.max(0, effectiveCents - goalCents)

  return {
    carryover_percent: Math.round((carryoverTowardGoal / goalCents) * 100),
    raised_percent: Math.round((raisedTowardGoal / goalCents) * 100),
    surplus_percent: Math.min(25, Math.round((surplusCents / goalCents) * 100)),
  }
}

export function useDonationGoal() {
  const page = usePage()
  const translate = useTranslate()

  const goal = computed(() => page.props.donation_goal ?? null)

  const donationUrl = computed(() => goal.value?.payment_url ?? null)

  const monthlySupporters = computed(() => goal.value?.monthly_supporters ?? [])

  const postGoalSupporters = computed(() => goal.value?.post_goal_supporters ?? [])

  const progressSegments = computed(() => resolveProgressSegments(goal.value))

  const hasCarryover = computed(() => (goal.value?.carryover_cents ?? 0) > 0)

  const hasSurplus = computed(() => (goal.value?.surplus_cents ?? 0) > 0)

  const recentSupporters = computed(() => monthlySupporters.value)

  const pitchLine = computed(() => resolveDonationPitch(goal.value, translate))

  const daysUnit = computed(() => resolveDonationDaysUnit(goal.value, translate))

  const progressAriaLabel = computed(() => translate('Donation progress aria', { percent: goal.value?.percent ?? 0 }))

  const cardTitle = computed(() => (goal.value?.goal_reached ? translate('Donation card title reached') : translate('Donation card title')))

  const ctaLabel = computed(() => (goal.value?.goal_reached ? translate('Donation cta reached') : translate('Donation cta')))

  const formatEuros = (cents) => {
    const amount = (cents ?? 0) / 100

    return new Intl.NumberFormat(page.props.locale ?? 'fr', {
      style: 'currency',
      currency: 'EUR',
      maximumFractionDigits: 0,
    }).format(amount)
  }

  return {
    goal,
    donationUrl,
    monthlySupporters,
    postGoalSupporters,
    progressSegments,
    hasCarryover,
    hasSurplus,
    recentSupporters,
    pitchLine,
    daysUnit,
    progressAriaLabel,
    cardTitle,
    ctaLabel,
    formatEuros,
    translate,
  }
}
