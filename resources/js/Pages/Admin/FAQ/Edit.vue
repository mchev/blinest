<script setup>
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  faq: Object,
})

const form = useForm({
  locale: props.faq.locale,
  question: props.faq.question,
  answer: props.faq.answer,
})

const update = () => {
  form.put(`/admin/faqs/${props.faq.id}`)
}

const destroy = () => {
  if (confirm('Are you sure you want to delete this FAQ?')) {
    router.delete(`/admin/faqs/${props.faq.id}`)
  }
}

</script>
<template>
  <Head title="Edit FAQ" />
  <AdminLayout>
    <Card>
      <template #header>
        <h1 class="text-xl font-bold">
          <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/faqs">{{ __('FAQ') }}</Link>
          <span class="font-medium text-indigo-400"> /</span> {{ __('Create') }}
        </h1>
      </template>
      <form @submit.prevent="update" id="editForm">
        <div class="p-8">
          <select-input v-model="form.locale" :error="form.errors.locale" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Locale')" required>
            <option value="fr">FR</option>
            <option value="en">EN</option>
          </select-input>
          <text-input v-model="form.question" :error="form.errors.question" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Question')" required />
          <textarea-input rows="10" v-model="form.answer" :error="form.errors.answer" class="w-full pb-8 pr-6" :label="__('Answer')" required />
        </div>
      </form>
      <template #footer>
        <button class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
        <loading-button :loading="form.processing" class="btn-primary ml-auto mr-8" type="submit" form="editForm">{{ __('Update') }}</loading-button>
      </template>
    </Card>
  </AdminLayout>
</template>