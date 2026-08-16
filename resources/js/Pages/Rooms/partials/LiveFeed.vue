<script setup>
import { computed } from 'vue'

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
})

const hasEvents = computed(() => props.events.length > 0)
</script>

<template>
  <div class="room-live-feed flex h-full min-h-0 flex-col">
    <div
      v-if="hasEvents"
      aria-live="polite"
      aria-relevant="additions"
      aria-atomic="false"
      class="min-h-0 flex flex-1 flex-col"
    >
      <ul
        class="room-live-feed__list min-h-0 flex-1 overflow-y-auto pr-1"
        role="list"
      >
        <li
          v-for="event in events"
          :key="event.id"
          class="room-live-feed__item room-live-feed__item--enter"
          role="listitem"
        >
          <img
            v-if="event.userPhoto"
            :src="event.userPhoto"
            :alt="event.userName"
            class="room-live-feed__avatar"
          />
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-semibold text-white">
              {{ event.userName }}
            </p>
            <p class="truncate text-sm text-white/80">
              <span class="text-brand-accent">{{ __(event.answerName) }}</span>
            </p>
          </div>
          <div class="shrink-0 text-right">
            <p v-if="event.points != null" class="text-sm font-bold text-brand-secondary">
              +{{ event.points }}
              <span class="sr-only">{{ __('points') }}</span>
            </p>
            <p v-else-if="event.order && event.order < 4" class="text-xs font-bold text-brand-secondary">
              #{{ event.order }}
            </p>
            <p v-else class="text-sm text-brand-accent">
              <span aria-hidden="true">✓</span>
              <span class="sr-only">{{ __('Correct answer') }}</span>
            </p>
            <p v-if="event.speedBonus" class="text-xs text-white/60">
              <span aria-hidden="true">⚡</span>
              <span class="sr-only">{{ __('Speed bonus') }}</span>
            </p>
          </div>
        </li>
      </ul>
    </div>

    <div v-else class="flex flex-1 flex-col items-center justify-center py-8 text-center text-white/60">
      <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
      </svg>
      <p class="text-sm">{{ __('Activity will appear here in real time') }}</p>
    </div>
  </div>
</template>
