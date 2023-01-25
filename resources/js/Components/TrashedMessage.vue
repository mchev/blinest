<script setup>
import Icon from '@/Components/Icon.vue'

defineEmits(['restore'])
</script>
<template>
  <div class="flex max-w-3xl items-center justify-between rounded bg-yellow-400 p-4">
    <div class="flex items-center">
      <icon name="trash" class="mr-2 h-4 w-4 flex-shrink-0 fill-yellow-800" />
      <div class="text-sm font-medium text-yellow-800">
        <slot />
      </div>
    </div>
    <button class="text-sm text-yellow-800 hover:underline" tabindex="-1" type="button" @click="$emit('restore')">Restore</button>
  </div>
</template>
