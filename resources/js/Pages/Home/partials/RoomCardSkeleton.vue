<script setup>
defineProps({
  index: {
    type: Number,
    default: 0,
  },
  variant: {
    type: String,
    default: 'catalog',
    validator: (value) => ['catalog', 'minigame'].includes(value),
  },
})
</script>

<template>
  <article class="flex h-full w-full flex-col" aria-hidden="true" :style="{ '--skeleton-delay': `${index * 75}ms` }">
    <div v-if="variant === 'minigame'" class="game-card flex h-full min-h-[220px] flex-col overflow-hidden">
      <div class="relative flex flex-1 flex-col gap-4 bg-gradient-to-b from-brand-deep to-brand-midnight p-5">
        <span class="game-card__poster-grid absolute inset-0 opacity-25" aria-hidden="true" />
        <div class="home-catalog-skeleton__shimmer relative z-10 mx-auto h-16 w-16 rounded-lg" />
        <div class="relative z-10 space-y-2">
          <div class="home-catalog-skeleton__shimmer mx-auto h-4 w-2/3 rounded" />
          <div class="home-catalog-skeleton__shimmer mx-auto h-3 w-full rounded" />
          <div class="home-catalog-skeleton__shimmer mx-auto h-3 w-4/5 rounded" />
        </div>
        <div class="home-catalog-skeleton__shimmer relative z-10 mt-auto h-9 w-full rounded" />
      </div>
    </div>

    <div v-else class="game-card flex h-full flex-col overflow-hidden">
      <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-brand-midnight to-brand-deep">
        <span class="game-card__poster-grid absolute inset-0 opacity-30" aria-hidden="true" />
        <span class="game-card__scanlines" aria-hidden="true" />
        <div class="home-catalog-skeleton__shimmer absolute left-2.5 top-2.5 h-5 w-14 rounded" />
        <div class="home-catalog-skeleton__shimmer absolute right-2.5 top-2.5 h-7 w-7 rounded" />
        <div class="absolute inset-x-0 bottom-0 h-1 bg-white/5">
          <div class="home-catalog-skeleton__shimmer h-full w-1/3 rounded-none" />
        </div>
      </div>

      <div class="game-card__deck flex flex-1 flex-col gap-3">
        <div class="min-w-0 space-y-2">
          <div class="home-catalog-skeleton__shimmer h-4 w-4/5 rounded" />
          <div class="game-card__stats !mt-0">
            <div class="home-catalog-skeleton__shimmer h-7 flex-1 rounded" />
            <div class="home-catalog-skeleton__shimmer h-7 flex-1 rounded" />
          </div>
        </div>
        <div class="home-catalog-skeleton__shimmer mt-auto h-9 w-full rounded" />
      </div>
    </div>
  </article>
</template>
