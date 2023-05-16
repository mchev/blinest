<template>
  <div :class="$attrs.class">
    <label v-if="label" class="flex cursor-pointer items-center" :for="id">
      <div class="relative">
        <input :id="id" v-bind="{ ...$attrs, class: null }" :value="modelValue" :checked="modelValue" type="checkbox" class="sr-only" @input="$emit('update:modelValue', $event.target.checked)" />
        <div class="h-3 w-8 rounded-full bg-neutral-500 shadow-inner" />
        <div class="dot absolute -left-1 -top-1 h-5 w-5 rounded-full bg-neutral-200 shadow transition" />
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
    modelValue: [Boolean, Number, String],
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
  background-color: rgb(13, 148, 136);
}
</style>
