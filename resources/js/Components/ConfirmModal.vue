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
    default: 'danger',
    validator: (value) => ['danger', 'warning', 'info'].includes(value),
  },
})

const emit = defineEmits(['close', 'confirm'])

const confirmClass = computed(() => {
  const variants = {
    danger: 'game-btn-play-live !min-h-[40px] !w-auto !px-5 !text-xs',
    warning: 'game-btn-play-secondary !min-h-[40px] !w-auto !px-5 !text-xs',
    info: 'game-btn-play-join !min-h-[40px] !w-auto !px-5 !text-xs',
  }

  return variants[props.variant] || variants.danger
})

const iconWrapClass = computed(() => {
  const variants = {
    danger: 'text-brand-primary',
    warning: 'text-brand-secondary',
    info: 'text-brand-accent',
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
  <Modal :show="show" max-width="md" @close="handleClose">
    <div class="p-6">
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
          <div class="retro-icon-btn !h-10 !w-10" :class="iconWrapClass">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
        </div>

        <div class="min-w-0 flex-1">
          <h3 class="retro-title retro-title--primary mb-2 text-lg">
            {{ title }}
          </h3>
          <p class="text-sm leading-relaxed text-white/70">
            {{ message }}
          </p>
        </div>
      </div>

      <div class="mt-6 flex items-center justify-end gap-3">
        <button type="button" class="game-btn-secondary !min-h-[40px]" @click="handleClose">
          {{ cancelText }}
        </button>
        <button type="button" :class="confirmClass" @click="handleConfirm">
          {{ confirmText }}
        </button>
      </div>
    </div>
  </Modal>
</template>
