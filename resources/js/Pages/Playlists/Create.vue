<template>
  <div>
    <Head title="Create Playlist" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" :href="route('playlists')">Playlists</Link>
      <span class="font-medium text-blinest-400">/</span> Create
    </h1>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="w-full pb-8 pr-6 lg:w-1/2" label="Public">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Playlist</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import FileInput from '@/Components/FileInput'
import TextInput from '@/Components/TextInput'
import SelectInput from '@/Components/SelectInput'
import LoadingButton from '@/Components/LoadingButton'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        is_public: 0,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post(route('playlists.store'))
    },
  },
}
</script>
