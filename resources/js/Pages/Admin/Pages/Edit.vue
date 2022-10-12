<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import TextEditor from '@/Components/TextEditor.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  page: Object,
})

const form = useForm({
  title: props.page.title,
  content: props.page.content,
})

const update = () => {
  form.put(`/admin/pages/${props.page.id}`)
}
const destroy = () => {
  if (confirm('Are you sure you want to delete this page?')) {
    Inertia.delete(`/admin/pages/${props.page.id}`)
  }
}
</script>
<template>
  <Head title="Create Category" />
  <AdminLayout>
    <Card>
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="text-xl font-bold">
            <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/pages">Pages</Link>
            <span class="font-medium text-indigo-400">/</span> {{ __('Update') }}
          </h1>
          <small class="text-xs text-neutral-500">{{ __('Last revision') }} : {{ page.date }}</small>
        </div>
      </template>
      <form @submit.prevent="update" id="editForm">
        <Link v-if="page.url" :href="page.url" class="underline px-8">{{ page.url }}</Link>
        <div class="p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="w-full pb-8 pr-6 lg:w-1/2" label="Title" />
          <text-editor v-model="form.content" :error="form.errors.content" class="w-full pb-8 pr-6" />
        </div>
      </form>
      <template #footer>
        <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
        <loading-button :loading="form.processing" class="btn-primary ml-auto mr-8" type="submit" form="editForm">{{ __('Update') }}</loading-button>
      </template>
    </Card>
  </AdminLayout>
</template>
