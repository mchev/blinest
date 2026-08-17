<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const snowflakes = ref([])
const stars = ref([])
let snowflakeInterval = null

const createSnowflake = () => {
  return {
    id: Math.random(),
    left: Math.random() * 100,
    size: Math.random() * 4 + 2,
    duration: Math.random() * 4 + 3,
    delay: Math.random() * 2,
    opacity: Math.random() * 0.5 + 0.3,
  }
}

const createStar = () => {
  return {
    id: Math.random(),
    left: Math.random() * 100,
    top: Math.random() * 100,
    size: Math.random() * 8 + 4,
    duration: Math.random() * 2 + 1.5,
    delay: Math.random() * 1,
  }
}

onMounted(() => {
  // Create snowflakes
  for (let i = 0; i < 15; i++) {
    snowflakes.value.push(createSnowflake())
  }

  // Create stars
  for (let i = 0; i < 6; i++) {
    stars.value.push(createStar())
  }

  // Add new snowflakes periodically
  snowflakeInterval = setInterval(() => {
    if (snowflakes.value.length < 20) {
      snowflakes.value.push(createSnowflake())
    }
    // Remove old snowflakes
    if (snowflakes.value.length > 0) {
      snowflakes.value.shift()
    }
  }, 2000)
})

onUnmounted(() => {
  if (snowflakeInterval) {
    clearInterval(snowflakeInterval)
  }
})
</script>

<template>
  <div class="christmas-decorations pointer-events-none absolute inset-0 z-0 overflow-hidden">
    <!-- Snowflakes -->
    <div
      v-for="snowflake in snowflakes"
      :key="snowflake.id"
      class="snowflake absolute select-none text-white"
      :style="{
        left: `${snowflake.left}%`,
        fontSize: `${snowflake.size}px`,
        opacity: snowflake.opacity,
        animation: `snowfall ${snowflake.duration}s linear ${snowflake.delay}s infinite`,
      }"
    >
      ❄
    </div>

    <!-- Twinkling Stars -->
    <div
      v-for="star in stars"
      :key="star.id"
      class="star absolute select-none text-yellow-300"
      :style="{
        left: `${star.left}%`,
        top: `${star.top}%`,
        fontSize: `${star.size}px`,
        animation: `twinkle ${star.duration}s cubic-bezier(0.4, 0, 0.6, 1) ${star.delay}s infinite`,
      }"
    >
      ✨
    </div>
  </div>
</template>

<style>
.christmas-decorations .snowflake {
  top: -20px;
  will-change: transform, opacity;
}

.christmas-decorations .star {
  will-change: opacity;
  filter: drop-shadow(0 0 3px rgba(255, 215, 0, 0.9));
  display: inline-block;
  line-height: 1;
}

@keyframes snowfall {
  0% {
    transform: translateY(0) rotate(0deg);
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    transform: translateY(120px) rotate(360deg);
    opacity: 0;
  }
}

@keyframes twinkle {
  0%,
  100% {
    opacity: 0.5;
  }
  50% {
    opacity: 1;
  }
}
</style>
