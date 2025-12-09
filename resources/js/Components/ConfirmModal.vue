<script setup>
import Modal from './Modal.vue'
import { computed } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    required: true,
  },
  message: {
    type: String,
    required: true,
  },
  confirmText: {
    type: String,
    default: 'Confirm',
  },
  cancelText: {
    type: String,
    default: 'Cancel',
  },
  variant: {
    type: String,
    default: 'danger', // 'danger', 'warning', 'info'
    validator: (value) => ['danger', 'warning', 'info'].includes(value),
  },
})

const emit = defineEmits(['close', 'confirm'])

const variantClasses = computed(() => {
  const variants = {
    danger: 'bg-red-500 hover:bg-red-600',
    warning: 'bg-yellow-500 hover:bg-yellow-600',
    info: 'bg-blinest-500 hover:bg-blinest-600',
  }
  return variants[props.variant] || variants.danger
})

const handleConfirm = () => {
  emit('confirm')
  emit('close')
}

const handleClose = () => {
  emit('close')
}
</script>

<template>
  <Modal :show="show" @close="handleClose" max-width="md">
    <div class="p-6">
      <div class="flex items-start gap-4">
        <div v-if="variant === 'danger'" class="flex-shrink-0">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-red-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
        </div>
        <div v-else-if="variant === 'warning'" class="flex-shrink-0">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-yellow-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
        </div>
        <div v-else class="flex-shrink-0">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blinest-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-blinest-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
          </div>
        </div>
        
        <div class="flex-1 min-w-0">
          <h3 class="text-lg font-semibold text-neutral-200 mb-2">
            {{ title }}
          </h3>
          <p class="text-sm text-neutral-400 leading-relaxed">
            {{ message }}
          </p>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-end gap-3">
        <button
          type="button"
          class="px-4 py-2 text-sm font-medium text-neutral-300 bg-neutral-700/50 hover:bg-neutral-700 rounded-lg transition-colors"
          @click="handleClose"
        >
          {{ cancelText }}
        </button>
        <button
          type="button"
          :class="['px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors', variantClasses]"
          @click="handleConfirm"
        >
          {{ confirmText }}
        </button>
      </div>
    </div>
  </Modal>
</template>

