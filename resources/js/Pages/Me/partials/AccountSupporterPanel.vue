<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import LucideIcon from '@/Components/Icons/LucideIcon.vue'
import { useDonationGoal } from '@/composables/useDonationGoal'

const props = defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const { formatEuros } = useDonationGoal()

const preferencesForm = useForm({
  show_donation_history_on_profile: props.account.show_donation_history_on_profile ?? true,
})

watch(
  () => props.account.show_donation_history_on_profile,
  (value) => {
    preferencesForm.show_donation_history_on_profile = value ?? true
  },
)

const perks = [
  { icon: 'circle-slash', title: 'Donation perk ad free title', description: 'Donation perk ad free desc' },
  { icon: 'crown', title: 'Donation perk crown title', description: 'Donation perk crown desc' },
  { icon: 'smile-plus', title: 'Donation perk reactions title', description: 'Donation perk reactions desc' },
]

const savePreferences = () => {
  preferencesForm.patch(route('users.donation-preferences.update', props.account.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <section class="space-y-4 rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">
    <div>
      <h2 class="text-sm font-bold uppercase tracking-wider text-amber-200/90">{{ __('Me supporter perks title') }}</h2>
      <p class="mt-1 text-sm text-white/55">{{ __('Me supporter perks intro') }}</p>
    </div>

    <div v-if="account.donation_summary" class="grid grid-cols-2 gap-2 sm:grid-cols-3">
      <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center">
        <p class="text-lg font-bold text-white">{{ formatEuros(account.donation_summary.total_cents) }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Total donated') }}</p>
      </div>
      <div class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center">
        <p class="text-lg font-bold text-white">{{ account.donation_summary.months_supported }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Months supported') }}</p>
      </div>
      <div class="col-span-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-center sm:col-span-1">
        <p class="text-lg font-bold text-white">{{ account.donation_summary.donation_count }}</p>
        <p class="text-[10px] uppercase tracking-wide text-white/50">{{ __('Donations') }}</p>
      </div>
    </div>

    <label v-if="account.donation_summary?.donation_count > 0" class="flex cursor-pointer items-start gap-3 rounded-lg border border-white/10 bg-white/5 p-3">
      <input v-model="preferencesForm.show_donation_history_on_profile" type="checkbox" class="mt-0.5 rounded border-white/20 bg-black/30 text-amber-400 focus:ring-amber-400/30" :disabled="preferencesForm.processing" @change="savePreferences" />
      <span class="min-w-0">
        <span class="block text-sm font-semibold text-white">{{ __('Show donation history on profile') }}</span>
        <span class="mt-0.5 block text-xs leading-relaxed text-white/45">{{ __('Show donation history on profile hint') }}</span>
      </span>
    </label>

    <ul v-if="account.is_supporter" class="space-y-2">
      <li v-for="perk in perks" :key="perk.title" class="flex gap-3 rounded-lg border border-white/10 bg-white/5 p-3">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
          <LucideIcon :name="perk.icon" icon-class="h-4 w-4" />
        </span>
        <div class="min-w-0">
          <p class="text-sm font-semibold text-white">{{ __(perk.title) }}</p>
          <p class="mt-0.5 text-xs leading-relaxed text-white/45">{{ __(perk.description) }}</p>
        </div>
      </li>
    </ul>
  </section>
</template>
