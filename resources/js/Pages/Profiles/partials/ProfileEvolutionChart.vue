<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from 'vue'
import axios from 'axios'

const ChartLine = defineAsyncComponent(() =>
  Promise.all([import('chart.js'), import('vue-chartjs')]).then(([chartJs, vueChartJs]) => {
    const { Chart, LineElement, PointElement, LinearScale, Title, Tooltip, Legend, CategoryScale, Filler } = chartJs

    Chart.register(LineElement, PointElement, LinearScale, Title, Tooltip, Legend, CategoryScale, Filler)

    return vueChartJs.Line
  }),
)

const props = defineProps({
  userId: {
    type: Number,
    required: true,
  },
})

const scoreEvolution = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await axios.get(route('user.profile.score-evolution', props.userId))
    scoreEvolution.value = response.data.score_evolution || []
  } catch (error) {
    console.error('Error loading score evolution:', error)
    scoreEvolution.value = []
  } finally {
    loading.value = false
  }
})

const chartData = computed(() => {
  if (scoreEvolution.value.length === 0) {
    return { labels: [], datasets: [] }
  }

  return {
    labels: scoreEvolution.value.map((entry) => entry.date),
    datasets: [
      {
        label: 'Score',
        data: scoreEvolution.value.map((entry) => entry.total_score),
        fill: true,
        borderColor: '#f59e0b',
        backgroundColor: 'rgba(245, 158, 11, 0.08)',
        pointBackgroundColor: '#f59e0b',
        pointBorderColor: '#f59e0b',
        tension: 0.3,
      },
    ],
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
  },
  scales: {
    x: {
      grid: { color: 'rgba(255,255,255,0.05)' },
      ticks: { color: '#94a3b8', font: { size: 10 }, maxTicksLimit: 6 },
    },
    y: {
      grid: { color: 'rgba(255,255,255,0.05)' },
      ticks: { color: '#94a3b8', font: { size: 10 } },
    },
  },
}
</script>

<template>
  <div class="relative h-44 sm:h-48">
    <div v-if="loading" class="absolute inset-0 flex items-end gap-1 px-2 pb-2">
      <div v-for="index in 7" :key="index" class="w-full animate-pulse rounded-t bg-white/10" :style="{ height: `${20 + index * 8}%`, animationDelay: `${index * 0.08}s` }" />
    </div>
    <ChartLine v-else-if="scoreEvolution.length > 1" :data="chartData" :options="chartOptions" />
    <div v-else class="flex h-full items-center justify-center text-sm text-white/45">
      {{ __('Not enough data for score evolution') }}
    </div>
  </div>
</template>
