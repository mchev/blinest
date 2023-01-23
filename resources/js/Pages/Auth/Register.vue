<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Logo from '@/Components/Logo.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import Socialite from './Socialite.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})
const register = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>
<template>
  <AppLayout>
    <Head :title="__('Login')" />

    <div class="mx-auto flex flex-wrap gap-4 lg:max-w-3xl justify-center">
      <Card class="flex flex-grow">
        <template #header>
          <h1 class="text-center text-xl font-bold">{{ __('Register') }}</h1>
        </template>
        <form @submit.prevent="register">
          <div class="p-4">
            <text-input v-model="form.name" :error="form.errors.name" :label="__('Name') + ' / ' + __('Nickname')" autofocus autocapitalize="off" required />
            <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="__('Email')" type="email" autofocus autocapitalize="off" required />
            <text-input v-model="form.password" :error="form.errors.password" class="mt-10" :label="__('Password')" type="password" required />
            <text-input v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="mt-10" :label="__('Confirm password')" type="password" required autocomplete="new-password" />
          </div>
          <div class="flex px-10 py-4">
            <loading-button :loading="form.processing" class="btn-primary ml-auto" type="submit">{{ __('Register') }}</loading-button>
          </div>
        </form>
      </Card>

      <Socialite />

    </div>
  </AppLayout>
</template>
