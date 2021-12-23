<template>
  <div :class="$attrs.class">
    <label v-if="label" class="flex items-center cursor-pointer" :for="id">
      <div class="relative">
        <input :id="id" v-bind="{ ...$attrs, class: null }" :value="modelValue" :checked="modelValue" @input="$emit('update:modelValue', $event.target.checked)" type="checkbox" class="sr-only" />
        <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
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
    modelValue: Boolean,
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
