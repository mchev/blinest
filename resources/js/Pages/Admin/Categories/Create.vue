<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

const form = useForm({
  name: null,
})

const store = () => {
  form.post('/admin/categories')
}
</script>
<template>
  <Head title="Create Category" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/categories">Categories</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create Category</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
