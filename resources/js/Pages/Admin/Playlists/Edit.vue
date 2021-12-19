<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="route('admin.playlists')">Playlists</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.name }}
      </h1>
      <img v-if="playlist.photo" class="block ml-4 w-8 h-8 rounded-full" :src="playlist.photo" />
    </div>
    <trashed-message v-if="playlist.deleted_at" class="mb-6" @restore="restore"> This playlist has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="First name" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="pb-8 pr-6 w-full lg:w-1/2" label="Public">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!playlist.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Playlist</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Playlist</loading-button>
        </div>
      </form>
    </div>

    <tracks-manager class="mt-4" :playlist="playlist"/>

  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Shared/AdminLayout'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

import TracksManager from '@/Shared/TracksManager'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    TracksManager
  },
  layout: AdminLayout,
  props: {
    playlist: Object,
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
      this.form.post(route('admin.playlists.update', this.playlist), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this playlist?')) {
        this.$inertia.delete(route('admin.playlists.destroy', this.playlist.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this playlist?')) {
        this.$inertia.put(route('admin.playlists.restore', this.playlist.id))
      }
    },
  },
}
</script>
