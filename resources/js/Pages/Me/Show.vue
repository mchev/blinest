<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import Layout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  user: Object,
})

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
      <div class="mr-4 border-r pr-4">
        <figure>
          <img :src="user.photo" :alt="user.name" class="w-full rounded" />
        </figure>

        <h2 class="mt-2 text-xl font-bold">{{ user.name }}</h2>
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

        <button @click="deleteUser" class="text-red-600 hover:underline">{{ __('Delete my account') }}</button>
      </div>

      <div class="flex-1">
        <header class="mb-6 flex gap-2">
          <Link class="btn-primary" href="/me/edit">{{ __('Edit my profile') }}</Link>
          <Link class="btn-primary" href="/me/password">{{ __('Change password') }}</Link>
          <Link class="btn-secondary ml-auto" :href="`/users/${user.id}`">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            {{ __('See my profile as regular user') }}
          </Link>
        </header>

        <Card class="my-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('My scores') }}</h2>
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
