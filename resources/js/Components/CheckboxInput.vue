<template>
  <div :class="$attrs.class">
    <label v-if="label" class="flex cursor-pointer items-center" :for="id">
      <div class="relative">
        <input :id="id" v-bind="{ ...$attrs, class: null }" :value="modelValue" :checked="modelValue" type="checkbox" class="sr-only" @input="$emit('update:modelValue', $event.target.checked)" />
        <div class="h-4 w-10 rounded-full bg-gray-400 shadow-inner" />
        <div class="dot absolute -left-1 -top-1 h-6 w-6 rounded-full bg-white shadow transition" />
      </div>
      <div v-if="label" class="ml-3 font-medium">
        {{ label }}
      </div>
    </label>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'

export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `checkbox-input-${uuid()}`
      },
    },
    error: String,
    label: String,
    modelValue: [Boolean, Number],
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

<style scoped>
input:checked ~ .dot {
  transform: translateX(100%);
  background-color: #48bb78;
}
</style>
