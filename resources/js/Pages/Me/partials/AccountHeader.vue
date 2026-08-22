<script setup>
import { Link } from '@inertiajs/vue3'
import SupporterBadge from '@/Components/Donations/SupporterBadge.vue'
import AccountAvatarEditor from './AccountAvatarEditor.vue'
import { userHasDonorCrown } from '@/utils/donorPerks'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <section class="overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-deep via-brand-midnight to-neutral-900 shadow-lg">
    <div class="flex flex-col gap-6 p-6 sm:flex-row sm:items-start sm:p-8">
      <AccountAvatarEditor :account="account" />

      <div class="min-w-0 flex-1 space-y-2 text-center sm:text-left">
        <h1 class="flex flex-wrap items-center justify-center gap-2 text-2xl font-bold text-white sm:justify-start sm:text-3xl">
          {{ account.name }}
          <SupporterBadge v-if="account.is_supporter && !userHasDonorCrown(account)" size="md" />
        </h1>
        <p class="text-sm text-white/55">{{ account.email }}</p>
        <p class="text-xs text-white/40">{{ __('Member for') }} {{ account.created_at_from_now }} · #{{ account.id }}</p>

        <Link :href="account.links.profile" class="game-btn-secondary mt-4 inline-flex whitespace-nowrap">
          {{ __('View my public profile') }}
        </Link>
      </div>
    </div>
  </section>
</template>
