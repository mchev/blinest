<template>
  <section
    :class="[
      'squircle-modern flex h-auto flex-col surface-card',
      'transition-all duration-200 ease-out',
      'hover:border-red-500/25 hover:shadow-xl hover:shadow-black/20',
      'focus-within:border-red-500/25 focus-within:ring-2 focus-within:ring-red-500/15',
      'focus-visible:outline-none',
    ]"
    role="region"
    :aria-labelledby="$slots.header ? `${id}-header` : undefined"
  >
    <header
      v-if="$slots.header"
      :id="`${id}-header`"
      :class="[
        'px-5 py-3 sm:px-6 sm:py-3.5',
        'border-b border-white/10',
        'bg-gradient-to-b from-surface-hover/20 to-transparent',
        'backdrop-blur-sm',
        'flex-shrink-0',
      ]"
    >
      <div class="flex items-center gap-3">
        <slot name="header" />
      </div>
    </header>
    <main
      :class="[
        $slots.header ? 'px-5 py-5 sm:px-6 sm:py-6' : 'px-5 py-5 sm:px-6 sm:py-6',
      ]"
    >
      <slot />
    </main>
    <footer
      v-if="$slots.footer"
      :class="[
        'px-5 py-4 sm:px-6 sm:py-5',
        'border-t border-white/10',
        'bg-gradient-to-t from-surface-hover/25 to-transparent',
        'backdrop-blur-sm',
      ]"
    >
      <slot name="footer" />
    </footer>
  </section>
</template>

<script setup>
const props = defineProps({
  id: {
    type: String,
    default: () => `card-${Math.random().toString(36).substr(2, 9)}`,
  },
})
</script>
