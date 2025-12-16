<template>
  <section
    :class="[
      'flex flex-col bg-slate-800/50 backdrop-blur-sm shadow-lg',
      'border border-neutral-700/50',
      'squircle-modern',
      'h-auto',
      'transition-all duration-200 ease-out',
      'hover:border-neutral-600/50 hover:shadow-xl',
      'focus-within:ring-2 focus-within:ring-neutral-500/20 focus-within:border-neutral-600',
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
        'border-b border-neutral-700/20',
        'bg-gradient-to-b from-neutral-800/20 via-neutral-800/10 to-transparent',
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
        'border-t border-neutral-700/20',
        'bg-gradient-to-t from-neutral-800/40 via-neutral-800/20 to-transparent',
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
