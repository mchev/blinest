<script setup>
import { router } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import { useTranslate } from '@/composables/useTranslate'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const translate = useTranslate()

const deleteUser = (accountId) => {
  if (!confirm(translate('Me delete account confirm'))) {
    return
  }

  router.delete(route('users.destroy', accountId))
}
</script>

<template>
  <Card class="border-red-500/20">
    <template #header>
      <h2 class="text-lg font-bold text-red-300">{{ __('Danger zone') }}</h2>
    </template>

    <p class="text-sm leading-relaxed text-white/55">
      {{ __('Me delete account warning') }}
    </p>

    <template #footer>
      <button type="button" class="btn-danger ml-auto" @click="deleteUser(account.id)">
        {{ __('Delete my account') }}
      </button>
    </template>
  </Card>
</template>
