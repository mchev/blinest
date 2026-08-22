<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  minigames: {
    type: Object,
    default: null,
  },
  profileId: {
    type: Number,
    required: true,
  },
})

const history = ref(props.minigames?.history?.data ? [...props.minigames.history.data] : [])
const nextPage = ref(props.minigames?.history ? props.minigames.history.current_page + 1 : 1)
const lastPage = ref(props.minigames?.history ? props.minigames.history.last_page : 1)
const loading = ref(false)

watch(
  () => props.minigames,
  (payload) => {
    if (payload?.history) {
      history.value = [...payload.history.data]
      nextPage.value = payload.history.current_page + 1
      lastPage.value = payload.history.last_page
    }
  },
  { immediate: true },
)

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.minigames?.history) {
    return
  }

  loading.value = true

  router.get(
    route('user.profile', props.profileId),
    { tab: 'minigames', page: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['minigames', 'activeTab'],
      onSuccess: (page) => {
        const rows = page.props.minigames?.history?.data || []
        history.value.push(...rows)
        nextPage.value = page.props.minigames.history.current_page + 1
        lastPage.value = page.props.minigames.history.last_page
        loading.value = false
      },
      onError: () => {
        loading.value = false
      },
    },
  )
}
</script>

<template>
  <div v-if="minigames" class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <p class="text-sm text-white/55">{{ __('Mini-games scores on profile intro') }}</p>
      <div class="flex flex-wrap items-center gap-3">
        <p v-if="minigames.user_rank" class="text-xs font-semibold text-brand-secondary">
          {{ __('Profile minigames rank', { rank: minigames.user_rank, score: minigames.user_total_score }) }}
        </p>
        <Link :href="minigames.rankings_url" class="text-xs font-semibold text-brand-secondary hover:text-brand-secondary/80">
          {{ __('Mini-games rankings') }}
        </Link>
      </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="game in minigames.games" :key="game.type" class="rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="truncate font-semibold text-white">{{ __(game.label_key) }}</p>
            <p class="mt-1 text-2xl font-black tabular-nums text-brand-secondary">{{ game.total }}</p>
            <p class="text-[10px] uppercase tracking-wide text-white/45">{{ __('PTS') }}</p>
          </div>
          <Link :href="game.play_url" class="shrink-0 rounded-lg border border-white/15 px-2.5 py-1 text-xs font-semibold text-white/80 transition hover:border-brand-primary/40 hover:text-white">
            {{ __('Play') }}
          </Link>
        </div>
      </div>
    </div>

    <div class="space-y-3">
      <h3 class="text-sm font-bold uppercase tracking-wider text-white/70">{{ __('Recent sessions') }}</h3>

      <div v-for="session in history" :key="session.id" class="retro-list-row">
        <div class="min-w-0 flex-1">
          <p class="font-medium text-white">{{ __(session.label_key) }}</p>
          <p class="text-xs text-white/55">{{ session.played_at }}</p>
        </div>
        <div class="text-lg font-bold text-brand-secondary">{{ session.score }} {{ __('PTS') }}</div>
      </div>

      <div v-if="history.length === 0" class="py-10 text-center text-white/45">{{ __('No mini-game sessions yet') }}</div>

      <div v-if="loading" class="flex justify-center py-6">
        <svg class="h-6 w-6 animate-spin text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
        </svg>
      </div>

      <div v-if="nextPage <= lastPage && !loading && history.length > 0" class="flex justify-center pt-2">
        <button type="button" @click="loadMore" class="squircle-nested-xs border border-neutral-600 bg-neutral-700 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-600">
          {{ __('Load more') }}
        </button>
      </div>
    </div>
  </div>
</template>
