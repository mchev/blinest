<script setup>
import { useForm } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  name: props.account.name,
  email: props.account.email,
})

const update = () => {
  form.post(route('users.update', props.account.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Card>
    <template #header>
      <h2 class="text-lg font-bold text-white">{{ __('Information') }}</h2>
    </template>

    <form id="editAccountForm" class="space-y-4" @submit.prevent="update">
      <text-input v-model="form.name" :error="form.errors.name" class="w-full" :label="__('Name')" required />
      <text-input v-model="form.email" type="email" :error="form.errors.email" class="w-full" :label="__('Email')" required />
    </form>

    <template #footer>
      <loading-button :loading="form.processing" class="btn-primary ml-auto" form="editAccountForm" type="submit">
        {{ __('Update') }}
      </loading-button>
    </template>
  </Card>
</template>
