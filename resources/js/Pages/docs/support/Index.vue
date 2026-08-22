<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import DonationGoalCard from '@/Components/Donations/DonationGoalCard.vue'
import DonationHistoryList from '@/Components/Donations/DonationHistoryList.vue'
import DonationInfrastructureSection from '@/Components/Donations/DonationInfrastructureSection.vue'
import DonationPerksSection from '@/Components/Donations/DonationPerksSection.vue'
import { useDonationGoal } from '@/composables/useDonationGoal'

defineProps({
  history: {
    type: Array,
    default: () => [],
  },
  recentDonations: {
    type: Array,
    default: () => [],
  },
})

const { formatEuros } = useDonationGoal()

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
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8">
      <div class="space-y-4 text-center">
        <h1 class="text-4xl font-bold text-white">{{ __('Support Blinest') }}</h1>
        <p class="mx-auto max-w-2xl text-lg text-neutral-400">{{ __('Docs support intro') }}</p>
      </div>

      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <DonationGoalCard />
      </div>

      <DonationPerksSection />

      <DonationInfrastructureSection />

      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold text-white">{{ __('How donations work') }}</h2>
        <ul class="list-inside list-disc space-y-2 text-neutral-300">
          <li>{{ __('Donation howto goal') }}</li>
          <li>{{ __('Donation howto ads') }}</li>
          <li>{{ __('Donation howto surplus') }}</li>
          <li>{{ __('Donation howto transparency') }}</li>
        </ul>
      </div>

      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold text-white">{{ __('Recent donations') }}</h2>
        <p class="mb-4 text-sm text-neutral-400">{{ __('Recent donations intro') }}</p>
        <DonationHistoryList :donations="recentDonations" show-users />
      </div>

      <div class="overflow-x-auto rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold text-white">{{ __('Monthly history') }}</h2>
        <table class="w-full min-w-[40rem] border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-700 text-left text-neutral-400">
              <th class="px-3 py-2 font-semibold">{{ __('Month') }}</th>
              <th class="px-3 py-2 font-semibold">{{ __('Raised') }}</th>
              <th class="px-3 py-2 font-semibold">{{ __('Carryover') }}</th>
              <th class="px-3 py-2 font-semibold">{{ __('Effective') }}</th>
              <th class="px-3 py-2 font-semibold">{{ __('Surplus') }}</th>
              <th class="px-3 py-2 font-semibold">{{ __('Status') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in history" :key="row.month_key" class="border-b border-slate-700/50 text-neutral-200">
              <td class="px-3 py-3 capitalize">{{ formatMonth(row.month_key) }}</td>
              <td class="px-3 py-3">{{ formatEuros(row.raised_cents) }}</td>
              <td class="px-3 py-3 text-amber-200/90">{{ row.carryover_cents > 0 ? formatEuros(row.carryover_cents) : '—' }}</td>
              <td class="px-3 py-3 font-medium">{{ formatEuros(row.effective_cents) }}</td>
              <td class="px-3 py-3 text-emerald-300/90">{{ row.surplus_cents > 0 ? formatEuros(row.surplus_cents) : '—' }}</td>
              <td class="px-3 py-3">
                <span v-if="row.goal_reached" class="text-emerald-400">{{ __('Reached') }}</span>
                <span v-else class="text-neutral-400">{{ __('In progress') }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="text-center">
        <Link :href="route('docs.index')" class="text-sm text-neutral-400 underline-offset-2 hover:text-white hover:underline">
          {{ __('Back to docs') }}
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
