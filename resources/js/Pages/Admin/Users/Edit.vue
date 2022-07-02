<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="mb-8 flex max-w-3xl justify-start">
      <h1 class="text-3xl font-bold">
        <Link class="text-blinest-400 hover:text-blinest-600" :href="route('admin.users')">Users</Link>
        <span class="font-medium text-blinest-400">/</span>
        {{ form.name }}
      </h1>
      <img v-if="user.photo" class="ml-4 block h-8 w-8 rounded-full" :src="user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore"> This user has been deleted. </trashed-message>
    <card>
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
          <text-input v-model="form.email" :error="form.errors.email" class="w-full pb-8 pr-6 lg:w-1/2" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="w-full pb-8 pr-6 lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          <select-input v-model="form.team_id" :error="form.errors.team_id" class="w-full pb-8 pr-6 lg:w-1/2" label="Team">
            <option :value="0">Yes</option>
            <option :value="1">No</option>
          </select-input>
          <select-input v-model="form.is_administrator" :error="form.errors.is_administrator" class="w-full pb-8 pr-6 lg:w-1/2" label="Admin">
            <option :value="0">No</option>
            <option :value="1">Yes</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
          <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete User</button>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" type="submit">Update User</loading-button>
        </div>
      </form>
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TrashedMessage from '@/Components/TrashedMessage.vue'
import Card from '@/Components/Card.vue'

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
        is_administrator: this.user.is_administrator,
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
