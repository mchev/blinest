<script setup>
import { useForm } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put(route('users.password.update', props.account.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
  })
}
</script>

<template>
  <Card>
    <template #header>
      <h2 class="text-lg font-bold text-white">{{ account.has_password ? __('Update password') : __('Set password') }}</h2>
    </template>

    <p class="mb-4 text-sm text-white/45">
      {{ account.has_password ? __('Update password help') : __('Set password help') }}
    </p>

    <form id="updatePasswordForm" class="space-y-4" @submit.prevent="updatePassword">
      <text-input v-if="account.has_password" v-model="form.current_password" type="password" :error="form.errors.current_password" class="w-full" :label="__('Current password')" autocomplete="current-password" />
      <text-input v-model="form.password" type="password" :error="form.errors.password" class="w-full" :label="__('New password')" autocomplete="new-password" />
      <text-input v-model="form.password_confirmation" type="password" :error="form.errors.password_confirmation" class="w-full" :label="__('Confirm password')" autocomplete="new-password" />
    </form>

    <template #footer>
      <loading-button :loading="form.processing" class="btn-primary ml-auto" form="updatePasswordForm" type="submit">
        {{ account.has_password ? __('Update password') : __('Set password') }}
      </loading-button>
    </template>
  </Card>
</template>
