<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  name: '',
})

const store = () => {
  form.post('/playlists')
}
</script>
<template>
  <Head title="Create Playlist" />
  <AppLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" :href="route('playlists')">{{ __('Playlists') }}</Link>
      <span class="font-medium text-blinest-400">/</span> {{ __('Create') }}
    </h1>
    <Card>
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <TextInput v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <LoadingButton :loading="form.processing" class="btn-primary" type="submit">{{ __('Create Playlist') }}</LoadingButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>
