<script setup>
import { ref, watch, onMounted, nextTick, computed } from 'vue'
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

watch(show, (show) => {
  if (show) {
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
  } else if (popper.value) {
    setTimeout(() => {
      popper.value.destroy()
      emit('closed')
    }, 100)
  }
})

onMounted(() => {
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      show.value = false
    }
  })
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
