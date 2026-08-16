<script setup>
import Spinner from '@/Components/Spinner.vue'

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <Transition name="round-finalizing-fade">
    <div
      v-if="show"
      class="round-finalizing-overlay"
      role="status"
      aria-live="polite"
      :aria-label="__('Calculating scores and rankings...')"
    >
      <div class="round-finalizing-overlay__panel retro-chamfer rc-16">
        <div class="round-finalizing-overlay__pulse" aria-hidden="true"></div>
        <Spinner class="!mr-0 h-8 w-8 !fill-brand-accent text-white/20" />
        <p class="round-finalizing-overlay__title">{{ __('Calculating scores and rankings...') }}</p>
        <p class="round-finalizing-overlay__subtitle">{{ __('ELO and podium update in a moment') }}</p>
        <div class="retro-progress mt-4 w-full max-w-xs">
          <div class="retro-progress__fill retro-progress__fill--live round-finalizing-overlay__bar"></div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.round-finalizing-fade-enter-active,
.round-finalizing-fade-leave-active {
  transition: opacity 0.25s ease;
}

.round-finalizing-fade-enter-from,
.round-finalizing-fade-leave-to {
  opacity: 0;
}

.round-finalizing-overlay__bar {
  width: 35%;
  animation: finalizing-bar 1.4s ease-in-out infinite;
}

@keyframes finalizing-bar {
  0% {
    transform: translateX(-120%);
  }

  100% {
    transform: translateX(320%);
  }
}
</style>
