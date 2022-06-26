<script>
export default {
  inheritAttrs: false,
}
</script>
<script setup>
import { v4 as uuid } from 'uuid'
import Icon from '@/Components/Icon'

defineProps({
  id: {
    type: String,
    default() {
      return `text-input-${uuid()}`
    },
  },
  type: {
    type: String,
    default: 'text',
  },
  loading: Boolean,
  error: String,
  label: String,
  modelValue: String | Number,
  appendIcon: String,
  prependIcon: String,
})

defineEmits(['update:modelValue'])
</script>
<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <div class="relative">
      <Icon v-if="prependIcon" :name="prependIcon" class="pointer-events-none absolute top-1/2 left-3 z-10 mr-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500" />
      <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error, 'pl-10': prependIcon, 'pr-10': appendIcon }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
      <Icon v-if="appendIcon" :name="appendIcon" class="pointer-events-none absolute top-1/2 right-3 z-10 ml-2 h-5 w-5 flex-shrink-0 -translate-y-1/2 transform fill-gray-500" />
      <svg v-if="loading" class="pointer-events-none absolute top-1/2 right-3 z-10 -mt-2 ml-2 h-5 w-5 flex-shrink-0 animate-spin text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
      </svg>
    </div>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
