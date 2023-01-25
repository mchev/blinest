<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: String,
  token: String,
})
const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})
const submit = () => {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <AppLayout>
    <Head :title="__('Reset Password')" />

    <Card class="mx-auto max-w-xl">
      <template #header>
        {{ __('Reset Password') }}
      </template>

      <form @submit.prevent="submit">
        <div>
          <TextInput type="email" label="Email" v-model="form.email" :error="form.errors.email" required autofocus />
          <TextInput type="password" :label="__('Password')" v-model="form.password" :error="form.errors.password" required autofocus />
          <TextInput type="password" :label="__('Confirm Password')" v-model="form.password_confirmation" :error="form.errors.password_confirmation" required autofocus />
        </div>

        <div class="mt-6 mb-4 flex items-center justify-end">
          <LoadingButton :class="{ 'opacity-25': form.processing }" class="btn-primary" :disabled="form.processing" :loading="form.processing">
            {{ __('Reset Password') }}
          </LoadingButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>
