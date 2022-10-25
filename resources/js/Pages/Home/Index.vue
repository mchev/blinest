<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Rooms from './partials/Rooms.vue'

defineProps({
  filters: Object,
  categories: Object,
  private_rooms: Object,
  top_rooms: Array,
})
</script>
<template>
  <Head>
    <title>Blind-Tests multijoueurs</title>
    <meta name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
  </Head>
  <Layout>
    <section v-if="!filters.search">
      <div v-if="top_rooms" class="relative mb-4">
        <h2 class="text-xl text-neutral-400 lg:text-2xl mb-1">TOP 5</h2>
        <rooms :rooms="top_rooms" />
      </div>
    </section>
    <section v-if="categories.length" v-for="category in categories" :key="category.id">
      <div v-if="category.rooms.data.length" class="relative mb-4">
        <h2 class="text-xl text-neutral-400 lg:text-2xl mb-1">{{ category.name }}</h2>
        <rooms :rooms="category.rooms" :id="category.id" />
      </div>
    </section>
    <section v-if="private_rooms && private_rooms.data.length">
      <div class="relative">
        <h2 class="text-xl text-neutral-400 lg:text-2xl mb-1">{{ __('My rooms') }}</h2>
        <rooms :rooms="private_rooms" id="private" />
      </div>
    </section>
  </Layout>
</template>
