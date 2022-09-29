<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import SocialIcon from '@/Components/SocialIcon.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const login = () => {
  form.post('/login')
}
</script>
<template>
  <AppLayout>
    <Head :title="__('Login')" />

    <div class="mx-auto flex mt-8 max-w-3xl">
      <Card class="w-full lg:w-2/3">
        <template #header>
          <h1 class="text-center text-xl font-bold">{{ __('Login') }}</h1>
        </template>
        <form @submit.prevent="login">
          <div class="p-4">
            <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="__('Email')" type="email" autofocus autocapitalize="off" required/>
            <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="__('Password')" type="password" required />
            <label class="mt-6 flex select-none items-center" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
              <span class="text-sm">{{ __('Remember Me') }}</span>
            </label>
          </div>
          <div class="flex px-10 py-4">
            <Link class="btn-secondary ml-auto mr-2" :href="route('register')">{{ __('Register') }}</Link>
            <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Login') }}</loading-button>
          </div>
        </form>
      </Card>

      <Card class="ml-4 px-10">
        <div class="flex h-full flex-col justify-center">
          <small class="text-center mb-2">{{ __('Login with') }}</small>
          <a :href="route('auth.redirect', 'discord')" class="btn-secondary my-2 flex gap-4 justify-start">
            <social-icon name="discord" class="h-6 w-6" />
            Discord
          </a>

          <a :href="route('auth.redirect', 'instagram')" class="btn-secondary my-2 flex gap-4 justify-start">
            <social-icon name="instagram" class="h-6 w-6" />
            Instagram
          </a>

          <a :href="route('auth.redirect', 'deezer')" class="btn-secondary my-2 flex gap-4 justify-start">
            <social-icon name="deezer" class="h-6 w-6" />
            Deezer
          </a>

          <a :href="route('auth.redirect', 'spotify')" class="btn-secondary my-2 flex gap-4 justify-start">
            <social-icon name="spotify" class="h-6 w-6" />
            Spotify
          </a>

          <a :href="route('auth.redirect', 'facebook')" class="btn-secondary my-2 flex gap-4 justify-start">
            <social-icon name="facebook" class="h-6 w-6" />
            Facebook
          </a>

        </div>
      </Card>
    </div>
  </AppLayout>
</template>
