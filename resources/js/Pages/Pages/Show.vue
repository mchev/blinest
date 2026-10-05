<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import Adsense from '@/Components/Adsense.vue'

defineProps({
  page: Object,
})
</script>
<template>
  <AppLayout>
    <Card class="mx-auto">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="my-4 text-xl">{{ page.title }}</h1>
          <small class="text-xs text-neutral-500">{{ __('Last revision') }} : {{ page.date }}</small>
        </div>
      </template>
      <Adsense wrapper-class="mb-6" />
      <section class="prose prose-invert" v-html="page.content" />
    </Card>
  </AppLayout>
</template>
