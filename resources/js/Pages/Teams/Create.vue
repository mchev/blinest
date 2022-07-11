<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  name: '',
})

const store = () => {
  form.post('/teams')
}
</script>
<template>
  <Head title="Create Team" />
  <AppLayout>
    <Card class="max-w-xl mx-auto">
      <template #header>
        <h1 class="text-xl font-bold">{{ __('Creating a team') }}</h1>
      </template>
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <TextInput v-model="form.name" :error="form.errors.name" class="w-full pb-8" :label="__('Name')" />
        </div>
        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <LoadingButton :loading="form.processing" class="btn-primary" type="submit">{{ __('Create Team') }}</LoadingButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>
