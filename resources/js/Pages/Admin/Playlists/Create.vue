<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  name: '',
  is_public: 0,
})

const store = () => {
  form.post('/admin/playlists')
}
</script>
<template>
  <Head title="Create Playlist" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-gray-400 hover:text-blinest-600" :href="route('admin.playlists')">{{ __('Playlists') }}</Link>
      <span class="font-medium text-gray-400">/</span> Create
    </h1>
    <card>
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Name')" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Public')">
            <option :value="1">{{ __('Yes') }}</option>
            <option :value="0">{{ __('No') }}</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Create the playlist') }}</loading-button>
        </div>
      </form>
    </card>
  </AdminLayout>
</template>
