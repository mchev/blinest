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

export function useDonationGoal() {
  const page = usePage()
  const translate = useTranslate()

  const goal = computed(() => page.props.donation_goal ?? null)

  const donationUrl = computed(() => goal.value?.payment_url ?? null)

  const recentSupporters = computed(() => goal.value?.recent_supporters ?? [])

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
