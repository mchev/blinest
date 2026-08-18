<script setup>
import { usePage } from '@inertiajs/vue3'

defineProps({
  room: {
    type: Object,
    required: true,
  },
  roomState: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['send-suggestion'])

const user = usePage().props.auth.user
</script>

<template>
  <section class="room-mobile-info" :aria-label="__('Room information')">
    <p v-if="room.description" class="room-mobile-info__description">
      {{ room.description }}
    </p>

    <div v-if="room.moderators?.length" class="room-mobile-info__group">
      <span class="room-mobile-info__label">{{ __('Moderators') }}</span>
      <div class="room-mobile-info__chips">
        <span v-for="moderator in room.moderators" :key="moderator.id" class="room-mobile-info__chip" :class="{ 'room-mobile-info__chip--online': roomState.users.find((player) => moderator.id === player.id) }">
          <img :src="moderator.photo" :alt="moderator.name" class="room-mobile-info__avatar" />
          <span>{{ moderator.name }}</span>
        </span>
      </div>
    </div>

    <div class="room-mobile-info__stats">
      <span class="room-mobile-info__stat">
        <span class="room-mobile-info__stat-value">{{ room.tracks_count != null ? room.tracks_count : '-' }}</span>
        <span>{{ __('Tracks') }}</span>
      </span>
      <span v-if="room.rounds_count != null && room.rounds_count > 0" class="room-mobile-info__stat">
        <span class="room-mobile-info__stat-value">{{ room.rounds_count }}</span>
        <span>{{ __('Rounds played') }}</span>
      </span>
    </div>

    <button v-if="user?.can?.sendSuggestion" type="button" class="room-mobile-info__suggestion" @click="emit('send-suggestion')">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
      </svg>
      <span>{{ __('Send a suggestion') }}</span>
    </button>
  </section>
</template>
