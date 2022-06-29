<script setup>
import { ref, watch, onMounted, nextTick } from 'vue'
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
    <button type="button" class="flex h-full" @click="show = true">
      <slot />
      <teleport v-if="show" to="#dropdown">
        <div>
          <div class="fixed top-0 right-0 left-0 bottom-0 z-[99998] bg-black opacity-10" @click="show = false" />
          <div ref="dropdown" class="absolute z-[99999] overflow-hidden rounded-lg bg-neutral-800 text-sm font-semibold text-neutral-100 shadow-lg" @click.stop="show = !autoClose">
            <slot name="dropdown" />
          </div>
        </div>
      </teleport>
    </button>
  </div>
</template>
