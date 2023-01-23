<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  name: null,
  pronoun: null,
  svg_icon: null,
})

const store = () => {
  form.post('/admin/answer_types')
}
</script>
<template>
  <Head title="Create Category" />
  <AdminLayout>
    <Card>
      <template #header>
        <h1 class="text-3xl font-bold">
          <Link class="text-blue-400 hover:text-blue-600" href="/admin/answer_types">Answer Types</Link>
          <span class="font-medium text-blue-400"> /</span> Create
        </h1>
      </template>

      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Name')" />
          <text-input v-model="form.pronoun" :error="form.errors.pronoun" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Pronoun')" />
          <textarea-input v-model="form.svg_icon" :error="form.errors.svg_icon" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('SVG Icon')" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Create Answer Type') }}</loading-button>
        </div>
      </form>
    </Card>
  </AdminLayout>
</template>
