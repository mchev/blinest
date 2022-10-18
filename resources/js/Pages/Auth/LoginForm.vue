<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Socialite from './Socialite.vue'

const form = useForm({
  email: '',
  password: '',
  remember: true,
})

const login = () => {
  form.post('/login')
}
</script>
<template>
    <div class="mx-auto flex flex-wrap gap-4 lg:max-w-3xl justify-center">
      <Card class="flex flex-grow">
        <template #header>
          <h1 class="text-center text-xl font-bold">{{ __('Login') }}</h1>
        </template>
        <form @submit.prevent="login">
          <div class="p-4">
            <text-input v-model="form.email" :error="form.errors.email" class="mt-6" :label="__('Email')" type="email" autofocus autocapitalize="off" required/>
            <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="__('Password')" type="password" required />
<!--             <label class="mt-6 flex select-none items-center" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
              <span class="text-sm">{{ __('Remember Me') }}</span>
            </label>
 -->          </div>
          <div class="flex px-6 py-4">
            <Link class="btn-secondary ml-auto mr-2" :href="route('register')">{{ __('Register') }}</Link>
            <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Login') }}</loading-button>
          </div>
          <div class="flex px-6 mb-2">
            <Link class="text-sm ml-auto underline" :href="route('password.request')">{{ __('Forgot your password?') }}</Link>
          </div>
        </form>
      </Card>

      <Socialite />
    </div>
</template>
