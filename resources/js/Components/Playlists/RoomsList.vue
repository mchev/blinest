<script setup>
import { Link } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'

defineProps({
  playlist: Object,
})
</script>
<template>
  <Card>
    <template #header>
      <h3 class="text-xl font-bold">{{ __('Rooms') }}</h3>
    </template>

    <ul v-if="playlist.rooms.length" class="max-h-80 overflow-y-auto">
      <li v-for="room in playlist.rooms" :key="room.id" class="flex items-center rounded gap-2 p-3 hover:bg-neutral-900">
        <img :src="room.photo" class="rounded-full h-8 w-8">
        <Link :href="route('rooms.show', room.slug)">{{ room.name }}</Link>
        <span class="text-sm">(<Link :href="route('user.profile', room.owner)">{{ room.owner.name }}</Link>)</span>
      </li>
    </ul>
    <p v-else class="my-2 text-sm text-neutral-400">{{ __('Empty') }}</p>
  </Card>
</template>
