<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  account: {
    type: Object,
    required: true,
  },
})

const links = (account) => [
  {
    href: account.links.profile,
    title: 'Me link public profile title',
    description: 'Me link public profile description',
    stat: null,
  },
  {
    href: account.links.profile_scores,
    title: 'Scores',
    description: 'Me link scores description',
    stat: null,
  },
  {
    href: account.links.profile_bookmarks,
    title: 'Bookmarks',
    description: 'Me link bookmarks description',
    stat: account.stats.bookmarks,
  },
  {
    href: account.links.profile_likes,
    title: 'Likes',
    description: 'Me link likes description',
    stat: null,
  },
  {
    href: account.links.profile_minigames,
    title: 'Mini-games',
    description: 'Me link minigames description',
    stat: null,
  },
  {
    href: account.links.rooms,
    title: 'My Rooms',
    description: 'Me link rooms description',
    stat: account.stats.rooms,
  },
  {
    href: account.links.playlists,
    title: 'My Playlists',
    description: 'Me link playlists description',
    stat: account.stats.playlists,
  },
  {
    href: account.links.rankings,
    title: 'Rankings',
    description: 'Me link rankings description',
    stat: null,
  },
]
</script>

<template>
  <section class="space-y-3">
    <div>
      <h2 class="text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Shortcuts') }}</h2>
      <p class="mt-1 text-sm text-white/45">{{ __('Me shortcuts intro') }}</p>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
      <Link v-for="item in links(account)" :key="item.href" :href="item.href" class="group rounded-xl border border-white/10 bg-brand-deep/50 p-4 transition hover:border-brand-primary/30 hover:bg-brand-deep/80">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="font-semibold text-white group-hover:text-brand-secondary">{{ __(item.title) }}</p>
            <p class="mt-1 text-xs leading-relaxed text-white/45">{{ __(item.description) }}</p>
          </div>
          <span v-if="item.stat !== null" class="shrink-0 rounded-full bg-white/10 px-2 py-0.5 text-xs font-bold tabular-nums text-white">
            {{ item.stat }}
          </span>
        </div>
      </Link>
    </div>

    <Link v-if="!account.is_supporter" :href="account.links.support" class="block rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 text-sm text-amber-100/90 transition hover:border-amber-400/35">
      {{ __('Me support CTA') }}
    </Link>
  </section>
</template>
