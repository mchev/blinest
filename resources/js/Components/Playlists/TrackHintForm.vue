<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  track: {
    type: Object,
    required: true,
  },
  show: [Boolean, Number],
  maxWidth: {
    type: String,
    default: 'md',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['close'])

const form = useForm({
  hint: props.track?.hint || '',
})

// Update form when track changes
watch(
  () => props.track,
  (newTrack) => {
    if (newTrack) {
      form.hint = newTrack.hint || ''
    }
  },
  { immediate: true },
)

const close = () => {
  emit('close')
}

const submitForm = () => {
  form.put(route('playlists.tracks.update', [props.track.playlist_id, props.track.id]), {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks'],
    onSuccess: () => {
      close()
    },
  })
}

const deleteHint = () => {
  form.hint = ''
  form.put(route('playlists.tracks.update', [props.track.playlist_id, props.track.id]), {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks'],
    onSuccess: () => {
      close()
    },
  })
}
</script>
<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
    <form @submit.prevent="submitForm">
      <div class="px-6 py-4">
        <div class="text-lg">
          {{ track?.hint ? __('Edit hint') : __('Add hint') }}
        </div>

        <div class="mt-4">
          <text-input v-model="form.hint" type="text" :error="form.errors.hint" class="w-full" :label="__('Hint')" :placeholder="__('Hint')" />
        </div>
      </div>

      <div class="flex items-center justify-between px-2 py-4 text-right">
        <button v-if="track?.hint" type="button" class="mx-2 text-red-400" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteHint">
          {{ __('Delete') }}
        </button>

        <div class="ml-auto flex items-center">
          <button class="btn-secondary mx-2 bg-gray-400" @click="close">
            {{ __('Close') }}
          </button>

          <button type="submit" class="btn-primary ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ __('Save') }}
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>
