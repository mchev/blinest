import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { userHasDonorPerk } from '@/utils/donorPerks'

export function useAdsDisabled() {
  const page = usePage()

  return computed(() => {
    const globalDisabled = page.props.donation_goal?.ads_disabled ?? false
    const user = page.props.auth?.user

    return globalDisabled || userHasDonorPerk(user, 'ad_free')
  })
}
