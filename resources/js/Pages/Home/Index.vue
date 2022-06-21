<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/AppLayout'
import Rooms from './partials/Rooms'

defineProps({
  categories: Object,
})
</script>
<template>
  <Head title="Home" />
  <Layout>
    <div v-for="category in categories" :key="category.id">
      <h2>{{ category.name }}</h2>
      <rooms :rooms="category.public_rooms" />
    </div>
  </Layout>
</template>
