<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Layout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  photo: null,
})

const update = () => {
  form.post(route('users.update', props.user.id), {
    onSuccess: () => {
      form.reset('photo')
    }
  })
}

const deleteUser = () => {
  if(confirm('Attention, cette action est irréversible. Voulez-vous vraiment supprimer votre compte et tous les scores associés?')) {
    Inertia.delete(route('users.destroy', props.user.id))
  }
}
</script>
<template>
  <Head :title="user.name" />
  <Layout>
    <div class="flex">
      <div class="mr-4 border-r border-neutral-600 pr-4 text-center">

        <figure class="mb-6">
          <img :src="user.photo" :alt="user.name" class="w-60 rounded-lg" />
        </figure>

        <h2 class="text-xl font-bold">{{ user.name }}</h2>
        <p class="text-xs text-neutral-400">{{ user.email }}</p>

        <ul class="my-8">
          <li class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Registered at') }}</span>
            <span
              >{{ user.created_at }}<br /><small>{{ user.created_at_from_now }}</small></span
            >
          </li>
          <li class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Id') }}</span>
            {{ user.id}}
          </li>
          <li v-if="user.latest_round_at" class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Last round played at') }}</span>
            {{ user.latest_round_at }}
          </li>
          <li v-else class="mb-4 flex flex-col">
            {{ __('No round played yet') }}
          </li>
        </ul>

        <button @click="deleteUser" class="btn-danger btn-sm mx-auto">{{ __('Delete my account') }}</button>
      </div>

      <div class="flex-1">
        <Card class="my-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Informations') }}</h2>
          </template>
          <form id="editUserForm" @submit.prevent="update">
            <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Name')" required />
            <text-input v-model="form.email" type="email" :error="form.errors.email" class="mb-4 w-full" :label="__('Email')" required />
            <file-input v-model="form.photo" :error="form.errors.photo" class="mb-4 w-full" type="file" accept="image/*" :label="__('Photo')" />
            <text-input v-model="form.password" type="password" :error="form.errors.password" class="mb-4 w-full" :label="__('New password') + ' (' + __('Optional') + ')'" />
          </form>
          <template #footer>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="editUserForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>

        <Card class="my-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Playlist moderation') }}</h2>
          </template>
        </Card>

        <Card class="my-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Room modération') }}</h2>
          </template>
        </Card>
      </div>
    </div>
  </Layout>
</template>
