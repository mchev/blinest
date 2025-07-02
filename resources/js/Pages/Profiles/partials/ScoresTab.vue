<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
})

const scores = ref([...props.user.scores.data])
const nextPage = ref(props.user.scores.current_page + 1)
const lastPage = ref(props.user.scores.last_page)
const loading = ref(false)

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
  return [...scores.value].sort((a, b) => {
    let aVal = a[sortKey.value]
    let bVal = b[sortKey.value]
    if (sortKey.value === 'room') {
      aVal = a.room?.name || ''
      bVal = b.room?.name || ''
    }
    if (sortKey.value === 'score') {
      aVal = Number(aVal)
      bVal = Number(bVal)
    }
    if (sortDir.value === 'asc') {
      return aVal > bVal ? 1 : aVal < bVal ? -1 : 0
    } else {
      return aVal < bVal ? 1 : aVal > bVal ? -1 : 0
    }
  })
})

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value) return
  loading.value = true
  router.get(
    window.location.pathname,
    { scores: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
      onSuccess: (page) => {
        const newScores = page.props.user.scores.data
        scores.value.push(...newScores)
        nextPage.value++
        loading.value = false
      },
      onError: () => { loading.value = false },
    }
  )
}
</script>
<template>
  <div class="space-y-2">
    <div class="flex items-center gap-2 mb-4 animate-bounce">
      <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
      <span class="font-bold text-yellow-300 text-lg">{{ __('Keep going! Every score counts!') }}</span>
    </div>
    <div class="flex gap-2 font-bold text-xs mb-2">
      <button @click="setSort('room')" :class="['px-3 py-1 rounded-full', sortKey === 'room' ? 'bg-red-500 text-white' : 'bg-neutral-800 text-red-300']">
        {{ __('Room') }} <span v-if="sortKey === 'room'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button @click="setSort('updated_at')" :class="['px-3 py-1 rounded-full', sortKey === 'updated_at' ? 'bg-yellow-400 text-black' : 'bg-neutral-800 text-yellow-300']">
        {{ __('Last played') }} <span v-if="sortKey === 'updated_at'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
      <button @click="setSort('score')" :class="['px-3 py-1 rounded-full', sortKey === 'score' ? 'bg-blue-500 text-white' : 'bg-neutral-800 text-blue-300']">
        {{ __('Score') }} <span v-if="sortKey === 'score'">{{ sortDir === 'asc' ? '▲' : '▼' }}</span>
      </button>
    </div>
    <div v-for="score in sortedScores" :key="score.id" class="flex items-center gap-4 bg-neutral-900/80 rounded-xl px-4 py-3 shadow">
      <Link class="flex items-center" :href="route('rooms.show', score.room.slug)">
        <img v-if="score.room.photo" class="h-12 w-12 rounded-full ring-2 ring-pink-300 mr-3" :src="score.room.photo" loading="lazy" />
        <div class="flex flex-col">
          <span class="font-bold text-pink-200">{{ score.room.name }}</span>
          <span class="text-xs text-neutral-400">{{ score.updated_at }}</span>
        </div>
      </Link>
      <div class="ml-auto text-2xl font-extrabold text-yellow-400 flex items-center">
        {{ score.score }} <sup class="text-xs text-pink-400 ml-1">{{ __('PTS') }}</sup>
      </div>
    </div>
    <div v-if="scores.length === 0" class="text-center text-pink-700 py-8">{{ __('No scores found') }}</div>
    <div v-if="loading" class="flex justify-center py-4">
      <div class="animate-pulse flex gap-4">
        <div class="h-10 w-10 bg-pink-200 rounded-full"></div>
        <div class="flex-1 space-y-2">
          <div class="h-4 bg-pink-200 rounded w-1/2"></div>
          <div class="h-4 bg-pink-200 rounded w-1/3"></div>
        </div>
      </div>
    </div>
    <div v-if="nextPage.value <= lastPage.value && !loading" class="flex justify-center mt-4">
      <button @click="loadMore" class="px-6 py-2 rounded-full bg-gradient-to-r from-yellow-400 to-pink-400 text-white font-bold shadow-lg hover:scale-105 hover:from-pink-400 hover:to-yellow-400 transition-all duration-200">
        <span class="inline-flex items-center gap-2">
          <svg class="w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13v4h3a1 1 0 110 2h-4a1 1 0 01-1-1V5a1 1 0 112 0z"/></svg>
          {{ __('Load more scores!') }}
        </span>
      </button>
    </div>
  </div>
</template>