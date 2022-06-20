<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import AdminLayout from '@/Layouts/AdminLayout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

const props = defineProps({
  category: Object,
})

const form = useForm({
  name: props.category.name,
})

const update = () => {
  form.put(`/admin/categories/${props.category.id}`)
}
const destroy = () => {
  if (confirm('Are you sure you want to delete this category?')) {
    Inertia.delete(`/admin/categories/${props.category.id}`)
  }
}
const restore = () => {
  if (confirm('Are you sure you want to restore this category?')) {
    Inertia.put(`/admin/categories/${props.category.id}/restore`)
  }
}
</script>
<template>
  <Head :title="form.name" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/categories">Categories</Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Category</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Category</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
