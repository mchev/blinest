<script setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Room from './partials/Room.vue'
import Rooms from './partials/Rooms.vue'

defineProps({
  filters: Object,
  categories: Object,
  private_rooms: Object,
  user_rooms: Object,
  top_rooms: Array,
  search_result: Object,
})
</script>
<template>
  <Head>
    <title>{{ __('Free multiplayer music quizzes') }}</title>
    <meta head-key="description" name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
  </Head>
  <Layout>
    <h1 class="hidden">Blinest, {{ __('Free multiplayer music quizzes') }}</h1>
    <section v-if="search_result">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4 lg:grid-cols-6">
        <Room v-for="room in search_result" :room="room" :key="room.id" />
      </div>
    </section>
    <div v-else>
      <section v-if="!filters.search">
        <div class="flex flex-wrap items-center">
          <div v-if="top_rooms" class="relative mb-4 flex-grow">
            <h2 class="mb-1 text-xl text-neutral-400 lg:text-2xl">TOP 5</h2>
            <rooms :rooms="top_rooms" :is_top_5="true" id="topRooms" />
          </div>
        </div>
      </section>
      <section v-if="categories.length" v-for="category in categories" :key="category.id">
        <div v-if="category.rooms.length" class="relative mb-4">
          <h2 class="mb-1 text-xl text-neutral-400 lg:text-2xl">{{ __(category.name) }}</h2>
          <rooms :rooms="category.rooms" :id="`category-${category.id}`" />
        </div>
      </section>
      <section v-if="private_rooms && private_rooms.length">
        <div class="relative">
          <h2 class="mb-1 text-xl text-neutral-400 lg:text-2xl">{{ __('Private rooms') }}</h2>
          <rooms :rooms="private_rooms" id="privateRooms" />
        </div>
      </section>
      <section v-if="user_rooms && user_rooms.length">
        <div class="relative">
          <h2 class="mb-1 text-xl text-neutral-400 lg:text-2xl">{{ __('My rooms') }}</h2>
          <rooms :rooms="user_rooms" id="userRooms" />
        </div>
      </section>
    </div>
  </Layout>
</template>
