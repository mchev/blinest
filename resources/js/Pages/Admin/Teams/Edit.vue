<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="mb-8 flex max-w-3xl justify-start">
      <h1 class="text-3xl font-bold">
        <Link class="text-blinest-400 hover:text-blinest-600" :href="route('admin.teams')">Teams</Link>
        <span class="font-medium text-blinest-400">/</span>
        {{ form.name }} {{ form.last_name }}
      </h1>
      <img v-if="team.photo" class="ml-4 block h-8 w-8 rounded-full" :src="team.photo" />
    </div>
    <trashed-message v-if="team.deleted_at" class="mb-6" @restore="restore"> This team has been deleted. </trashed-message>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
          <select-input v-model="form.owner" :error="form.errors.owner" class="w-full pb-8 pr-6 lg:w-1/2" label="Owner">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
          <button v-if="!team.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Team</button>
          <loading-button :loading="form.processing" class="btn-blinest ml-auto" type="submit">Update Team</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: AdminLayout,
  props: {
    team: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.team.name,
        owner: this.team.owner,
        photo: null,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(route('admin.teams.update', this.team.id), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this team?')) {
        this.$inertia.delete(route('admin.teams.destroy', this.team.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this team?')) {
        this.$inertia.put(route('admin.teams.restore', this.team.id))
      }
    },
  },
}
</script>
