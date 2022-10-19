<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
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

const user = usePage().props.value.auth.user

const update = () => {
  form.put(`/playlists/${props.playlist.id}`, {
    onSuccess: () => form.reset('password', 'photo'),
  })
}

const destroy = () => {
  if (confirm('Are you sure you want to delete this playlist?')) {
    Inertia.delete(`/playlists/${props.playlist.id}`)
  }
}

const restore = () => {
  if (confirm('Are you sure you want to restore this playlist?')) {
    Inertia.put(`/playlists/${props.playlist.id}/restore`)
  }
}
</script>
<template>
  <Head :title="`${form.name}`" />
  <AppLayout>
    <h1 class="mb-4 text-3xl font-bold">
      <Link :href="route('playlists')">{{ __('Playlists') }}</Link>
      <span class="font-medium"> / </span>
      {{ form.name }}
    </h1>

    <div class="flex flex-wrap gap-4">
      <div v-if="user.id === playlist.user_id" class="flex w-full flex-col xl:w-1/4">
        <Card class="mb-4">
          <template #header>
            <h3 class="text-xl font-bold">Playlist</h3>
          </template>
          <form id="playlistForm" class="p-4" @submit.prevent="update">
            <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Title')" />
            <textarea-input v-model="form.description" :error="form.errors.description" class="mb-4 w-full" :label="__('Description')" />
            <select-input v-model="form.user_id" :error="form.errors.user_id" class="w-full" :label="__('Owner')">
              <option v-for="moderator in playlist.moderators" :value="moderator.id">{{ moderator.name }}</option>
            </select-input>
            <small>{{ __('Transfer the playlist management to a moderator.') }}</small>
          </form>
          <template #footer>
            <button v-if="!playlist.deleted_at" class="text-sm text-red-500 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="playlistForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>
        <ModeratorsManager :playlist="playlist" class="mb-4" />
        <RoomsList :playlist="playlist" />
      </div>
      <div class="flex-1">
        <trashed-message v-if="playlist.deleted_at" class="mb-6" @restore="restore">{{ __('This playlist has been deleted.') }}</trashed-message>

        <TracksManager :playlist="playlist" :filters="filters" :tracks="tracks" :answer_types="answer_types" />
      </div>
    </div>
  </AppLayout>
</template>
