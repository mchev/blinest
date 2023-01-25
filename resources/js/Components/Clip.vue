<script setup>
import { ref } from 'vue'

const props = defineProps({
  text: String,
})

const message = ref({})
const clipboardData = window.clipboardData || navigator.clipboard

const copy = () => {
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(props.text)
    message.value = {
      content: 'Copié',
      status: true,
    }
  } else {
    message.value = {
      content: 'Copie impossible',
      status: false,
    }
  }

  setTimeout(() => {
    message.value = {}
  }, 1000)
}
</script>

<template>
  <span class="relative inline-block">
    <div v-if="message.content" v-text="message.content" class="absolute right-0 bottom-full mb-1 rounded-lg p-1 text-xs text-white shadow" :class="message.status ? 'bg-green-500' : 'bg-red-500'" />
    <div class="flex">
      <div type="text" id="website-admin" class="min-w-0 rounded-none rounded-l-lg border border-gray-300 border-gray-300 bg-gray-50 px-1.5 py-1 font-mono text-xs text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" v-text="text" />

      <span @click="copy" class="inline-flex cursor-pointer items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-200 px-2 text-sm text-gray-900 opacity-80 hover:opacity-100 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
          <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
        </svg>
      </span>
    </div>
  </span>
</template>
