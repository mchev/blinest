<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

const props = defineProps({
  show: Boolean,
  room: Object,
})

const emit = defineEmits(['close'])

const form = useForm({
  suggestion: '',
})

const submit = () => {
  form.post(route('rooms.suggestions', props.room.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      emit('close')
    }
  })
}

const close = () => {
  emit('close')
}

</script>
<template>
  <Modal :show="show" @close="close">
    <Card>
      <template #header>
        <h2>{{ __('Send a suggestion') }}</h2>
      </template>
      <form @submit.prevent="submit" id="suggestionForm">
        <textarea-input v-model="form.suggestion" :error="form.errors.suggestion" class="mb-4 w-full" :label="__('What would you like to suggest?')" required/>
      </form>
      <template #footer>
        <div class="flex items-center ml-auto">
          <button type="button" class="btn-secondary mr-2" @click="$emit('close')">{{ __('Cancel') }}</button>
          <loading-button :loading="form.processing" class="btn-primary" form="suggestionForm" type="submit">{{ __('Send') }}</loading-button>
        </div>
      </template>
    </Card>
  </Modal>
</template>
