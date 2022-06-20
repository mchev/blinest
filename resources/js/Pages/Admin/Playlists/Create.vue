<template>
  <div>
    <Head title="Create Playlist" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" :href="route('admin.playlists')">Playlists</Link>
      <span class="text-blinest-400 font-medium">/</span> Create
    </h1>
    <card>
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <select-input v-model="form.is_public" :error="form.errors.is_public" class="pb-8 pr-6 w-full lg:w-1/2" label="Public">
            <option :value="1">Yes</option>
            <option :value="0">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-blinest" type="submit">Create Playlist</loading-button>
        </div>
      </form>
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import Card from '@/Shared/Card'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    Card,
  },
  layout: AdminLayout,
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
      this.form.post(route('admin.playlists.store'))
    },
  },
}
</script>
