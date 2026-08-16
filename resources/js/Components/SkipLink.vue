<script setup>
defineProps({
  targetId: {
    type: String,
    default: 'main-content',
  },
})
</script>

<template>
  <a :href="`#${targetId}`" class="skip-link">
    {{ __('Skip to main content') }}
  </a>
</template>
