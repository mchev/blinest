<script setup>
defineProps({
  size: {
    type: String,
    default: 'sm',
    validator: (value) => ['sm', 'md'].includes(value),
  },
})
</script>

<template>
  <span class="inline-flex items-center gap-1 rounded-full border border-rose-400/40 bg-rose-500/15 font-semibold text-rose-200" :class="size === 'md' ? 'px-2 py-0.5 text-[10px]' : 'px-1.5 py-0.5 text-[9px]'" :title="__('Supporter this month')">
    <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0" :class="size === 'md' ? 'h-3 w-3' : 'h-2.5 w-2.5'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
      <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
    </svg>
    {{ __('Supporter') }}
  </span>
</template>
