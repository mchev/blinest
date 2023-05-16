<script setup>
import { ref } from 'vue'
import { router, usePage, useForm, Link } from '@inertiajs/vue3'
import AccountLayout from '@/Layouts/AccountLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Tip from '@/Components/Tip.vue'

import UserSubscriptions from './partials/UserSubscriptions.vue'

const props = defineProps({
  user: Object,
  subscriptions: Object,
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
    },
  })
}

const deleteUser = () => {
  if (confirm('Attention, cette action est irréversible. Voulez-vous vraiment supprimer votre compte et tous les scores associés?')) {
    router.delete(route('users.destroy', props.user.id))
  }
}
</script>
<template>
  <AccountLayout>
    <div class="flex flex-col gap-8">
      <Card class="p-8">
        <template #header>
          <h2 class="text-xl font-bold">{{ __('Edit informations') }}</h2>
        </template>
        <form id="editUserForm" @submit.prevent="update">
          <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Name')" required />
          <text-input v-model="form.email" type="email" :error="form.errors.email" class="mb-4 w-full" :label="__('Email')" required />
          <file-input v-model="form.photo" :error="form.errors.photo" class="mb-4 w-full" type="file" accept="image/*" :label="__('Photo')" />
        </form>
        <template #footer>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" form="editUserForm" type="submit">{{ __('Update') }}</loading-button>
        </template>
      </Card>
      <Card class="p-8">
        <template #header>
          <h2 class="text-xl font-bold">{{ __('Password') }}</h2>
        </template>
        <form id="editUserForm" @submit.prevent="update">
          <text-input v-model="form.password" type="password" :error="form.errors.password" class="mb-4 w-full" :label="__('New password') + ' (' + __('Optional') + ')'" autocomplete="new-password" name="new-password" />
        </form>
        <template #footer>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" form="editUserForm" type="submit">{{ __('Change my password') }}</loading-button>
        </template>
      </Card>
      <Card class="p-8">
        <template #header>
          <h2 class="text-xl font-bold">{{ __('Subscriptions') }}</h2>
        </template>
        <UserSubscriptions :subscriptions="subscriptions"/>
        <template #footer>
          <Link href="/account/billing" class="btn-primary ml-auto">{{ __('Manage my subscriptions') }}</Link>
        </template>
      </Card>
      <Card class="border-2 border-red-800 p-8 text-red-500">
        <h3 class="mb-4 text-lg font-bold">{{ __('Delete my account') }}</h3>
        <p>Conformément au Règlement général sur la protection des données (RGPD),<br> la suppression de votre compte entraînera la suppression irréversible des éléments suivants :</p>
        <ul class="flex text-white mb-4">
          <li class="badge">Informations personnelles</li>
          <li class="badge">Rooms</li>
          <li class="badge">Playlists</li>
          <li class="badge">Scores</li>
          <li class="badge">Team</li>
          <li class="badge">Favoris</li>
          <li class="badge">Likes</li>
          <li class="badge">Photo</li>
        </ul>
        <Tip>Si vous avez souscris à un abonnement celui-ci sera automatiquement résilié.</Tip>
        <template #footer>
          <loading-button :loading="form.processing" class="btn-danger ml-auto">{{ __('Delete my account') }}</loading-button>
        </template>
      </Card>
    </div>
  </AccountLayout>
</template>
