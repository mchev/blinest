<template>
  <section
    :class="['retro-card transition-all duration-200 ease-out focus-visible:outline-none']"
    role="region"
    :aria-labelledby="$slots.header ? `${id}-header` : undefined"
  >
    <header
      v-if="$slots.header"
      :id="`${id}-header`"
      class="retro-card__header"
    >
      <div class="flex items-center gap-3">
        <slot name="header" />
      </div>
    </header>
    <main :class="$slots.header ? 'retro-card__body' : 'retro-card__body'">
      <slot />
    </main>
    <footer v-if="$slots.footer" class="retro-card__footer">
      <slot name="footer" />
    </footer>
  </section>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    default: () => `card-${Math.random().toString(36).substr(2, 9)}`,
  },
})
</script>
