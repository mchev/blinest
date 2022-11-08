<script setup>
import { Inertia } from '@inertiajs/inertia'
import Card from '@/Components/Card.vue'

const props = defineProps({
  moderators: Object,
})

</script>
<template>
  <Card>
    <template #header>
      <h2 class="font-bold">Modérateurs officiels</h2>
    </template>
    <ul>
      <li v-for="room in moderators" :key="room.id" class="flex flex-col mb-4 border-b border-neutral-600">
        {{ room.name }}
        <ul class="flex flex-wrap gap-1 mb-2">
          <li v-for="moderator in room.moderators">
            <span class="badge flex items-center gap-1"><img :src="moderator.photo" class="w-5 h-5 rounded-full">{{ moderator.name }}</span>
          </li>
        </ul>
      </li>
    </ul>
  </Card>
</template>
