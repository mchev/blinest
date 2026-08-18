<script setup>
import { Link } from '@inertiajs/vue3'
import { useDonationGoal } from '@/composables/useDonationGoal'

defineProps({
  donations: {
    type: Array,
    default: () => [],
  },
  summary: {
    type: Object,
    default: null,
  },
  showUsers: {
    type: Boolean,
    default: false,
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const { formatEuros } = useDonationGoal()

const formatDate = (isoDate) => {
  if (!isoDate) {
    return ''
  }

  return new Date(isoDate).toLocaleDateString(document.documentElement.lang || 'fr', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  })
}

const formatMonth = (monthKey) => {
  const [year, month] = monthKey.split('-').map(Number)
  const date = new Date(year, month - 1, 1)

  return date.toLocaleDateString(document.documentElement.lang || 'fr', {
    month: 'long',
    year: 'numeric',
  })
}
</script>

<template>
  <div v-if="donations.length" class="space-y-4">
    <div v-if="summary && !compact" class="grid grid-cols-3 gap-2">
      <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center">
        <p class="text-lg font-bold text-white">{{ formatEuros(summary.total_cents) }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Total donated') }}</p>
      </div>
      <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center">
        <p class="text-lg font-bold text-white">{{ summary.donation_count }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Donations') }}</p>
      </div>
      <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center">
        <p class="text-lg font-bold text-white">{{ summary.months_supported }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Months supported') }}</p>
      </div>
    </div>

    <ul class="divide-y divide-white/10 rounded-lg border border-white/10 bg-white/5">
      <li v-for="donation in donations" :key="donation.id" class="flex items-center justify-between gap-3 px-3 py-2.5">
        <div class="flex min-w-0 items-center gap-3">
          <template v-if="showUsers && donation.user">
            <img :src="donation.user.photo" :alt="donation.user.name" class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-white/15" />
            <div class="min-w-0">
              <Link :href="route('user.profile', { user: donation.user.id })" class="block truncate text-sm font-semibold text-white hover:text-rose-200">
                {{ donation.user.name }}
              </Link>
              <p class="text-xs text-white/50">{{ formatDate(donation.donated_at) }} · {{ formatMonth(donation.month_key) }}</p>
            </div>
          </template>
          <template v-else-if="showUsers && donation.anonymous">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 bg-white/5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white/50" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-white/80">{{ __('Anonymous donor') }}</p>
              <p class="text-xs text-white/50">{{ formatDate(donation.donated_at) }}</p>
            </div>
          </template>
          <template v-else>
            <div>
              <p class="text-sm font-semibold text-white">{{ formatEuros(donation.amount_cents) }}</p>
              <p class="text-xs text-white/50">{{ formatDate(donation.donated_at) }} · {{ formatMonth(donation.month_key) }}</p>
            </div>
          </template>
        </div>
        <span v-if="showUsers" class="shrink-0 text-sm font-bold text-rose-200">{{ formatEuros(donation.amount_cents) }}</span>
      </li>
    </ul>
  </div>
  <p v-else class="text-sm text-white/50">{{ __('No donations yet') }}</p>
</template>
