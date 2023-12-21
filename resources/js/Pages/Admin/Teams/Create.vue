<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const form = useForm({
  name: '',
})

const store = () => {
  form.post(route('admin.teams.store'))
}
</script>
<template>
  <Head title="Create Team" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-gray-400 hover:text-blinest-600" :href="route('admin.teams')">{{ __('Teams') }}</Link>
      <span class="font-medium text-gray-400">/</span> Create
    </h1>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8" :label="__('Name')" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Create Team') }}</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
