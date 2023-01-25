<script setup>
import { router } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'

const props = defineProps({
  moderators: Object,
})
</script>
<template>
  <Card>
    <ul>
      <li v-for="room in moderators" :key="room.id" class="mb-4 flex flex-col border-b border-neutral-600">
        {{ room.name }}
        <ul class="mb-2 flex flex-wrap gap-1">
          <li v-for="moderator in room.moderators">
            <span class="badge flex items-center gap-1"><img :src="moderator.photo" class="h-5 w-5 rounded-full" />{{ moderator.name }}</span>
          </li>
        </ul>
      </li>
    </ul>
  </Card>
</template>
