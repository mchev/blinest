<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import Pagination from '@/Components/Pagination.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  teams: Object,
  filters: Object,
})

const user = usePage().props.auth.user

const isTeamsListLoading = ref(false)

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    isTeamsListLoading.value = true
    router.get(
      '/teams',
      { ...pickBy({ search: form.search }), page: 1 },
      {
        remember: 'forget',
        preserveState: true,
        onFinish: () => {
          isTeamsListLoading.value = false
        },
      },
    )
  }, 150),
  { deep: true },
)

const sendRequest = (team) => {
  router.post(`/teams/${team.id}/request`)
}

const cancelRequest = (team) => {
  router.post(`/teams/${team.id}/request/cancel`)
}

const formatNumber = (num) => {
  const n = Number(num)
  if (Number.isNaN(n)) {
    return '0'
  }
  return n >= 1000 ? n.toLocaleString(undefined, { maximumFractionDigits: 1 }) : String(Math.round(n * 10) / 10)
}

const isTeamFull = (team) => Number(team.members_count) >= Number(team.seats)
</script>

<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <div class="mx-auto max-w-7xl pb-16 pt-6 md:pt-10">
      <!-- Hero -->
      <div class="relative mb-10 overflow-hidden rounded-2xl border border-violet-500/20 bg-gradient-to-br from-violet-950/80 via-neutral-900/90 to-fuchsia-950/50 px-6 py-10 shadow-[0_0_60px_-12px_rgba(139,92,246,0.35)] sm:px-10 md:mb-14 md:py-12">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-fuchsia-500/10 blur-3xl" />
        <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-violet-600/10 blur-3xl" />
        <div class="relative mx-auto max-w-3xl text-center">
          <h1 class="mb-4 bg-gradient-to-r from-white via-violet-100 to-fuchsia-200 bg-clip-text text-4xl font-black tracking-tight text-transparent sm:text-5xl md:text-6xl">
            {{ __('Teams') }}
          </h1>
          <p class="mx-auto max-w-xl text-base text-neutral-300 sm:text-lg">
            {{ __('Join a team and share your scores with other members to skyrocket the scores!') }}
          </p>
          <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <Link
              v-if="!user.team"
              href="/teams/create"
              class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-violet-900/40 transition hover:from-violet-500 hover:to-fuchsia-500 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 focus:ring-offset-neutral-950"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              {{ __('Create a team') }}
            </Link>
            <Link
              v-else
              :href="route('teams.show', user.team.id)"
              class="inline-flex items-center gap-2 rounded-xl border border-violet-400/40 bg-violet-500/10 px-6 py-3 text-sm font-bold text-violet-100 transition hover:border-violet-300/60 hover:bg-violet-500/20"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              {{ __('Show my team') }}
            </Link>
            <Link
              :href="route('rankings.teams')"
              class="inline-flex items-center gap-2 rounded-xl border border-neutral-600 bg-neutral-800/60 px-5 py-3 text-sm font-semibold text-neutral-200 transition hover:border-neutral-500 hover:bg-neutral-800"
            >
              {{ __('Team rankings') }}
            </Link>
          </div>
        </div>
      </div>

      <!-- Search -->
      <div class="mx-auto mb-10 max-w-xl">
        <TextInput
          v-model="form.search"
          :placeholder="__('Search a team')"
          prepend-icon="search"
          :loading="isTeamsListLoading"
          class="w-full"
          input-class="w-full rounded-xl border-neutral-600 bg-neutral-900/80 py-3 pl-11 text-white placeholder-neutral-500 focus:border-violet-500 focus:ring-violet-500/30"
        />
      </div>

      <!-- Grid -->
      <div v-if="teams.data.length" class="relative space-y-8">
        <div
          class="grid grid-cols-1 gap-6 transition-opacity duration-200 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
          :class="isTeamsListLoading ? 'pointer-events-none opacity-50' : ''"
        >
          <article
            v-for="team in teams.data"
            :key="team.id"
            class="group relative flex flex-col overflow-hidden rounded-2xl border border-neutral-700/60 bg-neutral-900/50 shadow-lg backdrop-blur-sm transition duration-300 hover:border-violet-500/35 hover:shadow-[0_0_40px_-8px_rgba(139,92,246,0.25)]"
          >
            <div class="pointer-events-none absolute right-3 top-3 z-10 flex items-center gap-1 rounded-full border border-amber-500/30 bg-amber-500/10 px-2.5 py-1 text-xs font-bold text-amber-200">
              <span class="opacity-80">{{ team.members_count }}</span>
              <span class="text-neutral-500">/</span>
              <span>{{ team.seats }}</span>
            </div>
            <Link
              :href="route('teams.show', team.id)"
              class="flex flex-1 flex-col items-center px-5 pb-4 pt-8 text-left outline-none ring-inset transition hover:brightness-105 focus-visible:ring-2 focus-visible:ring-violet-500"
            >
              <div class="relative mb-4">
                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-violet-500/40 to-fuchsia-500/30 opacity-0 blur-xl transition group-hover:opacity-100" />
                <img
                  class="relative h-28 w-28 rounded-full object-cover ring-2 ring-violet-500/40 ring-offset-2 ring-offset-neutral-900 transition group-hover:ring-fuchsia-400/50 sm:h-32 sm:w-32"
                  :src="team.photo"
                  :alt="team.name"
                >
              </div>
              <h3 class="mb-1 text-center text-xl font-bold text-white transition group-hover:text-violet-200">
                {{ team.name }}
              </h3>
              <p class="mb-4 text-center text-sm text-neutral-400">
                @{{ team.owner.name }}
              </p>
              <div class="grid w-full grid-cols-2 gap-2 text-center">
                <div class="relative overflow-hidden rounded-xl border border-violet-500/25 bg-gradient-to-b from-violet-500/15 via-neutral-900/40 to-neutral-900/70 px-2 py-3 shadow-inner shadow-violet-900/20">
                  <div class="text-[10px] font-bold uppercase tracking-wider text-violet-300/90">
                    {{ __('Squad score') }}
                  </div>
                  <div
                    class="mt-1 flex flex-wrap items-baseline justify-center gap-0.5"
                    :title="`${formatNumber(team.team_points ?? 0)} ${__('PTS')}`"
                  >
                    <span class="bg-gradient-to-r from-violet-200 via-fuchsia-200 to-violet-200 bg-clip-text text-2xl font-black tabular-nums tracking-tight text-transparent">
                      {{ team.team_points_abbreviated ?? formatNumber(team.team_points ?? 0) }}
                    </span>
                    <span class="text-xs font-bold uppercase text-violet-400/90">{{ __('PTS') }}</span>
                  </div>
                </div>
                <div class="rounded-xl border border-neutral-700/80 bg-neutral-800/50 px-2 py-2">
                  <div class="text-[10px] font-semibold uppercase tracking-wider text-neutral-500">
                    {{ __('Rounds together') }}
                  </div>
                  <div class="text-lg font-bold tabular-nums text-fuchsia-200">
                    {{ team.rounds_played ?? 0 }}
                  </div>
                </div>
              </div>
            </Link>
            <div class="mt-auto w-full px-5 pb-5">
              <button
                v-if="user.declined_requests.includes(team.id)"
                type="button"
                class="w-full rounded-xl border border-red-500/40 bg-red-500/10 px-4 py-2.5 text-sm font-semibold text-red-300 transition hover:bg-red-500/20"
                @click="cancelRequest(team)"
              >
                {{ __('Declined request') }}
              </button>
              <button
                v-else-if="user.pending_requests.includes(team.id)"
                type="button"
                class="w-full rounded-xl border border-amber-500/40 bg-amber-500/10 px-4 py-2.5 text-sm font-semibold text-amber-200 transition hover:bg-amber-500/20"
                @click="cancelRequest(team)"
              >
                {{ __('Cancel join request') }}
              </button>
              <button
                v-else-if="isTeamFull(team)"
                type="button"
                disabled
                class="w-full cursor-not-allowed rounded-xl border border-neutral-600 bg-neutral-800/80 px-4 py-2.5 text-sm font-semibold text-neutral-500"
                :title="__('It is not possible to join this team. The maximum number of members has been reached')"
              >
                {{ __('Team is full') }}
              </button>
              <button
                v-else
                type="button"
                class="w-full rounded-xl bg-gradient-to-r from-violet-600/90 to-fuchsia-600/90 px-4 py-2.5 text-sm font-bold text-white shadow-md transition hover:from-violet-500 hover:to-fuchsia-500"
                @click="sendRequest(team)"
              >
                {{ __('Send a join request') }}
              </button>
            </div>
          </article>
        </div>

        <Pagination v-if="teams.links && teams.links.length > 3" :links="teams.links" />
      </div>

      <div v-else class="rounded-2xl border border-dashed border-neutral-600 bg-neutral-900/30 px-6 py-16 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-14 w-14 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-semibold text-white">
          {{ __('No results found') }}
        </h3>
        <p class="mt-2 text-sm text-neutral-400">
          {{ __('Try adjusting your search to find what you\'re looking for.') }}
        </p>
      </div>
    </div>
  </AppLayout>
</template>
