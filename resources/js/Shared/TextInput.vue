<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label dark:text-gray-100" :for="id">{{ label }}:</label>
    <div class="relative">
      <Icon v-if="prependIcon" :name="prependIcon" class="pointer-events-none absolute top-1/2 transform -translate-y-1/2 left-3 flex-shrink-0 mr-2 w-5 h-5 fill-gray-500 z-10"/>
      <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error, 'pl-10': prependIcon, 'pr-10': appendIcon }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
      <Icon v-if="appendIcon" :name="appendIcon" class="pointer-events-none absolute top-1/2 transform -translate-y-1/2 right-3 flex-shrink-0 ml-2 w-5 h-5 fill-gray-500 z-10"/>
      <svg v-if="loading" class="pointer-events-none absolute top-1/2 -mt-2 right-3 flex-shrink-0 ml-2 w-5 h-5 text-gray-400 z-10 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>


<script>
import { v4 as uuid } from 'uuid'
import Icon from '@/Shared/Icon'

export default {
  inheritAttrs: false,
  components: {
    Icon,
  },
  props: {
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
    modelValue: String|Number,
    appendIcon: String,
    prependIcon: String,
  },
  emits: ['update:modelValue'],
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
