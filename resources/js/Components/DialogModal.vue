<script setup>
import Modal from './Modal.vue'

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['close'])

const close = () => {
  emit('close')
}
</script>
<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <div class="px-6 py-4">
      <div class="text-lg">
        <slot name="title" />
      </div>

      <div class="mt-4">
        <slot name="content" />
      </div>
    </div>

    <div class="bg-gray-100 px-6 py-4 text-right">
      <slot name="footer" />
    </div>
  </modal>
</template>
