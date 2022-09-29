<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Logo from '@/Components/Logo.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import SocialIcon from '@/Components/SocialIcon.vue'

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

    <div class="mx-auto mt-8 flex max-w-3xl">
      <Card class="w-full lg:w-2/3">
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

      <Card class="ml-4 px-10">
        <div class="flex h-full flex-col justify-center">
          <small class="mb-2 text-center">{{ __('Register with') }}</small>
          <a :href="route('auth.redirect', 'discord')" class="btn-secondary my-2 flex justify-start gap-4">
            <social-icon name="discord" class="h-6 w-6" />
            Discord
          </a>

          <a :href="route('auth.redirect', 'instagram')" class="btn-secondary my-2 flex justify-start gap-4">
            <social-icon name="instagram" class="h-6 w-6" />
            Instagram
          </a>

          <a :href="route('auth.redirect', 'deezer')" class="btn-secondary my-2 flex justify-start gap-4">
            <social-icon name="deezer" class="h-6 w-6" />
            Deezer
          </a>

          <a :href="route('auth.redirect', 'spotify')" class="btn-secondary my-2 flex justify-start gap-4">
            <social-icon name="spotify" class="h-6 w-6" />
            Spotify
          </a>

          <a :href="route('auth.redirect', 'facebook')" class="btn-secondary my-2 flex justify-start gap-4">
            <social-icon name="facebook" class="h-6 w-6" />
            Facebook
          </a>
        </div>
      </Card>
    </div>
  </AppLayout>
</template>
