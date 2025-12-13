<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  isExpanded: {
    type: Boolean,
    default: false,
  },
})

const scores = ref(props.user.scores ? [...props.user.scores.data] : [])
const nextPage = ref(props.user.scores ? props.user.scores.current_page + 1 : 1)
const lastPage = ref(props.user.scores ? props.user.scores.last_page : 1)
const loading = ref(false)

watch(() => props.user.scores, (newScores) => {
  if (newScores) {
    scores.value = [...newScores.data]
    nextPage.value = newScores.current_page + 1
    lastPage.value = newScores.last_page
  }
}, { immediate: true })

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

    return dir === 'asc' ? (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) : (aVal < bVal ? 1 : aVal > bVal ? -1 : 0)
  })
})

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.user.scores) return
  loading.value = true
  router.get(
    window.location.pathname,
    { scores: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
      onSuccess: (page) => {
        const newScores = page.props.user.scores?.data || []
        scores.value.push(...newScores)
        nextPage.value++
        loading.value = false
      },
      onError: () => {
        loading.value = false
      },
    }
  )
}

const expandSection = () => {
  router.get(
    window.location.pathname,
    { scores: 1, tab: 'scores' },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
    }
  )
}
</script>
<template>
  <div class="space-y-3">
    <div class="mb-4 flex flex-wrap gap-2">
      <button @click="setSort('room')" :class="['squircle-nested-xs border px-3 py-1.5 text-xs font-medium transition-colors', sortKey === 'room' ? 'border-slate-400 bg-slate-700 text-white' : 'border-neutral-600 bg-neutral-800 text-neutral-300 hover:bg-neutral-700']">
        {{ __('Room') }} <span v-if="sortKey === 'room'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button @click="setSort('updated_at')" :class="['squircle-nested-xs border px-3 py-1.5 text-xs font-medium transition-colors', sortKey === 'updated_at' ? 'border-slate-400 bg-slate-700 text-white' : 'border-neutral-600 bg-neutral-800 text-neutral-300 hover:bg-neutral-700']">
        {{ __('Last played') }} <span v-if="sortKey === 'updated_at'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button @click="setSort('score')" :class="['squircle-nested-xs border px-3 py-1.5 text-xs font-medium transition-colors', sortKey === 'score' ? 'border-slate-400 bg-slate-700 text-white' : 'border-neutral-600 bg-neutral-800 text-neutral-300 hover:bg-neutral-700']">
        {{ __('Score') }} <span v-if="sortKey === 'score'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
    </div>
    <div v-for="score in sortedScores" :key="score.id" class="flex items-center gap-4 squircle-nested-sm border border-neutral-700/50 bg-neutral-800/50 px-4 py-3 transition-colors hover:bg-neutral-800/70">
      <Link class="flex items-center" :href="route('rooms.show', score.room.slug)">
        <img v-if="score.room.photo" class="h-12 w-12 squircle-nested-xs object-cover" :src="score.room.photo" loading="lazy" />
        <div class="ml-3 flex flex-col">
          <span class="font-medium text-white">{{ score.room.name }}</span>
          <span class="text-xs text-neutral-400">{{ score.updated_at }}</span>
        </div>
      </Link>
      <div class="ml-auto text-xl font-bold text-white">
        {{ score.score }} <span class="text-sm text-neutral-400">{{ __('PTS') }}</span>
      </div>
    </div>
    <div v-if="scores.length === 0" class="py-12 text-center text-neutral-400">{{ __('No scores found') }}</div>
    <div v-if="loading" class="flex justify-center py-8">
      <svg class="h-6 w-6 animate-spin text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
      </svg>
    </div>
    <div v-if="!isExpanded && scores.length > 0 && scores.length < (user.scores?.total || 0)" class="flex justify-center pt-4">
      <button @click="expandSection" class="squircle-nested-xs border border-neutral-600 bg-neutral-700 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-600">
        {{ __('View all') }} ({{ user.scores?.total || 0 }})
      </button>
    </div>
    <div v-else-if="isExpanded && nextPage <= lastPage && !loading && scores.length > 0" class="flex justify-center pt-4">
      <button @click="loadMore" class="squircle-nested-xs border border-neutral-600 bg-neutral-700 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-600">
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