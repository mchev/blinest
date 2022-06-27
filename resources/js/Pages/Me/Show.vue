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

        <header class="flex gap-2 mb-6">
          <Link class="btn-primary" href="/me/edit">Editer mon profil</Link>
          <Link class="btn-primary" href="/me/edit">Modifier mon mot de passe</Link>
          <Link class="btn-primary ml-auto" :href="`/user/${user.id}`">Voir mon profil en tant que visiteur</Link>
        </header>

        <Card> </Card>


      </div>
    </div>
  </Layout>
</template>
