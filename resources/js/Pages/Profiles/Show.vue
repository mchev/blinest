<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import LevelBadge from '@/Components/LevelBadge.vue'
import EloBadge from '@/Components/EloBadge.vue'
import LevelInfo from '@/Components/LevelInfo.vue'
import LevelModal from '@/Components/LevelModal.vue'
import ScoresTab from './partials/ScoresTab.vue'
import LikesTab from './partials/LikesTab.vue'
import BookmarksTab from './partials/BookmarksTab.vue'
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

const props = defineProps({
  user: Object,
})

const scoreEvolution = ref([])
const loadingEvolution = ref(true)
const showLevelModal = ref(false)

const loadScoreEvolution = async () => {
  try {
    loadingEvolution.value = true
    const response = await axios.get(route('user.profile.score-evolution', props.user.id))
    scoreEvolution.value = response.data.score_evolution || []
  } catch (error) {
    console.error('Error loading score evolution:', error)
    scoreEvolution.value = []
  } finally {
    loadingEvolution.value = false
  }
}

onMounted(() => {
  loadScoreEvolution()
})

const chartData = computed(() => {
  if (!scoreEvolution.value || scoreEvolution.value.length === 0) {
    return {
      labels: [],
      datasets: [],
    }
  }

  return {
    labels: scoreEvolution.value.map((d) => d.date),
    datasets: [
      {
        label: 'Score',
        data: scoreEvolution.value.map((d) => d.total_score),
        fill: true,
        borderColor: '#64748b',
        backgroundColor: 'rgba(100, 116, 139, 0.1)',
        pointBackgroundColor: '#64748b',
        pointBorderColor: '#64748b',
        tension: 0.3,
      },
    ],
  }
})
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
    title: {
      display: false,
    },
    tooltip: {
      backgroundColor: '#1e293b',
      titleColor: '#cbd5e1',
      bodyColor: '#e2e8f0',
      borderColor: '#475569',
      borderWidth: 1,
      padding: 12,
    },
  },
  scales: {
    x: {
      grid: { color: 'rgba(255,255,255,0.05)' },
      ticks: { color: '#94a3b8', font: { size: 11 } },
    },
    y: {
      grid: { color: 'rgba(255,255,255,0.05)' },
      ticks: { color: '#94a3b8', font: { size: 11 } },
    },
  },
}
</script>
<template>
  <Head :title="user.name" />
  <AppLayout>
    <div class="mx-auto max-w-7xl space-y-6 lg:grid lg:grid-cols-12 lg:gap-6 lg:space-y-0">
      <aside class="lg:col-span-4 space-y-6">
        <Card>
          <div class="flex flex-col items-center text-center">
            <div class="relative mb-4">
              <img
                :src="user.photo"
                :alt="user.name"
                class="h-24 w-24 rounded-full border-2 border-neutral-600 object-cover"
                loading="lazy"
              />
              <div v-if="user.level" class="absolute -bottom-1 -right-1">
                <LevelBadge
                  :level="user.level"
                  :current-xp="user.current_xp"
                  :xp-for-next-level="user.xp_for_next_level"
                  :total-xp="user.total_xp"
                  :level-metrics="user.level_metrics"
                  size="sm"
                  @click="showLevelModal = true"
                />
              </div>
            </div>
            <h1 class="mb-2 text-2xl font-bold text-white">{{ user.name }}</h1>
            <Link
              v-if="user.team"
              :href="route('teams.show', user.team.id)"
              class="group mb-4 block w-full max-w-[280px] rounded-xl border border-violet-500/30 bg-gradient-to-br from-violet-950/50 via-neutral-900/80 to-fuchsia-950/30 p-3 text-left shadow-[0_0_24px_-8px_rgba(139,92,246,0.35)] transition hover:border-violet-400/45 hover:shadow-[0_0_32px_-6px_rgba(139,92,246,0.45)]"
            >
              <div class="flex items-center gap-3">
                <div class="relative shrink-0">
                  <div class="absolute inset-0 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 opacity-50 blur-md transition group-hover:opacity-70" />
                  <img
                    v-if="user.team.photo"
                    :src="user.team.photo"
                    :alt="user.team.name"
                    class="relative h-12 w-12 rounded-full object-cover ring-2 ring-violet-400/50"
                    loading="lazy"
                  >
                  <div
                    v-else
                    class="relative flex h-12 w-12 items-center justify-center rounded-full bg-neutral-800 text-lg font-black text-violet-300 ring-2 ring-violet-400/40"
                  >
                    {{ user.team.name?.charAt(0)?.toUpperCase() }}
                  </div>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-[10px] font-bold uppercase tracking-wider text-violet-300/90">
                    {{ __('Squad') }}
                  </p>
                  <p class="truncate text-base font-bold text-white group-hover:text-violet-100">
                    {{ user.team.name }}
                  </p>
                  <p class="text-xs text-neutral-500 group-hover:text-neutral-400">
                    {{ __('View team page') }} →
                  </p>
                </div>
              </div>
            </Link>
            <div class="mb-4 text-sm text-neutral-400">
              {{ user.created_at_from_now }}
            </div>

            <div class="mb-6 grid w-full grid-cols-2 gap-3">
              <div class="squircle-nested border border-neutral-700/50 bg-neutral-800/50 p-4">
                <div class="text-4xl font-bold text-white">{{ user.total_score }}</div>
                <div class="text-xs text-neutral-400">{{ __('PTS') }}</div>
              </div>
              <div class="squircle-nested border border-neutral-700/50 bg-neutral-800/50 p-4 flex flex-col items-center justify-center">
                <EloBadge :elo="user.elo" size="md" variant="compact" />
                <div class="text-xs text-neutral-400 mt-1">{{ __('ELO') }}</div>
              </div>
            </div>

            <div class="grid w-full grid-cols-3 gap-2">
              <div class="squircle-nested-sm border border-neutral-700/50 bg-neutral-800/50 p-3 text-center">
                <div class="text-xl font-bold text-white">{{ user.stats.rooms }}</div>
                <div class="text-xs text-neutral-400">{{ __('Rooms') }}</div>
              </div>
              <div class="squircle-nested-sm border border-neutral-700/50 bg-neutral-800/50 p-3 text-center">
                <div class="text-xl font-bold text-white">{{ user.stats.playlists }}</div>
                <div class="text-xs text-neutral-400">{{ __('Playlists') }}</div>
              </div>
              <div class="squircle-nested-sm border border-neutral-700/50 bg-neutral-800/50 p-3 text-center">
                <div class="text-xl font-bold text-white">{{ user.stats.bookmarks }}</div>
                <div class="text-xs text-neutral-400">{{ __('Bookmarks') }}</div>
              </div>
            </div>

            <div class="mt-4 flex w-full flex-col gap-2 text-xs text-neutral-400">
              <div class="flex justify-between">
                <span>{{ __('Public') }}</span>
                <span class="font-semibold text-white">{{ user.total_public_score }} {{ __('PTS') }}</span>
              </div>
              <div class="flex justify-between">
                <span>{{ __('Community') }}</span>
                <span class="font-semibold text-white">{{ user.total_private_score }} {{ __('PTS') }}</span>
              </div>
            </div>
          </div>
        </Card>

        <Card>
          <LevelInfo
            :level="user.level"
            :current-xp="user.current_xp"
            :xp-for-next-level="user.xp_for_next_level"
            :total-xp="user.total_xp"
            :level-metrics="user.level_metrics"
            :compact="true"
          />
        </Card>

        <Card>
          <template #header>
            <h2 class="text-lg font-bold text-white">{{ __('Evolution') }}</h2>
          </template>
          <div class="h-48 relative">
            <div v-if="loadingEvolution" class="absolute inset-0 flex items-end justify-between px-4 pb-4">
              <div class="flex items-end gap-1 w-full">
                <div class="h-8 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0s"></div>
                <div class="h-12 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.1s"></div>
                <div class="h-16 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.2s"></div>
                <div class="h-20 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.3s"></div>
                <div class="h-14 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.4s"></div>
                <div class="h-10 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.5s"></div>
                <div class="h-16 w-full bg-neutral-700/50 rounded-t animate-pulse" style="animation-delay: 0.6s"></div>
              </div>
            </div>
            <Line v-else-if="scoreEvolution.length > 1" :data="chartData" :options="chartOptions" />
            <div v-else class="flex h-full items-center justify-center text-neutral-400">
              {{ __('Not enough data for score evolution') }}
            </div>
          </div>
        </Card>
      </aside>

      <main class="lg:col-span-8 space-y-6">
        <Card>
          <template #header>
            <h2 class="text-lg font-bold text-white">{{ __('Scores') }}</h2>
          </template>
          <ScoresTab :user="user" :is-expanded="true" />
        </Card>

        <Card>
          <template #header>
            <h2 class="text-lg font-bold text-white">{{ __('Likes') }}</h2>
          </template>
          <LikesTab :user="user" :is-expanded="true" />
        </Card>

        <Card>
          <template #header>
            <h2 class="text-lg font-bold text-white">{{ __('Bookmarks') }}</h2>
          </template>
          <BookmarksTab :user="user" :is-expanded="true" />
        </Card>
      </main>
    </div>

    <LevelModal
      :show="showLevelModal"
      :level="user.level"
      :current-xp="user.current_xp"
      :xp-for-next-level="user.xp_for_next_level"
      :total-xp="user.total_xp"
      :level-metrics="user.level_metrics"
      @close="showLevelModal = false"
    />
  </AppLayout>
</template>