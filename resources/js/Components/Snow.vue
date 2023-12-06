<template>
  <div class="snowfall-container">
    <div v-for="flake in flakes" :key="flake.id" class="snowflake" :style="{ top: flake.y + 'px', left: flake.x + 'px' }"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const flakes = ref([])
let idCounter = 0

const createFlake = () => ({
  id: idCounter++,
  x: Math.random() * window.innerWidth,
  y: Math.random() * window.innerHeight,
  speed: Math.random() * 2 + 1,
})

const moveFlakes = () => {
  flakes.value.forEach((flake, index) => {
    flake.y += flake.speed

    if (flake.y > window.innerHeight) {
      flakes.value.splice(index, 1, createFlake())
    }
  })
}

onMounted(() => {
  for (let i = 0; i < 25; i++) {
    flakes.value.push(createFlake())
  }

  setInterval(moveFlakes, 30)
})
</script>

<style scoped>
.snowfall-container {
  position: fixed;
  top: 0;
  left: 0;
  pointer-events: none;
  width: 100%;
  height: 100%;
  z-index: 9999;
}

.snowflake {
  @apply absolute h-2 w-2 rounded-full bg-white;
  animation: fall linear infinite;
}

@keyframes fall {
  to {
    transform: translateY(100vh);
  }
}
</style>
