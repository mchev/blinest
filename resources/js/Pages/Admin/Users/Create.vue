<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" href="/users">Users</Link>
      <span class="text-blinest-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
          <select-input v-model="form.team_id" :error="form.errors.team_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Team">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <select-input v-model="form.is_admin" :error="form.errors.is_admin" class="pb-8 pr-6 w-full lg:w-1/2" label="Admin">
            <option :value="0">No</option>
            <option :value="1">Yes</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-blinest" type="submit">Create User</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: AdminLayout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        last_name: '',
        email: '',
        password: '',
        team_id: null,
        is_admin: false,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post(route('admin.users.store'))
    },
  },
}
</script>
