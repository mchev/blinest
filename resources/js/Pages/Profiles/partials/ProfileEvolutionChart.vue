<script setup>
import { computed, defineAsyncComponent } from 'vue'

const ChartLine = defineAsyncComponent(() =>
  Promise.all([import('chart.js'), import('vue-chartjs')]).then(([chartJs, vueChartJs]) => {
    const { Chart, LineElement, PointElement, LinearScale, Title, Tooltip, Legend, CategoryScale, Filler } = chartJs

    Chart.register(LineElement, PointElement, LinearScale, Title, Tooltip, Legend, CategoryScale, Filler)

    return vueChartJs.Line
  }),
)

const props = defineProps({
  scoreEvolution: {
    type: Array,
    default: () => [],
  },
})

const chartData = computed(() => {
  if (props.scoreEvolution.length === 0) {
    return { labels: [], datasets: [] }
  }

  return {
    labels: props.scoreEvolution.map((entry) => entry.date),
    datasets: [
      {
        label: 'Score',
        data: props.scoreEvolution.map((entry) => entry.total_score),
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
    <ChartLine v-if="scoreEvolution.length > 1" :data="chartData" :options="chartOptions" />
    <div v-else class="flex h-full items-center justify-center text-sm text-white/45">
      {{ __('Not enough data for score evolution') }}
    </div>
  </div>
</template>
