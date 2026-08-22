<script setup>
import LucideIcon from '@/Components/Icons/LucideIcon.vue'

defineProps({
  badges: {
    type: Array,
    default: () => [],
  },
})

const earnedBadges = (badges) => badges.filter((badge) => badge.earned)
</script>

<template>
  <section v-if="badges.length" class="rounded-2xl border border-white/10 bg-brand-deep/40 p-4 sm:p-5">
    <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Badges') }}</h2>

    <div v-if="earnedBadges(badges).length" class="flex flex-wrap gap-2">
      <div v-for="badge in earnedBadges(badges)" :key="badge.id" class="inline-flex items-center gap-2 rounded-full border border-brand-primary/30 bg-brand-primary/10 px-3 py-1.5 text-sm font-medium text-white" :title="__(badge.label_key)">
        <LucideIcon :name="badge.icon" icon-class="h-4 w-4 text-brand-secondary" />
        {{ __(badge.label_key) }}
      </div>
    </div>
    <p v-else class="text-sm text-white/45">{{ __('No badges earned yet') }}</p>
  </section>
</template>
