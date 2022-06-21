<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
  rooms: Object,
})
</script>
<template>
  <div class="flex flex-wrap">
    <Link :href="`/rooms/${room.id}`" v-for="room in rooms" :key="room.id" class="mr-4 flex h-32 w-32 items-center justify-center rounded-lg bg-gray-100 p-8 shadow">
      {{ room.name }}
    </Link>
  </div>
</template>
