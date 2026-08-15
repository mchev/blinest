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
    class="p-4"
    :class="{ 'border-b border-zinc-800': border }"
  >
    <header v-if="kicker || title" class="mb-3">
      <p v-if="kicker" class="game-section-kicker">{{ kicker }}</p>
      <h3 v-if="title" class="text-sm font-semibold tracking-tight text-white">
        {{ title }}
      </h3>
    </header>
    <slot />
  </section>
</template>
