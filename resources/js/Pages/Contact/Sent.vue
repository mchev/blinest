<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import Button from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  message: '',
})

const send = () => {
  form.post(route('contact.send'))
}
</script>
<template>
  <Head :title="__('Contact')" />

  <AppLayout>
    <div class="mx-auto flex w-full max-w-2xl flex-col">
      <Card class="mb-4">
        <template #header>
          <h3 class="text-xl font-bold">{{ __('Message sent') }}!</h3>
        </template>
        <p class="mb-2">Merci, le message a bien été envoyé.</p>
        <template #footer>
          <Link class="btn-primary" :href="route('home')">{{ __('Go back to home') }}</Link>
        </template>
      </Card>
    </div>
  </AppLayout>
</template>
