<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" :href="route('admin.rooms')">Rooms</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.name }} {{ form.last_name }}
      </h1>
      <img v-if="room.photo" class="block ml-4 w-8 h-8 rounded-full" :src="room.photo" />
    </div>
    <trashed-message v-if="room.deleted_at" class="mb-6" @restore="restore"> This room has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <select-input v-model="form.owner" :error="form.errors.owner" class="pb-8 pr-6 w-full lg:w-1/2" label="Owner">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!room.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Room</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Room</loading-button>
        </div>
      </form>
    </div>
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
    room: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.room.name,
        owner: this.room.owner,
        photo: null,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(route('admin.rooms', this.room.id), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this room?')) {
        this.$inertia.delete(route('admin.rooms', this.room.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this room?')) {
        this.$inertia.put(route('admin.rooms.restore', this.room.id))
      }
    },
  },
}
</script>
