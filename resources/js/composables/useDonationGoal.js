import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useDonationGoal() {
  const page = usePage()

  const goal = computed(() => page.props.donation_goal ?? null)

  const donationUrl = computed(() => goal.value?.payment_url ?? null)

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
    formatEuros,
  }
}
