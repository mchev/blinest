<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'

defineProps({
  page: Object,
})
</script>
<template>
  <Head>
    <title>{{ page.title }}</title>
  </Head>
  <AppLayout>
    <Card class="mx-auto">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="my-4 text-xl">{{ page.title }}</h1>
          <small class="text-xs text-neutral-500">{{ __('Last revision') }} : {{ page.date }}</small>
        </div>
      </template>
      <section class="prose prose-invert" v-html="page.content" />
    </Card>
  </AppLayout>
</template>
