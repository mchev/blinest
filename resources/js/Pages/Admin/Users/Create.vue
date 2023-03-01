<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" href="/users">Users</Link>
      <span class="font-medium text-blinest-400">/</span> Create
    </h1>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Name')" />
          <text-input v-model="form.email" :error="form.errors.email" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Email')" />
          <text-input v-model="form.password" :error="form.errors.password" class="w-full pb-8 pr-6 lg:w-1/2" type="password" autocomplete="new-password" :label="__('Password')" />
          <select-input v-model="form.team_id" :error="form.errors.team_id" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Team')">
            <option :value="true">{{ __('Yes') }}</option>
            <option :value="false">{{ __('No') }}</option>
          </select-input>
          <select-input v-model="form.is_admin" :error="form.errors.is_admin" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Admin')">
            <option :value="0">{{ __('No') }}</option>
            <option :value="1">{{ __('Yes') }}</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6 lg:w-1/2" type="file" accept="image/*" :label="__('Photo')" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Create User') }}</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

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
