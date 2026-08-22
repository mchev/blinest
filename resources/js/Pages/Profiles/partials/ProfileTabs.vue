<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  profileId: {
    type: Number,
    required: true,
  },
  activeTab: {
    type: String,
    required: true,
  },
})

const tabs = [
  { id: 'scores', label: 'Scores' },
  { id: 'likes', label: 'Likes' },
  { id: 'bookmarks', label: 'Bookmarks' },
  { id: 'minigames', label: 'Mini-games' },
]
</script>

<template>
  <nav class="flex gap-1 overflow-x-auto rounded-xl border border-white/10 bg-brand-deep/80 p-1">
    <Link v-for="tab in tabs" :key="tab.id" :href="route('user.profile', { user: profileId, tab: tab.id })" preserve-scroll class="flex-1 whitespace-nowrap rounded-lg px-4 py-2.5 text-center text-sm font-semibold transition" :class="activeTab === tab.id ? 'bg-brand-primary/20 text-white ring-1 ring-brand-primary/40' : 'text-white/55 hover:bg-white/5 hover:text-white'">
      {{ __(tab.label) }}
    </Link>
  </nav>
</template>
