<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-blinest-400 hover:text-blinest-600" :href="route('admin.users')">Users</Link>
        <span class="text-blinest-400 font-medium">/</span>
        {{ form.name }}
      </h1>
      <img v-if="user.photo" class="block ml-4 w-8 h-8 rounded-full" :src="user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> This user has been deleted. </trashed-message>
    <card>
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          <select-input v-model="form.team_id" :error="form.errors.team_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Team">
            <option :value="0">Yes</option>
            <option :value="1">No</option>
          </select-input>
          <select-input v-model="form.is_admin" :error="form.errors.is_admin" class="pb-8 pr-6 w-full lg:w-1/2" label="Admin">
            <option :value="0">No</option>
            <option :value="1">Yes</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete User</button>
          <loading-button :loading="form.processing" class="btn-blinest ml-auto" type="submit">Update User</loading-button>
        </div>
      </form>
    </card>
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
import Card from '@/Shared/Card'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    Card,
  },
  layout: AdminLayout,
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        name: this.user.name,
        email: this.user.email,
        password: '',
        team_id: this.user.team_id,
        is_admin: this.user.is_admin,
        photo: null,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(route('admin.users.update', this.user.id), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(route('admin.users.destroy', this.user.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this user?')) {
        this.$inertia.put(route('admin.users.restore', this.user.id))
      }
    },
  },
}
</script>
