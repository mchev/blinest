<script setup>
import { ref, defineAsyncComponent, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Badge from '@/Components/Badge.vue'
import LevelBadge from '@/Components/LevelBadge.vue'
import { Line } from 'vue-chartjs'
import {
  Chart,
  LineElement,
  PointElement,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  Filler,
} from 'chart.js'

Chart.register(LineElement, PointElement, LinearScale, Title, Tooltip, Legend, CategoryScale, Filler)

const ScoresTab = defineAsyncComponent(() => import('./partials/ScoresTab.vue'))
const LikesTab = defineAsyncComponent(() => import('./partials/LikesTab.vue'))
const BookmarksTab = defineAsyncComponent(() => import('./partials/BookmarksTab.vue'))

const props = defineProps({
  user: Object,
})

const tab = ref('scores')
const loading = ref(false)

const setTab = (newTab) => {
  if (tab.value !== newTab) {
    loading.value = true
    tab.value = newTab
    setTimeout(() => { loading.value = false }, 300)
  }
}

// Score evolution chart data
const scoreEvolution = computed(() => props.user.score_evolution || [])
const chartData = computed(() => ({
  labels: scoreEvolution.value.map((d) => d.date),
  datasets: [
    {
      label: 'Score',
      data: scoreEvolution.value.map((d) => d.total_score),
      fill: true,
      borderColor: '#FACC15', // yellow-400
      backgroundColor: 'rgba(250,204,21,0.15)',
      pointBackgroundColor: '#FACC15',
      pointBorderColor: '#FACC15',
      tension: 0.3,
    },
  ],
}))
const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      display: false,
    },
    title: {
      display: false,
    },
    tooltip: {
      backgroundColor: '#272B2C',
      titleColor: '#FACC15',
      bodyColor: '#fff',
      borderColor: '#FACC15',
      borderWidth: 1,
      padding: 12,
    },
  },
  scales: {
    x: {
      grid: { color: 'rgba(255,255,255,0.05)' },
      ticks: { color: '#FACC15', font: { weight: 'bold' } },
    },
    y: {
      grid: { color: 'rgba(255,255,255,0.08)' },
      ticks: { color: '#FACC15', font: { weight: 'bold' } },
    },
  },
}
</script>
<template>
  <Head :title="user.name" />
  <AppLayout>

      <!-- Blurred blobs for depth -->
      <section class="relative z-10 w-full max-w-3xl self-center">
        <div class="flex flex-col items-center rounded-3xl shadow-2xl bg-gradient-to-br from-shark-700 via-shark-800 to-black border-4 border-red-500/60 p-8">
          <!-- Profile Card Header -->
          <div class="flex flex-col items-center gap-4 w-full">
            <div class="relative">
              <img
                :src="user.photo"
                alt="Profile photo"
                class="shadow-xl rounded-full w-32 h-32 border-4 border-yellow-400 object-cover ring-4 ring-red-500"
                loading="lazy"
              />
              <span class="absolute -bottom-2 -right-2 bg-gradient-to-r from-yellow-400 to-red-500 text-white rounded-full px-3 py-1 text-xs font-bold shadow-lg">
                <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                {{ __('Quiz Star!') }}
              </span>
            </div>
            <h1 class="text-4xl font-extrabold text-white flex items-center gap-2 mb-1 drop-shadow-lg">
              {{ user.name }}
              <LevelBadge v-if="user.level" :level="user.level" size="lg" variant="default" />
              <Badge v-if="user.team" color="primary" :text="user.team.name" class="ml-2" />
            </h1>
            <div class="flex flex-wrap gap-2 justify-center text-red-400 text-base font-semibold">
              <span class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                {{ __('Member since') }} {{ user.created_at_from_now }}
              </span>
            </div>

            <!-- Score Evolution Graph -->
            <div class="w-full my-6">
              <div class="rounded-xl bg-black/30 p-4 shadow flex flex-col items-center justify-center min-h-[200px]">
                <Line v-if="scoreEvolution.length > 1" :data="chartData" :options="chartOptions" class="w-full h-56" />
                <span v-else class="text-gray-400 text-lg font-bold">{{ __('Not enough data for score evolution') }}</span>
              </div>
            </div>

            <div class="flex flex-col items-center gap-2 w-full mt-2">
              <div class="flex gap-4 w-full justify-center">
                <div class="rounded-xl px-6 py-3 text-center shadow bg-gradient-to-r from-red-500 to-yellow-400 text-white font-bold text-lg flex-1 hover:scale-105 transition-all">
                  <div class="text-2xl font-extrabold">{{ user.stats.rooms }}</div>
                  <div class="text-xs uppercase tracking-widest">{{ __('Rooms') }}</div>
                </div>
                <div class="rounded-xl px-6 py-3 text-center shadow bg-gradient-to-r from-yellow-400 to-blue-500 text-white font-bold text-lg flex-1 hover:scale-105 transition-all">
                  <div class="text-2xl font-extrabold">{{ user.stats.playlists }}</div>
                  <div class="text-xs uppercase tracking-widest">{{ __('Playlists') }}</div>
                </div>
                <div class="rounded-xl px-6 py-3 text-center shadow bg-gradient-to-r from-blue-500 to-red-500 text-white font-bold text-lg flex-1 hover:scale-105 transition-all">
                  <div class="text-2xl font-extrabold">{{ user.stats.bookmarks }}</div>
                  <div class="text-xs uppercase tracking-widest">{{ __('Bookmarks') }}</div>
                </div>
              </div>
              <div class="flex items-center gap-2 text-3xl font-extrabold text-yellow-400 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                </svg>
                {{ user.total_score }} <sup class="text-lg text-red-400">{{ __('PTS') }}</sup>
              </div>
              <div class="flex gap-4 text-xs text-gray-200 mt-1 font-bold">
                <span>{{ __('Public rooms') }}: <span class="text-yellow-400">{{ user.total_public_score }}</span></span>
                <span>{{ __('Private rooms') }}: <span class="text-yellow-400">{{ user.total_private_score }}</span></span>
              </div>
            </div>
          </div>

          <!-- Segmented Control Navigation -->
          <div class="w-full flex justify-center mt-10 mb-4">
            <div class="inline-flex rounded-full bg-black/30 p-1 shadow-lg">
              <button @click="setTab('scores')" :class="['px-6 py-2 rounded-full font-bold flex items-center gap-2 transition-all', tab === 'scores' ? 'bg-red-500 text-white shadow' : 'text-red-400 hover:bg-red-500/10']">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2M16 11V7a4 4 0 00-8 0v4m12 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/></svg>
                {{ __('Scores') }}
              </button>
              <button @click="setTab('likes')" :class="['px-6 py-2 rounded-full font-bold flex items-center gap-2 transition-all', tab === 'likes' ? 'bg-yellow-400 text-black shadow' : 'text-yellow-400']">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.293l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.293l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
                {{ __('Likes') }}
              </button>
              <button @click="setTab('bookmarks')" :class="['px-6 py-2 rounded-full font-bold flex items-center gap-2 transition-all', tab === 'bookmarks' ? 'bg-blue-500 text-white shadow' : 'text-blue-400 hover:bg-blue-500/10']">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5v14l7-7 7 7V5a2 2 0 00-2-2H7a2 2 0 00-2 2z"/></svg>
                {{ __('Bookmarks') }}
              </button>
            </div>
          </div>

          <div class="mt-4 min-h-[200px] w-full" role="tabpanel">
            <div v-if="loading" class="flex justify-center items-center min-h-[200px]">
              <svg class="animate-spin h-8 w-8 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
              </svg>
            </div>
            <ScoresTab v-if="!loading && tab === 'scores'" :user="user" />
            <LikesTab v-if="!loading && tab === 'likes'" :user="user" />
            <BookmarksTab v-if="!loading && tab === 'bookmarks'" :user="user" />
          </div>
        </div>
      </section>

  </AppLayout>
</template>