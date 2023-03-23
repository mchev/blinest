<script setup>
import Dropdown from '@/Components/Dropdown.vue'

defineProps({
  modelValue: String,
  maxWidth: {
    type: Number,
    default: 300,
  },
})

defineEmits(['update:modelValue', 'reset'])
</script>
<template>
  <div class="flex items-center">
    <div class="flex w-full rounded bg-neutral-700 shadow">
      <dropdown v-if="$slots.default" :auto-close="false" class="rounded-l border-r px-4 hover:bg-neutral-500 focus:z-10 focus:border-white focus:ring md:px-6" placement="left">
        <template #default>
          <div class="flex items-center">
            <span class="hidden md:inline">{{ __('Filter') }}</span>
            <svg class="h-2 w-2 md:ml-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 961.243 599.998">
              <path d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z" />
            </svg>
          </div>
        </template>
        <template #dropdown>
          <div class="mt-2 w-screen rounded bg-white px-4 py-6 shadow-xl" :style="{ maxWidth: `${maxWidth}px` }">
            <slot />
          </div>
        </template>
      </dropdown>
      <input class="focus:shadow-outline relative w-full rounded-r px-6 py-3" :class="{ 'rounded-l': !$slots.default }" autocomplete="off" type="text" name="search" :placeholder="__('Search…')" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <button class="ml-3 text-sm" type="button" @click="$emit('reset')">{{ __('Reset') }}</button>
  </div>
</template>
