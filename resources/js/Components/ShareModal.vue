<script setup>
import Modal from '@/Components/Modal.vue'
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
      <h2 class="retro-title retro-title--primary mb-4 text-xl">{{ __('Share this page') }}</h2>
      <p class="mb-1 text-sm text-white/70">{{ __('Copy URL:') }}</p>
      <Clip class="mb-6" :text="url" />
      <div class="flex flex-col gap-2 sm:flex-row">
        <a class="game-btn-play-live !min-h-[2.75rem] flex-1 text-center" target="_blank" :href="`https://www.facebook.com/sharer/sharer.php?u=${url}`">{{ __('Share on Facebook') }}</a>
        <a class="game-btn-play-neutral !min-h-[2.75rem] flex-1 text-center" target="_blank" :href="`https://twitter.com/intent/tweet?&url=${url}`">{{ __('Share on X') }}</a>
      </div>
      <div class="mt-6 flex justify-end">
        <button type="button" class="game-btn-secondary" @click="$emit('close')">{{ __('Close') }}</button>
      </div>
    </div>
  </Modal>
</template>
