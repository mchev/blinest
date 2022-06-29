<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Rooms from './partials/Rooms'

defineProps({
  categories: Object,
  private_rooms: Object,
})
</script>
<template>
  <Head title="Home" />
  <Layout>
    <section v-for="category in categories" :key="category.id">
      <div v-if="category.public_rooms.length" class="relative">
        <h2 class="text-1xl mb-2 mt-6 font-bold sm:text-2xl lg:text-3xl">{{ category.name }}</h2>
        <rooms :rooms="category.public_rooms" />
      </div>
    </section>
    <section>
      <div class="relative">
        <h2 class="mb-2 mt-4 text-2xl sm:text-3xl lg:text-4xl">{{ __('My rooms') }}</h2>
        <rooms :rooms="private_rooms" />
      </div>
    </section>
  </Layout>
</template>
