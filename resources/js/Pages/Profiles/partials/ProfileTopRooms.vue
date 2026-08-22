<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  topRooms: {
    type: Array,
    default: () => [],
  },
  profileId: {
    type: Number,
    required: true,
  },
})
</script>

<template>
  <section v-if="topRooms.length" class="rounded-2xl border border-white/10 bg-brand-deep/40 p-4 sm:p-5">
    <div class="mb-3 flex items-center justify-between gap-3">
      <h2 class="text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Top rooms') }}</h2>
      <Link :href="route('user.profile', { user: profileId, tab: 'scores' })" class="text-xs font-semibold text-brand-secondary hover:text-brand-secondary/80">
        {{ __('View all') }}
      </Link>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
      <Link v-for="(entry, index) in topRooms" :key="entry.room.id" :href="route('rooms.show', entry.room.slug)" class="group relative overflow-hidden rounded-xl border border-white/10 bg-white/5 transition hover:border-brand-primary/30 hover:bg-white/10">
        <div class="absolute left-2 top-2 z-10 flex h-6 w-6 items-center justify-center rounded-full bg-brand-midnight/90 text-xs font-black text-brand-secondary">
          {{ index + 1 }}
        </div>
        <img v-if="entry.room.photo" :src="entry.room.photo" :alt="entry.room.name" class="h-24 w-full object-cover opacity-90 transition group-hover:opacity-100" loading="lazy" />
        <div v-else class="flex h-24 items-center justify-center bg-brand-midnight/80 text-2xl font-black text-white/30">
          {{ entry.room.name?.charAt(0)?.toUpperCase() }}
        </div>
        <div class="space-y-1 p-3">
          <p class="truncate font-semibold text-white group-hover:text-brand-secondary">{{ entry.room.name }}</p>
          <p class="text-sm font-bold text-brand-secondary">{{ entry.score }} {{ __('PTS') }}</p>
          <p class="text-[10px] text-white/45">{{ entry.updated_at }}</p>
        </div>
      </Link>
    </div>
  </section>
</template>
