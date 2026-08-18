<script setup>
import { ref } from 'vue'

defineProps({
  label: {
    type: String,
    required: true,
  },
  tooltip: {
    type: String,
    required: true,
  },
  labelClass: {
    type: String,
    default: '',
  },
  variant: {
    type: String,
    default: 'neutral',
  },
  placement: {
    type: String,
    default: 'top',
  },
})

const isOpen = ref(false)

const toggle = (event) => {
  event.preventDefault()
  event.stopPropagation()
  isOpen.value = !isOpen.value
}

const close = () => {
  isOpen.value = false
}

const iconClass = (variant) => {
  return variant === 'brand' ? 'text-white/40 hover:text-white/70' : 'text-neutral-500 hover:text-neutral-300'
}

const tooltipClass = (variant) => {
  return variant === 'brand' ? 'border-white/15 bg-brand-midnight text-white/80' : 'border-neutral-700 bg-neutral-900 text-neutral-300'
}

const tooltipPositionClass = (placement) => {
  return placement === 'bottom' ? 'top-full left-0 mt-1.5' : 'bottom-full left-0 mb-1.5'
}
</script>

<template>
  <div class="group/metric relative inline-flex max-w-full items-center gap-0.5">
    <span :class="labelClass">{{ label }}</span>
    <button type="button" class="inline-flex h-3.5 w-3.5 shrink-0 items-center justify-center rounded-full transition-colors focus:outline-none focus:ring-1 focus:ring-yellow-500/40" :class="iconClass(variant)" :aria-label="tooltip" @click="toggle" @blur="close">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5" aria-hidden="true">
        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
      </svg>
    </button>
    <div role="tooltip" class="pointer-events-none absolute z-30 w-44 rounded-md border px-2 py-1.5 text-[10px] font-normal normal-case leading-snug tracking-normal shadow-xl transition-opacity sm:w-52" :class="[tooltipClass(variant), tooltipPositionClass(placement), isOpen ? 'opacity-100' : 'opacity-0 group-hover/metric:opacity-100']">
      {{ tooltip }}
    </div>
  </div>
</template>
