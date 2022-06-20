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

<script>
import { defineComponent } from 'vue'
import Modal from './Modal.vue'

export default defineComponent({
  components: {
    Modal,
  },

  props: {
    show: {
      default: false,
    },
    maxWidth: {
      default: '2xl',
    },
    closeable: {
      default: true,
    },
  },
  emits: ['close'],

  methods: {
    close() {
      this.$emit('close')
    },
  },
})
</script>
