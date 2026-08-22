<script setup>
import { Link } from '@inertiajs/vue3'
import LucideIcon from '@/Components/Icons/LucideIcon.vue'

defineProps({
  badges: {
    type: Array,
    default: () => [],
  },
})

const earnedBadges = (badges) => badges.filter((badge) => badge.earned)
const inProgressBadges = (badges) => badges.filter((badge) => !badge.earned && badge.target && badge.current < badge.target).slice(0, 3)

const progressPercent = (badge) => {
  if (!badge.target) {
    return 0
  }

  return Math.min(100, Math.round((badge.current / badge.target) * 100))
}
</script>

<template>
  <section v-if="badges.length" class="overflow-visible rounded-2xl border border-white/10 bg-brand-deep/40 p-4 sm:p-5">
    <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Badges') }}</h2>

    <div v-if="earnedBadges(badges).length" class="flex flex-wrap gap-2 overflow-visible">
      <div v-for="badge in earnedBadges(badges)" :key="badge.id" class="group relative inline-flex max-w-full cursor-default items-center gap-1.5 rounded-full border border-brand-primary/30 bg-brand-primary/10 px-3 py-1.5 text-sm font-medium text-white">
        <LucideIcon :name="badge.icon" icon-class="h-4 w-4 shrink-0 text-brand-secondary" />
        <span class="truncate">{{ __(badge.label_key) }}</span>
        <div role="tooltip" class="pointer-events-none invisible absolute left-1/2 top-full z-50 mt-2 w-56 -translate-x-1/2 rounded-md border border-white/15 bg-brand-midnight px-2.5 py-2 text-[11px] font-normal normal-case leading-snug tracking-normal text-white/90 opacity-0 shadow-xl transition-opacity group-hover:visible group-hover:opacity-100">
          {{ __(badge.description_key) }}
        </div>
      </div>
    </div>
    <p v-else class="text-sm text-white/45">{{ __('No badges earned yet') }}</p>

    <div v-if="inProgressBadges(badges).length" class="mt-4 space-y-3">
      <p class="text-xs font-semibold uppercase tracking-wide text-white/45">{{ __('Badges in progress') }}</p>
      <Link v-for="badge in inProgressBadges(badges)" :key="badge.id" :href="badge.progress_url || '#'" class="group/badge block rounded-xl border border-white/10 bg-white/5 p-3 transition hover:border-brand-primary/30">
        <div class="mb-1 flex items-start justify-between gap-3">
          <div class="min-w-0">
            <div class="flex items-center gap-2">
              <LucideIcon :name="badge.icon" icon-class="h-4 w-4 shrink-0 text-white/50" />
              <span class="truncate text-sm font-medium text-white/80">{{ __(badge.label_key) }}</span>
            </div>
            <p class="mt-1 text-xs leading-relaxed text-white/45">{{ __(badge.description_key) }}</p>
          </div>
          <span class="shrink-0 text-xs tabular-nums text-white/45">{{ badge.current }}/{{ badge.target }}</span>
        </div>
        <div class="h-1.5 overflow-hidden rounded-full bg-white/10">
          <div class="h-full rounded-full bg-brand-secondary transition-all" :style="{ width: `${progressPercent(badge)}%` }" />
        </div>
      </Link>
    </div>
  </section>
</template>
