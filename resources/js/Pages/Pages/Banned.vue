<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'

defineProps({
  ban: Object,
})

</script>
<template>
  <Head>
    <title>Vous avez été banni !</title>
  </Head>
  <AppLayout>
    <Card class="mx-auto">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="text-xl my-4 text-red-500">Vous avez été banni de blinest.com !</h1>
        </div>
      </template>
      <div class="prose prose-invert">
      <p><b>Raison du ban :</b><br>{{ ban.comment }}</p>
      <p><b>L'expulsion du site se termine :</b> <br>{{ ban.expired_at }}</p>
      <Tip class="text-orange-500">Dans certains cas, un signalement pourra être effectué auprès des autorités compétentes.</Tip>
      <Tip class="text-orange-500">Il est toujours possible d'accèder à la gestion de votre compte afin de respecter les règles sur la protection des données mais en cas de signalement nous nous reservons le droit de conserver les éléments de preuve comme le prévoit la loi.</Tip>
      </div>
    </Card>
  </AppLayout>
</template>