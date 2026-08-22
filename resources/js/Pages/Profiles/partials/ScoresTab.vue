<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  paginator: {
    type: Object,
    default: null,
  },
  profileId: {
    type: Number,
    required: true,
  },
  sort: {
    type: String,
    default: 'updated_at',
  },
  direction: {
    type: String,
    default: 'desc',
  },
})

const scores = ref(props.paginator ? [...props.paginator.data] : [])
const nextPage = ref(props.paginator ? props.paginator.current_page + 1 : 1)
const lastPage = ref(props.paginator ? props.paginator.last_page : 1)
const loading = ref(false)

watch(
  () => [props.paginator, props.sort, props.direction],
  ([paginator]) => {
    if (paginator) {
      scores.value = [...paginator.data]
      nextPage.value = paginator.current_page + 1
      lastPage.value = paginator.last_page
    }
  },
  { immediate: true },
)

const applySort = (key) => {
  const direction = props.sort === key && props.direction === 'desc' ? 'asc' : 'desc'

  router.get(route('user.profile', props.profileId), { tab: 'scores', sort: key, direction }, { preserveScroll: true, only: ['scores', 'activeTab', 'scoresSort', 'scoresDirection'] })
}

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.paginator) {
    return
  }

  loading.value = true

  router.get(
    route('user.profile', props.profileId),
    { tab: 'scores', page: nextPage.value, sort: props.sort, direction: props.direction },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['scores', 'activeTab', 'scoresSort', 'scoresDirection'],
      onSuccess: (page) => {
        const newScores = page.props.scores?.data || []
        scores.value.push(...newScores)
        nextPage.value = page.props.scores.current_page + 1
        lastPage.value = page.props.scores.last_page
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
  <div class="space-y-3">
    <div class="flex flex-wrap gap-2">
      <button type="button" @click="applySort('room')" :class="['retro-sort-btn', sort === 'room' ? 'retro-sort-btn--active' : '']">
        {{ __('Room') }} <span v-if="sort === 'room'">{{ direction === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button type="button" @click="applySort('updated_at')" :class="['retro-sort-btn', sort === 'updated_at' ? 'retro-sort-btn--active' : '']">
        {{ __('Last played') }} <span v-if="sort === 'updated_at'">{{ direction === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button type="button" @click="applySort('score')" :class="['retro-sort-btn', sort === 'score' ? 'retro-sort-btn--active' : '']">
        {{ __('Score') }} <span v-if="sort === 'score'">{{ direction === 'asc' ? '▲' : '▼' }}</span>
      </button>
    </div>

    <div v-for="score in scores" :key="score.id" class="retro-list-row">
      <Link v-if="score.room" class="flex min-w-0 items-center" :href="route('rooms.show', score.room.slug)">
        <img v-if="score.room.photo" class="squircle-nested-xs h-12 w-12 shrink-0 object-cover" :src="score.room.photo" loading="lazy" />
        <div class="ml-3 flex min-w-0 flex-col">
          <span class="truncate font-medium text-white">{{ score.room.name }}</span>
          <span class="text-xs text-white/60">{{ score.updated_at }}</span>
        </div>
      </Link>
      <div class="ml-auto shrink-0 text-xl font-bold text-brand-secondary">
        {{ score.score }} <span class="text-sm text-white/60">{{ __('PTS') }}</span>
      </div>
    </div>

    <div v-if="scores.length === 0" class="py-12 text-center text-white/60">{{ __('No scores found') }}</div>

    <div v-if="loading" class="flex justify-center py-8">
      <svg class="h-6 w-6 animate-spin text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
      </svg>
    </div>

    <div v-if="nextPage <= lastPage && !loading && scores.length > 0" class="flex justify-center pt-4">
      <button type="button" @click="loadMore" class="squircle-nested-xs border border-neutral-600 bg-neutral-700 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-600">
        <span class="inline-flex items-center gap-2">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
          {{ __('Load more') }}
        </span>
      </button>
    </div>
  </div>
</template>
