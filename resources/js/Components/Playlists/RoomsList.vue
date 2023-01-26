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

    <ul v-if="playlist.rooms.length">
      <li v-for="room in playlist.rooms" :key="room.id" class="flex items-center rounded p-3 hover:bg-neutral-200">
        <Link :href="route('rooms.show', room.slug)">{{ room.name }}</Link>
      </li>
    </ul>
    <p v-else class="my-2 text-sm text-neutral-400">{{ __('Empty') }}</p>
  </Card>
</template>
