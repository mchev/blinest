<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AccountHeader from './partials/AccountHeader.vue'
import AccountSettingsForm from './partials/AccountSettingsForm.vue'
import AccountPasswordForm from './partials/AccountPasswordForm.vue'
import AccountShortcuts from './partials/AccountShortcuts.vue'
import AccountSupporterPanel from './partials/AccountSupporterPanel.vue'
import AccountDangerZone from './partials/AccountDangerZone.vue'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-6 px-4 py-6 sm:py-8">
      <div class="space-y-1">
        <h1 class="text-sm font-bold uppercase tracking-wider text-white/50">{{ __('My account') }}</h1>
        <p class="text-sm text-white/45">{{ __('Me page intro') }}</p>
      </div>

      <AccountHeader :account="account" />

      <div class="grid gap-6 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-7">
          <AccountSettingsForm :account="account" />
          <AccountPasswordForm :account="account" />
          <AccountDangerZone :account="account" />
        </div>

        <aside class="space-y-6 lg:col-span-5">
          <AccountSupporterPanel v-if="account.is_supporter" :account="account" />
          <AccountShortcuts :account="account" />
        </aside>
      </div>
    </div>
  </AppLayout>
</template>
