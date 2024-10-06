<script setup>
import { ref, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Clip from '@/Components/Clip.vue'

defineProps({
  show: Boolean,
  url: String,
})

defineEmits(['close'])

</script>
<template>
  <Modal :show="show" @close="$emit('close')">
    <div class="p-6">
      <h2 class="my-4 text-xl">{{ __('Share this page') }}</h2>
      <p class="mb-1 text-sm">{{ __('Copy URL:') }}</p>
      <Clip class="mb-4" :text="url" />
      <a class="btn-primary my-2" target="_blank" :href="`https://www.facebook.com/sharer/sharer.php?u=${url}`">{{ __('Share on Facebook') }}</a>
      <a class="btn-primary my-2" target="_blank" :href="`https://twitter.com/intent/tweet?&url=${url}`">{{ __('Share on X') }}</a>
      <button class="btn-secondary mt-4 ml-auto" @click="$emit('close')">{{ __('Close') }}</button>
    </div>
  </Modal>
</template>