<script setup>
import { ref, watch, onMounted } from 'vue'
import Dropdown from '@/Components/Dropdown.vue'

const volume = ref(0.7)

onMounted(() => {
  volume.value = localStorage.getItem('volume') ?? 0.7
  dispatch()
})

watch(volume, (value) => {
  localStorage.setItem('volume', value)
  dispatch()
})

const dispatch = () => {
  window.dispatchEvent(
    new CustomEvent('volume-localstorage-changed', {
      detail: {
        volume: volume.value,
      },
    }),
  )
}
</script>
<template>
  <dropdown placement="bottom-end" :autoClose="false">
    <template #default>
      <div class="group flex cursor-pointer select-none items-center h-full mr-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
        </svg>
      </div>
    </template>
    <template #dropdown>
      <div class="p-2">
        <input id="default-range" type="range" min="0" max="1" step="0.1" v-model="volume" class="h-2 w-full cursor-pointer appearance-none rounded-lg" />
      </div>
    </template>
  </dropdown>
</template>
