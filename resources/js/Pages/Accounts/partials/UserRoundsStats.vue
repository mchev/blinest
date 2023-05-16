<script setup>
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const props = defineProps({
  rounds: Object,
})

const data = {
  labels: props.rounds.map(x => x.date),
  datasets: [{
    label: "Score",
    data: props.rounds.map(x => x.score),
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
