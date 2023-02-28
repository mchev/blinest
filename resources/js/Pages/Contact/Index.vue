<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Tip from '@/Components/Tip.vue'
import Card from '@/Components/Card.vue'

const form = useForm({
  message: '',
})

const send = () => {
  form.post(route('contact.send'))
}
</script>
<template>
  <Head>
    <title>{{ __('Contact') }}</title>
    <meta name="description" content="Envoyez votre message à Blinest. Réponse garantie!" />
  </Head>

  <AppLayout>
    <div class="mx-auto flex w-full max-w-2xl flex-col">
      <Card class="mb-4">
        <template #header>
          <h3 class="text-xl font-bold">{{ __('Send a message to') }} Blinest</h3>
        </template>
        <Tip class="mb-2">{{ __('For any request to add or modify tracks in public playlists, please contact the moderators directly.') }}</Tip>
        <Tip
          ><p>
            {{ __('To report a bug or suggest a site improvement, please use the following link: ') }}<a class="underline" target="_blank" href="https://github.com/mchev/blinest/issues/new">{{ __('Report a bug') }}</a>
          </p></Tip
        >

        <form @submit.prevent="send" id="roomForm" class="mt-4">
          <textarea-input v-model="form.message" :error="form.errors.message" rows="10" class="mb-4 w-full" :label="__('Message')" />
        </form>
        <template #footer>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" form="roomForm" type="submit">{{ __('Submit') }}</loading-button>
        </template>
      </Card>
    </div>
  </AppLayout>
</template>
