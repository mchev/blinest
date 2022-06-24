<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import TextInput from '@/Components/TextInput'
import LoadingButton from '@/Components/LoadingButton'

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
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">Create Category</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
