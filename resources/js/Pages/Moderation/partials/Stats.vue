<script setup>
import Card from '@/Components/Card.vue'

const props = defineProps({
  stats: Object,
})
</script>
<template>
  <div class="mb-4 flex w-full flex-wrap justify-between gap-4 text-center text-xl">
    <Card class="flex-grow">
      <template #header>
        <h3 class="mx-auto text-lg uppercase">{{ __('Users') }}</h3>
      </template>
      {{ stats.users_count }}
    </Card>
    <Card class="flex-grow">
      <template #header>
        <h3 class="mx-auto text-lg uppercase">{{ __('Public playlists') }}</h3>
      </template>
      {{ stats.public_playlists_count }} / {{ stats.playlists_count }}
    </Card>
    <Card class="flex-grow">
      <template #header>
        <h3 class="mx-auto text-lg uppercase">{{ __('Public rooms') }}</h3>
      </template>
      {{ stats.public_rooms_count }} / {{ stats.rooms_count }}
    </Card>
    <Card class="flex-grow">
      <template #header>
        <h3 class="mx-auto text-lg uppercase">{{ __('Banned users') }}</h3>
      </template>
      {{ stats.banned_users_count }}
    </Card>
  </div>
</template>
