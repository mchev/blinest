<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Rooms from './partials/Rooms.vue'

defineProps({
  categories: Object,
  private_rooms: Object,
})
</script>
<template>
  <Head title="Home" />
  <Layout>
    <section v-for="category in categories" :key="category.id">
      <div v-if="category.rooms.data.length" class="relative mb-4">
        <h2 class="text-xl font-bold text-neutral-500 lg:text-2xl">{{ category.name }}</h2>
        <rooms :rooms="category.rooms" :id="category.id" />
      </div>
    </section>
    <section>
      <div class="relative">
        <h2 class="text-xl mt-2 font-bold text-neutral-500 lg:text-2xl">{{ __('My rooms') }}</h2>
        <rooms :rooms="private_rooms" id="private" />
      </div>
    </section>
  </Layout>
</template>
