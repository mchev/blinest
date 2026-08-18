<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Deferred } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import RankingTabs from './partials/RankingTabs.vue'
import PlayersLeaderboard from './partials/PlayersLeaderboard.vue'
import PlayerUserPosition from './partials/PlayerUserPosition.vue'
import Pagination from '@/Components/Pagination.vue'

const page = usePage()

const user = computed(() => page.props.auth?.user ?? null)

const props = defineProps({
  leaderboard: Object,
  sort: {
    type: String,
    default: 'elo',
  },
  sorts: {
    type: Array,
    default: () => [],
  },
  officialRooms: {
    type: Array,
    default: () => [],
  },
  roomId: {
    type: Number,
    default: null,
  },
  userContext: {
    type: Object,
    default: null,
  },
})

const rankingQuery = (overrides = {}) => ({
  sort: props.sort,
  ...(props.roomId ? { room: props.roomId } : {}),
  ...overrides,
})

const onRoomChange = (event) => {
  const value = event.target.value

  router.get(
    route('rankings.index'),
    rankingQuery({
      room: value ? Number(value) : undefined,
      page: undefined,
    }),
    { preserveState: true, preserveScroll: true },
  )
}
</script>

<template>
  <AppLayout>
    <section>
      <div class="mx-auto max-w-5xl px-4 py-4 sm:py-8">
        <div class="mx-auto mb-6 text-center sm:mb-8">
          <h1 class="mb-2 bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 bg-clip-text text-3xl font-extrabold text-transparent sm:mb-4 sm:text-5xl">
            {{ __('Rankings') }}
          </h1>
          <p class="text-sm text-neutral-400 sm:text-lg">{{ __('Compete with the best players') }}</p>
          <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-neutral-500 sm:text-base">
            {{ __('Rankings SEO intro') }}
          </p>
          <Link :href="route('docs.index')" class="mt-3 inline-flex items-center gap-1.5 text-sm text-yellow-500/90 transition-colors hover:text-yellow-400">
            {{ __('How do rankings work?') }}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>

        <RankingTabs />

        <div v-if="!user" class="mb-6 rounded-xl border border-yellow-500/20 bg-yellow-500/5 p-4 sm:p-5">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
              <p class="font-semibold text-white">{{ __('Rankings guest CTA title') }}</p>
              <p class="text-sm text-neutral-400">{{ __('Rankings guest CTA body') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <Link :href="route('register')" class="game-btn-secondary inline-flex justify-center">
                {{ __('Rankings guest CTA button') }}
              </Link>
              <Link :href="route('login')" class="game-link-action inline-flex items-center justify-center px-3 py-2">
                {{ __('Log in') }}
              </Link>
            </div>
          </div>
        </div>

        <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-end sm:justify-between">
          <div class="flex flex-wrap gap-2">
            <Link v-for="sortOption in sorts" :key="sortOption.id" :href="route('rankings.index', rankingQuery({ sort: sortOption.id, page: undefined }))" preserve-scroll class="rounded-full border px-3 py-1.5 text-xs font-medium transition-colors sm:text-sm" :class="sort === sortOption.id ? 'border-yellow-500 bg-yellow-500/15 text-yellow-400' : 'border-neutral-700 text-neutral-400 hover:border-neutral-500 hover:text-neutral-200'">
              {{ sortOption.label }}
            </Link>
          </div>

          <label class="flex min-w-[12rem] flex-col gap-1 text-xs text-neutral-400 sm:text-sm">
            <span>{{ __('Filter by room') }}</span>
            <select :value="roomId ?? ''" class="rounded-lg border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white focus:border-yellow-500 focus:outline-none focus:ring-1 focus:ring-yellow-500/40" @change="onRoomChange">
              <option value="">{{ __('All official rooms') }}</option>
              <option v-for="room in officialRooms" :key="room.id" :value="room.id">{{ room.name }}</option>
            </select>
          </label>
        </div>

        <Deferred data="userContext">
          <template #fallback>
            <div class="mt-6 h-24 animate-pulse rounded-xl border border-neutral-800 bg-neutral-900/60 sm:mt-8" />
          </template>
          <PlayerUserPosition v-if="userContext?.position" :position="userContext.position" :entry="userContext.entry" :sort="sort" />
        </Deferred>

        <div v-if="leaderboard?.data?.length > 0" class="mt-6">
          <PlayersLeaderboard :items="leaderboard" :sort="sort" />
          <Pagination :links="leaderboard.links" />
        </div>
        <div v-else class="py-16 text-center">
          <p class="text-neutral-400">{{ __('No rankings available') }}</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
