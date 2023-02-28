<script setup>
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  answer_type: Object,
})

const form = useForm({
  name: props.answer_type.name,
  pronoun: props.answer_type.pronoun,
  svg_icon: props.answer_type.svg_icon,
})

const update = () => {
  form.put(`/admin/answer_types/${props.answer_type.id}`)
}
const destroy = () => {
  if (confirm('Are you sure you want to delete this answer_type?')) {
    router.delete(`/admin/answer_types/${props.answer_type.id}`)
  }
}
</script>
<template>
  <Head :title="form.name" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/answer_types">Answer Types</Link>
      <span class="font-medium text-indigo-400">/</span>
      {{ form.name }}
    </h1>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Name')" />
          <text-input v-model="form.pronoun" :error="form.errors.pronoun" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Pronoun')" />
          <textarea-input v-model="form.svg_icon" :error="form.errors.svg_icon" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('SVG Icon')" />
          <span v-html="form.svg_icon" />
        </div>
        <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
          <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete Answer Type') }}</button>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" type="submit">{{ __('Update Answer Type') }}</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
