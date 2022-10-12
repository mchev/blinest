<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'

defineProps({
  page: Object,
})
</script>
<template>
  <Head>
    <title>{{ page.title }}</title>
    <meta name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
  </Head>
  <AppLayout>
    <Card class="mx-auto">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="text-xl my-4">{{ page.title }}</h1>
          <small class="text-xs text-neutral-500">{{ __('Last revision') }} : {{ page.date }}</small>
        </div>
      </template>
      <section class="prose text-inherit" v-html="page.content"/>
    </Card>
  </AppLayout>
</template>