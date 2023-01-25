<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
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
    <Card class="mx-auto max-w-xl">
      <template #header>
        <h1 class="text-xl font-bold">{{ __('Creating a team') }}</h1>
      </template>
      <form @submit.prevent="store">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <TextInput v-model="form.name" :error="form.errors.name" class="w-full pb-8" :label="__('Name')" />
        </div>
        <div class="flex items-center justify-end px-8 py-4">
          <LoadingButton :loading="form.processing" class="btn-primary" type="submit">{{ __('Create Team') }}</LoadingButton>
        </div>
      </form>
    </Card>
  </AppLayout>
</template>
