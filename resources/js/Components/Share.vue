<script setup>
import { ref, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Clip from '@/Components/Clip.vue'

defineProps({
  url: String,
})

const show = ref(false)
</script>
<template>
  <div id="fb-root"></div>
  <button @click="show = true" :class="$attrs.class" :title="__('Share')">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-full w-full">
      <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
    </svg>
  </button>

  <Modal :show="show" @close="show = false">
    <div class="p-6">
      <h2 class="my-4 text-xl">{{ __('Share this page') }}</h2>
      <p class="mb-1 text-sm">Copier le lien :</p>
      <Clip class="mb-4" :text="url" />
      <a class="btn-primary my-2" target="_blank" :href="`https://www.facebook.com/sharer/sharer.php?u=${url}`">{{ __('Share on Facebook') }}</a>
      <a class="btn-primary my-2" target="_blank" :href="`https://twitter.com/intent/tweet?&url=${url}`">{{ __('Share on Twitter') }}</a>
      <button class="btn-secondary mt-4 ml-auto" @click="show = false">{{ __('Close') }}</button>
    </div>
  </Modal>
</template>
