<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  notification: Object,
})

const emit = defineEmits(['markedAsdone'])
</script>
<template>
  <div v-if="notification" class="flex-grow">
    <div class="mb-1 flex items-center justify-between border-b pb-1">
      <h4 class="flex items-center font-bold uppercase text-red-500">{{ __('Track deleted') }}</h4>
      <span class="text-xs text-neutral-400">{{ notification.data.created_at }}</span>
    </div>
    <div class="my-2 border-l-2 border-red-500 pl-1">
      <div class="whitespace-pre-line text-sm font-medium">
        {{ notification.data.message }}
      </div>
      <p class="text-xs text-shark-100">{{ __('Automatic removal if the track is no longer available on the original platform.') }}</p>
      <Link v-if="notification.data.playlist" :href="`/playlists/${notification.data.playlist.id}/edit`" class="text-xs">Playlist : {{ notification.data.playlist.name }}</Link>
    </div>
  </div>
</template>
