<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import TextEditor from '@/Components/TextEditor.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  title: '',
  content: '',
})

const store = () => {
  form.post('/admin/pages')
}
</script>
<template>
  <Head title="Create Category" />
  <AdminLayout>
    <Card>
      <template #header>
        <h1 class="text-xl font-bold">
          <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/pages">Pages</Link>
          <span class="font-medium text-indigo-400">/</span> {{ __('Create') }}
        </h1>
      </template>
      <form @submit.prevent="store" id="createForm">
        <div class="p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="w-full pb-8 pr-6 lg:w-1/2" label="Title" />
          <text-editor v-model="form.content" :error="form.errors.content" class="w-full pb-8 pr-6" />
        </div>
      </form>
      <template #footer>
        <loading-button :loading="form.processing" class="btn-primary ml-auto mr-8" type="submit" form="createForm">{{ __('Create') }}</loading-button>
      </template>
    </Card>
  </AdminLayout>
</template>
