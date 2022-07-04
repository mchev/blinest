<script setup>
import { ref, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'

const show = ref(false)

onMounted(() => {
  var autoPlayAllowed = true
  var promise = document.createElement('audio').play()
  if (promise instanceof Promise) {
    promise
      .catch(function (error) {
        if (error.name == 'NotAllowedError') {
          autoPlayAllowed = false
        } else {
          throw error
        }
      })
      .then(function () {
        if (!autoPlayAllowed) {
          show.value = true
        }
      })
  }
})
</script>
<template>
  <Modal :show="show" @close="show = false">
    <Card>
      <template #header>
        <h3 class="flex items-center pl-4 text-xl font-bold">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
          </svg>
          Autoriser la lecture
        </h3>
      </template>
      <div class="p-4">
        {{ __('The browser needs your permission to start playing audio files.') }}
        <p class="mt-4 flex items-start text-sm text-neutral-400 bg-neutral-200 p-2 rounded">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
          <span>{{ __('This message will appear every time you go directly to a room without going through the homepage.') }}</span>
        </p>
        <div class="mt-8 flex justify-end">
          <button class="btn-primary" @click="show = false">{{ __("Let's go!") }}</button>
        </div>
      </div>
    </Card>
  </Modal>
</template>
