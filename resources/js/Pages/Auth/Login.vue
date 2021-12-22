<template>
  <Head title="Login" />

  <div class="flex items-center justify-center min-h-screen p-6 bg-indigo-800">

    <div class="flex w-1/2">

      <div class="w-full lg:w-1/2">
        <logo class="block mx-auto w-full fill-white" height="50" />

        <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
          <div class="px-10 py-12">
            <h1 class="text-center text-3xl font-bold">Welcome Back!</h1>
            <div class="mt-6 mx-auto w-24 border-b-2" />
            <text-input v-model="form.email" :error="form.errors.email" class="mt-10" label="Email" type="email" autofocus autocapitalize="off" />
            <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Password" type="password" />
            <label class="flex items-center mt-6 select-none" for="remember">
              <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
              <span class="text-sm">Remember Me</span>
            </label>
          </div>
          <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Login</loading-button>
          </div>
        </form>


      </div>

      <div class="ml-4 w-full lg:w-1/2 flex flex-col bg-white rounded-lg shadow-xl overflow-hidden p-6">


          <h2 class="text-center text-3xl font-bold">Tu as le choix!</h2>

          <a :href="route('auth.redirect', 'facebook')" class="btn-indigo my-2"><i class="fab fa-facebook"></i> Se connecter via Facebook</a>

          <a :href="route('auth.redirect', 'discord')" class="btn-indigo my-2"><i class="fab fa-discord"></i> Se connecter via Discord</a>


      </div>

    </div>

  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Shared/Logo'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    Logo,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: 'johndoe@example.com',
        password: 'secret',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post('/login')
    },
  },
}
</script>
