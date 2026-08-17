<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  game: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Link
    :href="game.play_url"
    class="game-card group flex h-full min-h-[220px] flex-col"
  >
    <div class="relative flex flex-1 flex-col gap-4 bg-gradient-to-b from-brand-deep to-brand-midnight p-5">
      <span class="game-card__poster-grid absolute inset-0 opacity-25" aria-hidden="true" />
      <div
        class="relative z-10 mx-auto flex h-16 w-16 items-center justify-center rounded-lg bg-brand-midnight text-white ring-1 ring-white/15 transition group-hover:scale-110"
        aria-hidden="true"
      >
        <Icon name="play" class="h-7 w-7" />
      </div>
      <div class="relative z-10 min-w-0 flex-1 text-center">
        <h3 class="retro-title retro-title--accent text-sm">
          {{ game.name }}
        </h3>
        <p class="mt-1 line-clamp-2 text-xs text-white/70">
          {{ game.description }}
        </p>
        <p
          v-if="game.score !== undefined && game.score > 0"
          class="retro-badge mt-2"
        >
          {{ game.score }} {{ __('points') }}
        </p>
      </div>
      <span class="relative z-10 game-btn-play-join mt-auto text-xs">
        {{ __('Play') }}
      </span>
    </div>
  </Link>
</template>
