<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="mb-8 flex max-w-3xl justify-start">
      <h1 class="text-3xl font-bold">
        <Link class="text-blinest-400 hover:text-blinest-600" :href="route('playlists')">{{ __('Playlists') }}</Link>
        <span class="font-medium text-blinest-400">/</span>
        {{ form.name }}
      </h1>
      <img v-if="playlist.photo" class="ml-4 block h-12 w-12 rounded-full" :src="playlist.photo" />
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
          <loading-button :loading="form.processing" class="btn-blinest ml-auto" type="submit">{{ __('Update') }}</loading-button>
        </div>
      </form>
    </card>

    <card class="my-4">
      <tracks-manager :playlist="playlist" :filters="filters" :tracks="tracks" />
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Card from '@/Shared/Card'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

import TracksManager from '@/Shared/TracksManager'

export default {
  components: {
    FileInput,
    Card,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    TracksManager,
  },
  layout: Layout,
  props: {
    playlist: Object,
    filters: Object,
    tracks: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.playlist.name,
        is_public: this.playlist.is_public,
        photo: null,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(route('playlists.update', this.playlist), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this playlist?')) {
        this.$inertia.delete(route('playlists.destroy', this.playlist.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this playlist?')) {
        this.$inertia.put(route('playlists.restore', this.playlist.id))
      }
    },
  },
}
</script>
