<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  name: '',
})

const store = () => {
  form.post(route('playlists.store'))
}
</script>
<template>
  <Head title="Create Playlist" />
  <AppLayout>
    <form @submit.prevent="store">
      <div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold tracking-tight">{{ __('Playlists') }}</h2>
          <p class="font-light sm:text-xl">Si tu connais déjà par coeur les playlists publiques ou que tu souhaites créer quelque chose d'unique c'est ici que ça se passe.</p>
          <div class="mt-16 flex justify-center">
            <text-input v-model="form.name" :error="form.errors.name" class="text-xl" :placeholder="__('Playlist name')" required />
          </div>
          <div class="my-8 flex justify-center">
            <loading-button :loading="form.processing" class="btn-primary btn-lg" type="submit">{{ __('Create the playlist') }}</loading-button>
          </div>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
