<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<template>
  <AppLayout>
    <Head :title="__('Forgot your password?')" />

    <Card class="mx-auto max-w-xl">
      <template #header>
        {{ __('Forgot your password?') }}
      </template>

      <p class="mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
      </p>

      <form @submit.prevent="submit">
        <div>
          <TextInput type="email" label="Email" v-model="form.email" :error="form.errors.email" required autofocus />
        </div>

        <div class="mt-6 mb-4 flex items-center justify-end">
          <LoadingButton :class="{ 'opacity-25': form.processing }" class="btn-primary" :disabled="form.processing" :loading="form.processing">
            {{ __('Email Password Reset Link') }}
          </LoadingButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>
