<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Card from '@/Components/Card'

defineProps({
  user: Object,
})
</script>
<template>
  <Head :title="user.name" />
  <Layout>
    <div class="flex text-neutral-100">
      <div class="mr-4 border-r pr-4">
        <figure>
          <img :src="user.photo" :alt="user.name" class="w-full rounded" />
        </figure>

        <h2 class="my-2 text-xl font-bold">{{ user.name }}</h2>
        <p>{{ user.email }}</p>

        <ul class="my-8">
          <li class="flex justify-between">
            Membre depuis le
            <span
              >{{ user.created_at }}<br /><small>{{ user.created_at_from_now }}</small></span
            >
          </li>
          <li>Dernière partie jouée le : {{ user.latest_round_at }}</li>
        </ul>

        <Link href="" class="text-red-600">Supprimer mon compte</Link>
      </div>

      <div class="flex-1">
        <header class="mb-6 flex gap-2">
          <Link class="btn-primary" href="/me/edit">Editer mon profil</Link>
          <Link class="btn-primary" href="/me/edit">Modifier mon mot de passe</Link>
          <Link class="btn-secondary ml-auto" :href="`/user/${user.id}`">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Voir mon profil en tant que visiteur</Link
          >
        </header>

        <Card> </Card>
      </div>
    </div>
  </Layout>
</template>
