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
    default: 'primary',
    validator: (v) => ['primary', 'accent', 'teal'].includes(v),
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const accentClass = (accent) => {
  if (accent === 'accent' || accent === 'teal') {
    return { bar: 'retro-title-bar__accent--accent', title: 'retro-title--accent' }
  }

  return { bar: 'retro-title-bar__accent--primary', title: 'retro-title--primary' }
}
</script>

<template>
  <header v-if="compact" class="retro-section-header">
    <h2 class="retro-title retro-title--primary min-w-0 flex-1">
      {{ title }}
    </h2>
    <div v-if="$slots.action" class="shrink-0">
      <slot name="action" />
    </div>
  </header>

  <div v-else class="mb-6 space-y-2">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
      <div class="min-w-0 flex-1">
        <p v-if="kicker" class="game-section-kicker mb-2">{{ kicker }}</p>
        <div class="retro-title-bar">
          <span
            class="retro-title-bar__accent"
            :class="accentClass(accent).bar"
          />
          <h2
            class="retro-title min-w-0 flex-1 truncate sm:text-2xl"
            :class="accentClass(accent).title"
          >
            {{ title }}
          </h2>
        </div>
      </div>
      <div v-if="$slots.action" class="w-full shrink-0 sm:w-auto">
        <slot name="action" />
      </div>
    </div>
    <p
      v-if="subtitle || $slots.subtitle"
      class="text-xs leading-relaxed text-white/70 sm:text-sm"
    >
      <slot name="subtitle">{{ subtitle }}</slot>
    </p>
  </div>
</template>
