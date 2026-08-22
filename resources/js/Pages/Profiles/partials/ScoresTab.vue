<script setup>
import { ref, computed, watch } from 'vue'
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
})

const scores = ref(props.paginator ? [...props.paginator.data] : [])
const nextPage = ref(props.paginator ? props.paginator.current_page + 1 : 1)
const lastPage = ref(props.paginator ? props.paginator.last_page : 1)
const loading = ref(false)

watch(
  () => props.paginator,
  (newPaginator) => {
    if (newPaginator) {
      scores.value = [...newPaginator.data]
      nextPage.value = newPaginator.current_page + 1
      lastPage.value = newPaginator.last_page
    }
  },
  { immediate: true },
)

const sortKey = ref('updated_at')
const sortDir = ref('desc')

const setSort = (key) => {
  if (sortKey.value === key) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortKey.value = key
    sortDir.value = 'asc'
  }
}

const sortedScores = computed(() => {
  const items = [...scores.value]
  const key = sortKey.value
  const dir = sortDir.value

  return items.sort((a, b) => {
    let aVal = a[key]
    let bVal = b[key]

    if (key === 'room') {
      aVal = a.room?.name || ''
      bVal = b.room?.name || ''
    } else if (key === 'score') {
      aVal = Number(aVal)
      bVal = Number(bVal)
    }

    return dir === 'asc' ? (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) : aVal < bVal ? 1 : aVal > bVal ? -1 : 0
  })
})

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.paginator) {
    return
  }

  loading.value = true

  router.get(
    route('user.profile', props.profileId),
    { tab: 'scores', page: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['scores', 'activeTab'],
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
      <button type="button" @click="setSort('room')" :class="['retro-sort-btn', sortKey === 'room' ? 'retro-sort-btn--active' : '']">
        {{ __('Room') }} <span v-if="sortKey === 'room'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button type="button" @click="setSort('updated_at')" :class="['retro-sort-btn', sortKey === 'updated_at' ? 'retro-sort-btn--active' : '']">
        {{ __('Last played') }} <span v-if="sortKey === 'updated_at'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button type="button" @click="setSort('score')" :class="['retro-sort-btn', sortKey === 'score' ? 'retro-sort-btn--active' : '']">
        {{ __('Score') }} <span v-if="sortKey === 'score'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
    </div>

    <div v-for="score in sortedScores" :key="score.id" class="retro-list-row">
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
