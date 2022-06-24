<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import Card from '@/Components/Card'
import TextInput from '@/Components/TextInput'
import FileInput from '@/Components/FileInput'
import SelectInput from '@/Components/SelectInput'
import LoadingButton from '@/Components/LoadingButton'
import TrashedMessage from '@/Components/TrashedMessage'
import TracksManager from '@/Components/TracksManager'

const props = defineProps({
  playlist: Object,
  filters: Object,
  tracks: Object,
})

const form = useForm({
  name: props.playlist.name,
  is_public: props.playlist.is_public,
  photo: null,
})

const update = () => {
  form.put(`/admin/playlists/${props.playlist.id}`, {
    onSuccess: () => form.reset('password', 'photo'),
  })
}

const destroy = () => {
  if (confirm('Are you sure you want to delete this playlist?')) {
    Inertia.delete(`/admin/playlists/${props.playlist.id}`)
  }
}

const restore = () => {
  if (confirm('Are you sure you want to restore this playlist?')) {
    Inertia.put(`/admin/playlists/${props.playlist.id}/restore`)
  }
}
</script>
<template>
  <Head :title="`${form.name}`" />
  <AdminLayout>
    <div class="mb-8 flex max-w-3xl justify-start">
      <h1 class="text-3xl font-bold">
        <Link class="text-blinest-400 hover:text-blinest-600" :href="route('admin.playlists')">{{ __('Playlists') }}</Link>
        <span class="font-medium text-blinest-400">/</span>
        {{ form.name }}
      </h1>
      <img v-if="playlist.photo" class="ml-4 block h-8 w-8 rounded-full" :src="playlist.photo" />
    </div>
    <trashed-message v-if="playlist.deleted_at" class="mb-6" @restore="restore">{{ __('This playlist has been deleted.') }}</trashed-message>
    <card class="max-w-3xl">
      <form @submit.prevent="update">
        <div class="-mb-8 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Title')" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Public')">
            <option :value="1">{{ __('Yes') }}</option>
            <option :value="0">{{ __('No') }}</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center bg-gray-50 px-8 py-4 dark:bg-gray-900">
          <button v-if="!playlist.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete Playlist') }}</button>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" type="submit">{{ __('Update') }}</loading-button>
        </div>
      </form>
    </card>

    <card class="my-4">
      <tracks-manager :playlist="playlist" :filters="filters" :tracks="tracks" />
    </card>
  </AdminLayout>
</template>
