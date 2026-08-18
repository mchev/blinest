<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick, computed } from 'vue'
import { createPopper } from '@popperjs/core'

const props = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
  /** false = click-catcher only; 'subtle' = light dim; 'modal' = stronger dim (no blur) */
  overlay: {
    type: [Boolean, String],
    default: 'subtle',
  },
})

const overlayClass = computed(() => {
  if (props.overlay === false || props.overlay === 'none') {
    return 'bg-transparent'
  }

  if (props.overlay === 'modal') {
    return 'retro-modal-overlay fixed inset-0'
  }

  return 'retro-dropdown-overlay'
})

const show = ref(false)
const dropdown = ref(null)
const popper = ref(null)
const root = ref(null)
const emit = defineEmits(['closed'])

const handleClickOutside = (event) => {
  if (!show.value) {
    return
  }

  if (root.value?.contains(event.target)) {
    return
  }

  if (dropdown.value?.contains(event.target)) {
    return
  }

  show.value = false
}

const handleEscapeKey = (event) => {
  if (event.key === 'Escape') {
    show.value = false
  }
}

watch(show, (isShown) => {
  if (isShown) {
    nextTick(() => {
      popper.value = createPopper(root.value, dropdown.value, {
        placement: props.placement,
        modifiers: [
          {
            name: 'preventOverflow',
            options: {
              altBoundary: true,
            },
          },
        ],
      })
    })

    document.addEventListener('mousedown', handleClickOutside)
  } else {
    document.removeEventListener('mousedown', handleClickOutside)

    if (popper.value) {
      setTimeout(() => {
        popper.value.destroy()
        emit('closed')
      }, 100)
    }
  }
})

onMounted(() => {
  document.addEventListener('keydown', handleEscapeKey)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscapeKey)
  document.removeEventListener('mousedown', handleClickOutside)
})
</script>
<template>
  <div ref="root">
    <button type="button" class="flex h-full w-full" @click="show = true">
      <slot />
      <teleport v-if="show" to="#dropdown">
        <div>
          <div class="z-[99998]" :class="overlayClass" @click="show = false" />
          <div ref="dropdown" class="retro-dropdown-panel absolute z-[99999]" @click.stop="show = !autoClose">
            <slot name="dropdown" />
          </div>
        </div>
      </teleport>
    </button>
  </div>
</template>
