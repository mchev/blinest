<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: '',
  },
  kicker: {
    type: String,
    default: '',
  },
  accent: {
    type: String,
    default: 'red',
  },
})
</script>

<template>
  <div class="mb-4 space-y-2 border-b border-zinc-800 pb-3">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
      <div class="min-w-0 flex-1">
        <p v-if="kicker" class="game-section-kicker">{{ kicker }}</p>
        <div class="flex items-center gap-3">
          <span
            class="h-8 w-1 shrink-0 rounded-full"
            :class="accent === 'teal' ? 'bg-teal-500' : 'bg-red-500'"
          />
          <h2 class="truncate text-xl font-bold tracking-tight text-white sm:text-2xl">
            {{ title }}
          </h2>
        </div>
      </div>
      <div v-if="$slots.action" class="w-full shrink-0 sm:w-auto sm:pt-1">
        <slot name="action" />
      </div>
    </div>
    <p
      v-if="subtitle || $slots.subtitle"
      class="w-full text-xs leading-relaxed text-zinc-400 sm:text-sm"
    >
      <slot name="subtitle">{{ subtitle }}</slot>
    </p>
  </div>
</template>
