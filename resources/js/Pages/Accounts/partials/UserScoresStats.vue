<script setup>
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const props = defineProps({
  summary: Object,
})

const data = {
  labels: props.summary.map(x => x.room.name),
  datasets: [{
    label: "Scores par room",
    data: props.summary.map(x => x.score),
    backgroundColor: 'rgba(255, 99, 132, 0.2)',
  }],
}
const options = {
  responsive: true,
}
</script>
<template>
  <Bar :data="data" :options="options" />
</template>
