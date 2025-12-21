<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  elo: {
    type: Number,
    default: 1500,
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  variant: {
    type: String,
    default: 'default', // 'default', 'compact', 'minimal', 'rank'
    validator: (value) => ['default', 'compact', 'minimal', 'rank'].includes(value),
  },
  showChange: {
    type: Boolean,
    default: false,
  },
  eloChange: {
    type: Number,
    default: null, // Positive or negative change
  },
  clickable: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['click'])

const page = usePage()

// Translation function
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

// ELO ranges and colors (inspired by League of Legends, CS:GO, etc.)
// Couleurs optimisées pour l'accessibilité avec contraste WCAG AA minimum (4.5:1)
const eloRanges = [
  { 
    min: 0, 
    max: 999, 
    name: 'Bronze', 
    color: '#cd7f32', 
    bgGradient: 'from-amber-700 to-amber-800', 
    bgGradientDark: 'from-amber-800 to-amber-900',
    borderColor: 'border-amber-600/60',
    textColor: 'text-amber-200',
    textColorMinimal: 'text-amber-400',
    rank: 'I' 
  },
  { 
    min: 1000, 
    max: 1199, 
    name: 'Silver', 
    color: '#c0c0c0', 
    bgGradient: 'from-slate-500 to-slate-600', 
    bgGradientDark: 'from-slate-600 to-slate-700',
    borderColor: 'border-slate-400/60',
    borderColorClass: 'border-slate-400/60',
    textColor: 'text-slate-100',
    textColorMinimal: 'text-slate-300',
    rank: 'I' 
  },
  { 
    min: 1200, 
    max: 1399, 
    name: 'Silver', 
    color: '#c0c0c0', 
    bgGradient: 'from-slate-500 to-slate-600', 
    bgGradientDark: 'from-slate-600 to-slate-700',
    borderColor: 'border-slate-400/60',
    borderColorClass: 'border-slate-400/60',
    textColor: 'text-slate-100',
    textColorMinimal: 'text-slate-300',
    rank: 'II' 
  },
  { 
    min: 1400, 
    max: 1599, 
    name: 'Gold', 
    color: '#ffd700', 
    bgGradient: 'from-yellow-500 to-yellow-600', 
    bgGradientDark: 'from-yellow-600 to-yellow-700',
    borderColor: 'border-yellow-400/60',
    borderColorClass: 'border-yellow-400/60',
    textColor: 'text-yellow-50',
    textColorMinimal: 'text-yellow-300',
    rank: 'I' 
  },
  { 
    min: 1600, 
    max: 1799, 
    name: 'Gold', 
    color: '#ffd700', 
    bgGradient: 'from-yellow-500 to-yellow-600', 
    bgGradientDark: 'from-yellow-600 to-yellow-700',
    borderColor: 'border-yellow-400/60',
    borderColorClass: 'border-yellow-400/60',
    textColor: 'text-yellow-50',
    textColorMinimal: 'text-yellow-300',
    rank: 'II' 
  },
  { 
    min: 1800, 
    max: 1999, 
    name: 'Platinum', 
    color: '#00d4aa', 
    bgGradient: 'from-teal-500 to-teal-600', 
    bgGradientDark: 'from-teal-600 to-teal-700',
    borderColor: 'border-teal-400/60',
    borderColorClass: 'border-teal-400/60',
    textColor: 'text-teal-50',
    textColorMinimal: 'text-teal-300',
    rank: 'I' 
  },
  { 
    min: 2000, 
    max: 2199, 
    name: 'Platinum', 
    color: '#00d4aa', 
    bgGradient: 'from-teal-500 to-teal-600', 
    bgGradientDark: 'from-teal-600 to-teal-700',
    borderColor: 'border-teal-400/60',
    borderColorClass: 'border-teal-400/60',
    textColor: 'text-teal-50',
    textColorMinimal: 'text-teal-300',
    rank: 'II' 
  },
  { 
    min: 2200, 
    max: 2399, 
    name: 'Diamond', 
    color: '#00b8ff', 
    bgGradient: 'from-blue-500 to-blue-600', 
    bgGradientDark: 'from-blue-600 to-blue-700',
    borderColor: 'border-blue-400/60',
    borderColorClass: 'border-blue-400/60',
    textColor: 'text-blue-50',
    textColorMinimal: 'text-blue-300',
    rank: 'I' 
  },
  { 
    min: 2400, 
    max: 2599, 
    name: 'Diamond', 
    color: '#00b8ff', 
    bgGradient: 'from-blue-500 to-blue-600', 
    bgGradientDark: 'from-blue-600 to-blue-700',
    borderColor: 'border-blue-400/60',
    borderColorClass: 'border-blue-400/60',
    textColor: 'text-blue-50',
    textColorMinimal: 'text-blue-300',
    rank: 'II' 
  },
  { 
    min: 2600, 
    max: 2799, 
    name: 'Master', 
    color: '#9d4edd', 
    bgGradient: 'from-purple-600 to-purple-700', 
    bgGradientDark: 'from-purple-700 to-purple-800',
    borderColor: 'border-purple-500/60',
    borderColorClass: 'border-purple-500/60',
    textColor: 'text-purple-50',
    textColorMinimal: 'text-purple-300',
    rank: 'I' 
  },
  { 
    min: 2800, 
    max: 2999, 
    name: 'Master', 
    color: '#9d4edd', 
    bgGradient: 'from-purple-600 to-purple-700', 
    bgGradientDark: 'from-purple-700 to-purple-800',
    borderColor: 'border-purple-500/60',
    borderColorClass: 'border-purple-500/60',
    textColor: 'text-purple-50',
    textColorMinimal: 'text-purple-300',
    rank: 'II' 
  },
  { 
    min: 3000, 
    max: Infinity, 
    name: 'Grandmaster', 
    color: '#ff006e', 
    bgGradient: 'from-pink-600 to-pink-700', 
    bgGradientDark: 'from-pink-700 to-pink-800',
    borderColor: 'border-pink-500/60',
    borderColorClass: 'border-pink-500/60',
    textColor: 'text-pink-50',
    textColorMinimal: 'text-pink-300',
    rank: '' 
  },
]

