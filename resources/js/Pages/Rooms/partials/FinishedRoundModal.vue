<script setup>
import { ref } from 'vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  show: Boolean,
})

const emit = defineEmits(['close']);

const close = () => {
  emit('close');
}

</script>
<template>

    <Modal :show="show" @close="close">
      Round finished
    </Modal>

</template>
