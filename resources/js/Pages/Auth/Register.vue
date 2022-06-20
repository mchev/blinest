<template>
  <Head title="Register" />

  <div class="flex min-h-screen items-center justify-center bg-blinest-800 p-6">
    <div class="flex w-full flex-col md:w-2/3 lg:w-1/2">
      <div class="w-full items-center justify-center">
        <logo class="mx-auto block w-full fill-white" height="50" />
      </div>

      <div class="flex">
        <div class="w-full lg:w-2/3">
          <form class="mt-8 overflow-hidden rounded-lg bg-white shadow-xl" @submit.prevent="register">
            <div class="px-10 py-12">
              <h1 class="text-center text-3xl font-bold">{{ __('Welcome Back!') }}</h1>
              <div class="mx-auto mt-6 w-24 border-b-2" />
              <text-input v-model="form.name" :error="form.errors.name" class="mt-10" :label="__('Name')" autofocus autocapitalize="off" />
              <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="__('Email')" type="email" autofocus autocapitalize="off" />
              <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="__('Password')" type="password" />
            </div>
            <div class="flex border-t border-gray-100 bg-gray-100 px-10 py-4">
              <loading-button :loading="form.processing" class="btn-blinest ml-auto" type="submit">{{ __('Register') }}</loading-button>
            </div>
          </form>
        </div>

        <div class="ml-4 mt-8 flex w-full flex-col overflow-hidden rounded-lg bg-white p-6 shadow-xl lg:w-1/3">
          <a :href="route('auth.redirect', 'facebook')" class="btn-blinest my-2">
            <social-icon name="facebook" class="inline-block h-6 w-6" />
            Facebook
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
            <social-icon name="discord" class="inline-block h-6 w-6" />
            Discord
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
            <social-icon name="instagram" class="inline-block h-6 w-6" />
            Instagram
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
            <social-icon name="deezer" class="inline-block h-6 w-6" />
            Deezer
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
            <social-icon name="spotify" class="inline-block h-6 w-6" />
            Spotify
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
            <social-icon name="metamask" class="inline-block h-6 w-6" />
            Metamask
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import SocialIcon from '@/Shared/SocialIcon'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
    SocialIcon,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '',
      }),
    }
  },
  methods: {
    register() {
      this.form.post(route('register.store'))
    },
  },
}
</script>
