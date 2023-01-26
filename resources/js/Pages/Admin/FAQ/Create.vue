<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  locale: 'fr',
  question: '',
  answer: '',
})

const store = () => {
  form.post('/admin/faqs')
}
</script>
<template>
  <Head title="Create FAQ" />
  <AdminLayout>
    <Card>
      <template #header>
        <h1 class="text-xl font-bold">
          <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/faqs">FAQ</Link>
          <span class="font-medium text-indigo-400"> /</span> {{ __('Create') }}
        </h1>
      </template>
      <form @submit.prevent="store" id="createForm">
        <div class="p-8">
          <select-input v-model="form.locale" :error="form.errors.locale" class="w-full pb-8 pr-6 lg:w-1/2" label="Locale" required>
            <option value="fr">FR</option>
            <option value="en">EN</option>
          </select-input>
          <text-input v-model="form.question" :error="form.errors.question" class="w-full pb-8 pr-6 lg:w-1/2" label="Question" required />
          <textarea-input rows="10" v-model="form.answer" :error="form.errors.answer" class="w-full pb-8 pr-6" label="Réponse" required />
        </div>
      </form>
      <template #footer>
        <loading-button :loading="form.processing" class="btn-primary ml-auto mr-8" type="submit" form="createForm">{{ __('Create') }}</loading-button>
      </template>
    </Card>
  </AdminLayout>
</template>
