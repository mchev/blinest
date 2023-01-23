<script setup>
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TrashedMessage from '@/Components/TrashedMessage.vue'
import TracksManager from '@/Components/Playlists/TracksManager.vue'
import ModeratorsManager from '@/Components/Playlists/ModeratorsManager.vue'
import RoomsList from '@/Components/Playlists/RoomsList.vue'

const props = defineProps({
  playlist: Object,
  filters: Object,
  answer_types: Object,
  tracks: Object,
  moderators: Object,
})

const form = useForm(props.playlist)

const update = () => {
  form.put(`/admin/playlists/${props.playlist.id}`, {
    onSuccess: () => form.reset('password', 'photo'),
  })
}

const destroy = () => {
  if (confirm('Are you sure you want to delete this playlist?')) {
    router.delete(`/admin/playlists/${props.playlist.id}`)
  }
}

const restore = () => {
  if (confirm('Are you sure you want to restore this playlist?')) {
    router.put(`/admin/playlists/${props.playlist.id}/restore`)
  }
}
</script>
<template>
  <Head :title="`${form.name}`" />
  <AdminLayout>
    <h1 class="mb-4 text-3xl font-bold text-teal-600">
      <Link :href="route('admin.playlists')">{{ __('Playlists') }}</Link>
      <span class="font-medium"> / </span>
      {{ form.name }}
    </h1>

    <div class="flex flex-wrap gap-4">
      <div class="flex w-full flex-col xl:w-1/4">
        <Card class="mb-4">
          <template #header>
            <h3 class="text-xl font-bold">Playlist</h3>
          </template>
          <form id="playlistForm" class="p-4" @submit.prevent="update">
            <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Title')" />
            <textarea-input v-model="form.description" :error="form.errors.description" class="mb-4 w-full" :label="__('Description')" />
            <select-input v-model="form.is_public" :error="form.errors.is_public" class="mb-4 w-full" :label="__('Public')">
              <option :value="1">{{ __('Yes') }}</option>
              <option :value="0">{{ __('No') }}</option>
            </select-input>
            <select-input v-model="form.user_id" :error="form.errors.user_id" class="w-full" :label="__('Owner')">
              <option :value="playlist.owner.id">{{ playlist.owner.name }}</option>
              <option v-for="moderator in playlist.moderators" :value="moderator.id">{{ moderator.name }}</option>
            </select-input>
            <small>{{ __('You can transfer the playlist management to a moderator.') }}</small>
          </form>
          <template #footer>
            <button v-if="!playlist.deleted_at" class="text-sm text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="playlistForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>
        <ModeratorsManager :playlist="playlist" class="mb-4" />
        <RoomsList :playlist="playlist" />
      </div>
      <div class="flex-1">
        <trashed-message v-if="playlist.deleted_at" class="mb-6" @restore="restore">{{ __('This playlist has been deleted.') }}</trashed-message>

        <tracks-manager :playlist="playlist" :filters="filters" :tracks="tracks" :answer_types="answer_types" />
      </div>
    </div>
  </AdminLayout>
</template>
