<script>
export default {
  inheritAttrs: false,
}
</script>
<script setup>
import { useAttrs } from 'vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  field: {
    type: String,
    default: null,
  },
})

const attrs = useAttrs()

const emit = defineEmits(['update:modelValue'])

const select = () => {
  emit('update:modelValue', {
    field: props.field,
    direction: attrs.modelValue && attrs.modelValue.direction === 'asc' ? 'desc' : 'asc',
  })
}
</script>
<template>
  <div :class="$attrs.class" class="cursor-pointer" @click="select">
    <slot />
    <div v-if="$attrs.modelValue && field == $attrs.modelValue.field" class="ml-1 inline-block align-middle">
      <icon v-if="$attrs.modelValue.direction === 'asc'" name="arrow-down" class="block h-4 w-4 fill-gray-400" />
      <icon v-else name="arrow-up" class="block h-4 w-4 fill-gray-400" />
    </div>
  </div>
</template>
