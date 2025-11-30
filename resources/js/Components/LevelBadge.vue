<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  level: {
    type: Number,
    default: 1,
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  showText: {
    type: Boolean,
    default: true,
  },
  variant: {
    type: String,
    default: 'default', // 'default', 'compact', 'minimal'
    validator: (value) => ['default', 'compact', 'minimal'].includes(value),
  },
  currentXp: {
    type: Number,
    default: null,
  },
  xpForNextLevel: {
    type: Number,
    default: null,
  },
  isLevelUp: {
    type: Boolean,
    default: false,
  },
  isUpdating: {
    type: Boolean,
    default: false,
  },
})

const page = usePage()
const user = page.props.auth?.user

// Get XP data from props or user
const currentXpValue = computed(() => {
  return props.currentXp !== null ? props.currentXp : (user?.current_xp ?? 0)
})

const xpForNext = computed(() => {
  return props.xpForNextLevel !== null ? props.xpForNextLevel : (user?.xp_for_next_level ?? 100)
})

// Calculate progress percentage
const progressPercentage = computed(() => {
  if (xpForNext.value === 0) return 100
  return Math.min((currentXpValue.value / xpForNext.value) * 100, 100)
})

// Calculate circumference for progress ring (2 * π * r)
const radius = computed(() => {
  const configs = {
    sm: 12,
    md: 17,
    lg: 17,
  }
  return configs[props.size]
})

const circumference = computed(() => 2 * Math.PI * radius.value)
const strokeDashoffset = computed(() => circumference.value - (progressPercentage.value / 100) * circumference.value)

// Couleurs selon le niveau
const levelColor = computed(() => {
  if (props.level >= 50) return '#a855f7'
  if (props.level >= 30) return '#eab308'
  if (props.level >= 20) return '#3b82f6'
  if (props.level >= 10) return '#22c55e'
  return '#737373'
})

const sizeConfig = computed(() => {
  const configs = {
    sm: {
      size: 28,
      strokeWidth: 2.5,
      numberSize: 'text-[10px]',
      container: 'px-0.5',
    },
    md: {
      size: 40,
      strokeWidth: 3,
      numberSize: 'text-xs',
      container: 'px-1',
    },
    lg: {
      size: 40,
      strokeWidth: 3.5,
      numberSize: 'text-sm',
      container: 'px-1',
    },
  }
  return configs[props.size]
})
</script>

<template>
  <div
    v-if="variant !== 'minimal'"
    :class="[
      'inline-flex items-center justify-center',
      sizeConfig.container,
      isLevelUp ? 'animate-level-up' : '',
      isUpdating && !isLevelUp ? 'animate-xp-update' : '',
    ]"
    :title="`${__('Level')} ${level}`"
  >
    <!-- Simple circle with progress ring (GeoGuessr style) -->
    <div 
      class="relative"
      :class="[
        isLevelUp ? 'animate-scale-pulse' : '',
      ]"
      :style="`width: ${sizeConfig.size}px; height: ${sizeConfig.size}px;`"
    >
      <svg
        :width="sizeConfig.size"
        :height="sizeConfig.size"
        class="transform -rotate-90"
      >
        <!-- Background circle (full) -->
        <circle
          :cx="sizeConfig.size / 2"
          :cy="sizeConfig.size / 2"
          :r="radius"
          :stroke-width="sizeConfig.strokeWidth"
          stroke="rgba(255, 255, 255, 0.1)"
          fill="none"
        />
        
        <!-- Progress circle -->
        <circle
          :cx="sizeConfig.size / 2"
          :cy="sizeConfig.size / 2"
          :r="radius"
          :stroke-width="sizeConfig.strokeWidth"
          :stroke="levelColor"
          fill="none"
          :stroke-dasharray="circumference"
          :stroke-dashoffset="strokeDashoffset"
          stroke-linecap="round"
          :class="[
            'transition-all duration-500 ease-out',
            isLevelUp ? 'animate-glow-pulse' : '',
          ]"
        />
      </svg>

      <!-- Level number (centered) -->
      <div
        class="absolute inset-0 flex items-center justify-center pointer-events-none"
      >
        <span
          :class="[
            'font-extrabold',
            'leading-none',
            sizeConfig.numberSize,
            'transition-all duration-300',
            isLevelUp ? 'animate-number-bounce' : isUpdating ? 'animate-number-pulse' : '',
          ]"
          :style="`color: ${levelColor};`"
        >
          {{ level }}
        </span>
      </div>
    </div>
  </div>

  <!-- Variant minimal : étoile simple -->
  <div
    v-else
    class="inline-flex items-center justify-center gap-1"
    :title="`${__('Level')} ${level}`"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="currentColor"
      :class="[
        size === 'sm' ? 'h-4 w-4' : size === 'md' ? 'h-5 w-5' : 'h-6 w-6',
        props.level >= 50 ? 'text-purple-400' :
        props.level >= 30 ? 'text-yellow-400' :
        props.level >= 20 ? 'text-blue-400' :
        props.level >= 10 ? 'text-green-400' :
        'text-neutral-400'
      ]"
    >
      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
    </svg>
    <span
      v-if="showText"
      :class="[
        'font-bold',
        sizeConfig.numberSize,
        props.level >= 50 ? 'text-purple-400' :
        props.level >= 30 ? 'text-yellow-400' :
        props.level >= 20 ? 'text-blue-400' :
        props.level >= 10 ? 'text-green-400' :
        'text-neutral-400'
      ]"
    >
      {{ level }}
    </span>
  </div>
</template>
