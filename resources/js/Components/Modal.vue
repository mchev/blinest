<script setup>
import { computed, onMounted, onUnmounted, watch, nextTick, ref, useId } from 'vue'
import { initChamferBorders } from '@/chamfer-borders'
import {
  assignDialogLabel,
  focusInitialElement,
  handleFocusTrapKeydown,
} from '@/composables/useFocusTrap'

const props = defineProps({
  show: {
    type: [Boolean, Number],
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
  ariaLabel: {
    type: String,
    default: null,
  },
})

const emit = defineEmits(['close'])

const dialogRef = ref(null)
const labelledBy = ref(null)
const fallbackLabelId = useId()
let previouslyFocused = null

watch(
  () => props.show,
  async (visible) => {
    if (visible) {
      previouslyFocused = document.activeElement
      document.body.style.overflow = 'hidden'
      await nextTick()
      requestAnimationFrame(() => {
        initChamferBorders()

        if (dialogRef.value) {
          labelledBy.value = assignDialogLabel(dialogRef.value, fallbackLabelId)
          focusInitialElement(dialogRef.value)
        }
      })
    } else {
      document.body.style.overflow = null
      labelledBy.value = null

      if (previouslyFocused?.focus) {
        previouslyFocused.focus()
      }

      previouslyFocused = null
    }
  },
)

const close = () => {
  if (props.closeable) {
    emit('close')
  }
}

const handleKeydown = (event) => {
  if (!props.show) {
    return
  }

  if (event.key === 'Escape') {
    close()

    return
  }

  handleFocusTrapKeydown(event, dialogRef.value)
}

onMounted(() => document.addEventListener('keydown', handleKeydown))
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
  document.body.style.overflow = null
})

const maxWidthClass = computed(() => {
  const widths = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '3xl': 'sm:max-w-3xl',
    '4xl': 'sm:max-w-4xl',
    '5xl': 'sm:max-w-5xl',
  }

  return widths[props.maxWidth] ?? widths['2xl']
})
</script>

<template>
  <teleport to="body">
    <transition leave-active-class="duration-200">
      <div v-show="show" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 pb-24 sm:px-0 sm:pb-6" scroll-region>
        <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
          <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
            <div class="retro-modal-overlay" />
          </div>
        </transition>

        <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100 translate-y-0 sm:scale-100" leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
          <div
            v-show="show"
            ref="dialogRef"
            role="dialog"
            aria-modal="true"
            tabindex="-1"
            class="retro-modal-panel mx-auto mb-6 w-full max-w-[calc(100vw-2rem)] transform transition-all focus:outline-none"
            :class="maxWidthClass"
            :aria-labelledby="labelledBy ?? undefined"
            :aria-label="!labelledBy && ariaLabel ? ariaLabel : undefined"
          >
            <slot v-if="show" />
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>
