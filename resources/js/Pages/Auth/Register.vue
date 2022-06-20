<template>
  <Head title="Register" />

  <div class="flex items-center justify-center min-h-screen p-6 bg-blinest-800">

    <div class="flex flex-col w-full md:w-2/3 lg:w-1/2">

      <div class="w-full justify-center items-center">
        <logo class="block mx-auto w-full fill-white" height="50" />
      </div>

      <div class="flex">

        <div class="w-full lg:w-2/3">

          <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="register">
            <div class="px-10 py-12">
              <h1 class="text-center text-3xl font-bold">{{ __('Welcome Back!') }}</h1>
              <div class="mt-6 mx-auto w-24 border-b-2" />
              <text-input v-model="form.name" :error="form.errors.name" class="mt-10" :label="__('Name')" autofocus autocapitalize="off" />
              <text-input v-model="form.email" :error="form.errors.email" class="mt-10" :label="__('Email')" type="email" autofocus autocapitalize="off" />
              <text-input v-model="form.password" :error="form.errors.password" class="mt-6" :label="__('Password')" type="password" />
            </div>
            <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
              <loading-button :loading="form.processing" class="btn-blinest ml-auto" type="submit">{{ __('Register') }}</loading-button>
            </div>
          </form>


        </div>

        <div class="ml-4 mt-8 w-full lg:w-1/3 flex flex-col bg-white rounded-lg shadow-xl overflow-hidden p-6">

            <a :href="route('auth.redirect', 'facebook')" class="btn-blinest my-2">
              <social-icon name="facebook" class="w-6 h-6 inline-block"/>
              Facebook
            </a>

            <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
              <social-icon name="discord" class="w-6 h-6 inline-block"/>
              Discord
            </a>

            <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
              <social-icon name="instagram" class="w-6 h-6 inline-block"/>
              Instagram
            </a>

            <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
              <social-icon name="deezer" class="w-6 h-6 inline-block"/>
              Deezer
            </a>

            <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
              <social-icon name="spotify" class="w-6 h-6 inline-block"/>
              Spotify
            </a>

            <a :href="route('auth.redirect', 'discord')" class="btn-blinest my-2">
              <social-icon name="metamask" class="w-6 h-6 inline-block"/>
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