const eloInfo = computed(() => {
  const eloValue = Math.max(100, Math.min(props.elo, 10000)) // Clamp between 100 and 10000
  return eloRanges.find(range => eloValue >= range.min && eloValue <= range.max) || eloRanges[eloRanges.length - 1]
})

const sizeConfig = computed(() => {
  const configs = {
    sm: {
      textSize: 'text-xs',
      numberSize: 'text-[10px]',
      padding: 'px-1.5 py-0.5',
      iconSize: 'h-3 w-3',
    },
    md: {
      textSize: 'text-sm',
      numberSize: 'text-xs',
      padding: 'px-2 py-1',
      iconSize: 'h-4 w-4',
    },
    lg: {
      textSize: 'text-base',
      numberSize: 'text-sm',
      padding: 'px-3 py-1.5',
      iconSize: 'h-5 w-5',
    },
  }
  return configs[props.size]
})

const handleClick = () => {
  if (props.clickable) {
    emit('click', {
      elo: props.elo,
      eloInfo: eloInfo.value,
    })
  }
}
</script>

<template>
  <!-- Default variant: Badge with rank name and ELO -->
  <div
    v-if="variant === 'default'"
    :class="[
      'inline-flex items-center gap-1.5 rounded-lg border font-semibold transition-all shadow-md',
      `bg-gradient-to-r ${eloInfo.bgGradientDark}`,
      sizeConfig.padding,
      clickable ? 'cursor-pointer hover:scale-105 hover:shadow-lg' : '',
      // Border colors by rank
      eloInfo.name === 'Bronze' ? 'border-amber-600/60' :
      eloInfo.name === 'Silver' ? 'border-slate-400/60' :
      eloInfo.name === 'Gold' ? 'border-yellow-400/60' :
      eloInfo.name === 'Platinum' ? 'border-teal-400/60' :
      eloInfo.name === 'Diamond' ? 'border-blue-400/60' :
      eloInfo.name === 'Master' ? 'border-purple-500/60' :
      'border-pink-500/60',
    ]"
    :title="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
    @click="handleClick"
    role="status"
    :aria-label="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
  >
    <span :class="[sizeConfig.textSize, 'font-bold text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']">
      {{ eloInfo.name }}
      <span v-if="eloInfo.rank" class="text-white/95">{{ eloInfo.rank }}</span>
    </span>
    <span :class="[sizeConfig.numberSize, 'text-white font-extrabold drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']">{{ elo }}</span>
    <span v-if="showChange && eloChange !== null" :class="[
      sizeConfig.numberSize,
      'font-bold drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]',
      eloChange > 0 ? 'text-green-100' : eloChange < 0 ? 'text-red-100' : 'text-white/90'
    ]">
      {{ eloChange > 0 ? '+' : '' }}{{ eloChange }}
    </span>
  </div>

  <!-- Compact variant: Just ELO number with colored background -->
  <div
    v-else-if="variant === 'compact'"
    :class="[
      'inline-flex items-center justify-center rounded-md font-bold border shadow-sm',
      `bg-gradient-to-br ${eloInfo.bgGradientDark}`,
      sizeConfig.padding,
      clickable ? 'cursor-pointer hover:scale-105 hover:shadow-md' : '',
      // Border colors by rank
      eloInfo.name === 'Bronze' ? 'border-amber-600/60' :
      eloInfo.name === 'Silver' ? 'border-slate-400/60' :
      eloInfo.name === 'Gold' ? 'border-yellow-400/60' :
      eloInfo.name === 'Platinum' ? 'border-teal-400/60' :
      eloInfo.name === 'Diamond' ? 'border-blue-400/60' :
      eloInfo.name === 'Master' ? 'border-purple-500/60' :
      'border-pink-500/60',
    ]"
    :title="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
    @click="handleClick"
    role="status"
    :aria-label="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
  >
    <span :class="[sizeConfig.numberSize, 'text-white font-extrabold drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']">{{ elo }}</span>
  </div>

  <!-- Minimal variant: Just colored text with better contrast -->
  <div
    v-else-if="variant === 'minimal'"
    :class="[
      'inline-flex items-center gap-1',
      eloInfo.textColorMinimal,
      clickable ? 'cursor-pointer hover:opacity-90' : '',
    ]"
    :title="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
    @click="handleClick"
    role="status"
    :aria-label="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
  >
    <span :class="[sizeConfig.numberSize, 'font-bold']">{{ elo }}</span>
  </div>

  <!-- Rank variant: Full rank display with icon -->
  <div
    v-else-if="variant === 'rank'"
    :class="[
      'inline-flex items-center gap-2 rounded-xl border-2 px-3 py-2 font-bold shadow-lg',
      `bg-gradient-to-r ${eloInfo.bgGradientDark}`,
      clickable ? 'cursor-pointer hover:scale-105 hover:shadow-xl' : '',
      // Border colors by rank
      eloInfo.name === 'Bronze' ? 'border-amber-600/60' :
      eloInfo.name === 'Silver' ? 'border-slate-400/60' :
      eloInfo.name === 'Gold' ? 'border-yellow-400/60' :
      eloInfo.name === 'Platinum' ? 'border-teal-400/60' :
      eloInfo.name === 'Diamond' ? 'border-blue-400/60' :
      eloInfo.name === 'Master' ? 'border-purple-500/60' :
      'border-pink-500/60',
    ]"
    :title="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
    @click="handleClick"
    role="status"
    :aria-label="`${eloInfo.name} ${eloInfo.rank} - ${elo} ELO`"
  >
    <div class="flex items-center gap-1.5">
      <svg
        v-if="eloInfo.name === 'Grandmaster'"
        :class="[sizeConfig.iconSize, 'text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']"
        fill="currentColor"
        viewBox="0 0 24 24"
        aria-hidden="true"
      >
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
      </svg>
      <svg
        v-else
        :class="[sizeConfig.iconSize, 'text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']"
        fill="currentColor"
        viewBox="0 0 24 24"
        aria-hidden="true"
      >
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
      </svg>
      <span :class="[sizeConfig.textSize, 'text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']">
        {{ eloInfo.name }}
        <span v-if="eloInfo.rank" class="text-white/95">{{ eloInfo.rank }}</span>
      </span>
    </div>
    <div class="h-4 w-px bg-white/40" aria-hidden="true"></div>
    <span :class="[sizeConfig.numberSize, 'text-white font-extrabold drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]']">{{ elo }}</span>
  </div>
</template>

