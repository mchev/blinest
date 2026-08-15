<script setup>
defineProps({
  kicker: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    default: '',
  },
  border: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <section
    class="relative p-4"
    :class="{ 'border-b border-white/10': border }"
  >
    <header v-if="kicker || title" class="mb-3">
      <p v-if="kicker" class="game-section-kicker">{{ kicker }}</p>
      <h3 v-if="title" class="retro-title retro-title--secondary text-sm">
        {{ title }}
      </h3>
    </header>
    <slot />
  </section>
</template>
