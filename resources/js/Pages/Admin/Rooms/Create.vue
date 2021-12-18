<template>
  <div>
    <Head title="Create Room" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" :href="route('admin.rooms')">Rooms</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full" label="Name" />
          <textarea-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full" label="Description" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          <select-input v-model="form.playlist_id" :error="form.errors.playlist_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Playlist">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          
          <vue-select
            v-model="form.playlist_id"
            :error="form.errors.playlist_id"
            :options="playlist_options"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :filterable="false"
            @search="(query) => (search = query)">
          </vue-select>

          <text-input v-model="form.tracks_by_game" :error="form.errors.tracks_by_game" type="number" step="1" min="1" max="100" class="pb-8 pr-6 w-full lg:w-1/3" label="Tracks by game" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="pb-8 pr-6 w-full lg:w-1/3" label="Public">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <select-input v-model="form.is_pro" :error="form.errors.is_pro" class="pb-8 pr-6 w-full lg:w-1/3" label="Pro">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <select-input v-model="form.is_random" :error="form.errors.is_random" class="pb-8 pr-6 w-full lg:w-1/3" label="Random">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <select-input v-model="form.is_active" :error="form.errors.is_active" class="pb-8 pr-6 w-full lg:w-1/3" label="Active">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <select-input v-model="form.is_chat_active" :error="form.errors.is_chat_active" class="pb-8 pr-6 w-full lg:w-1/3" label="Chatbox">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
          <text-input v-model="form.discord_webhook_url" type="url" :error="form.errors.discord_webhook_url" class="pb-8 pr-6 w-full lg:w-1/2" label="Discord Webhook" />
          <text-input v-model="form.color" type="color" :error="form.errors.color" class="pb-8 pr-6 w-full lg:w-1/2" label="Color" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Room</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Shared/AdminLayout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import VueSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    VueSelect,
  },
  layout: AdminLayout,
  remember: 'form',
  data() {
    return {
      search_playlist: '',
      playlist_options: [],
      form: this.$inertia.form({
        name: '',
        description: '',
        playlist_id: null,
        is_public: 0,
        is_pro: 0,
        is_random: 1,
        is_active: 1,
        is_chat_active: 1,
        discord_webhook_url: '',
        color: '',
        password: '',
        tracks_by_game: 15,
        photo: null,
      }),
    }
  },

  watch: {
    search_playlist: {
      handler: throttle(function () {
        this.$inertia.get(route('admin.playlists'), pickBy(this.search_playlist), { preserveState: true })
      }, 150),
    },
  },

  methods: {
    store() {
      this.form.post(route('admin.rooms.store'))
    },
  },
}
</script>
