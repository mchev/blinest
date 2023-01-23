<script setup>
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'

const props = defineProps({
  answer: {
    type: Object,
    default() {
      return {
        answer_type_id: 1,
        value: '',
        score: '0.5',
      }
    },
  },
  show: [Boolean, Number],
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
  answer_types: Object,
})

const emit = defineEmits(['close'])

const form = useForm({
  answer_type_id: props.answer ? props.answer.answer_type_id : 1,
  value: props.answer ? props.answer.value : '',
  score: props.answer ? props.answer.score : 0.5,
})

const close = () => {
  emit('close')
}

const submitForm = () => {
  props.answer ? updateAnswer() : storeAnswer()
}

const updateAnswer = () => {
  form.put(route('tracks.answers.update', [props.show, props.answer.id]), {
    preserveScroll: true,
    onSuccess: () => {
      close()
    },
  })
}

const storeAnswer = () => {
  form.post(route('tracks.answers.store', props.show), {
    preserveScroll: true,
    onSuccess: () => {
      close()
    },
  })
}

const deleteAnswer = () => {
  form.delete(route('tracks.answers.delete', [props.show, props.answer.id]), {
    preserveScroll: true,
    preserveState: false,
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
        <div v-if="answer" class="text-lg">
          {{ __('Edit Answer') }}
        </div>
        <div v-else="answer" class="text-lg">
          {{ __('Add Answer') }}
        </div>

        <div class="mt-4 flex flex-wrap">
          <select-input v-model="form.answer_type_id" :error="form.errors.answer_type_id" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Type')">
            <option v-for="type in answer_types" :value="type.id">{{ __(type.name) }}</option>
          </select-input>

          <text-input v-model="form.score" type="number" step="0.5" min="0" max="99" :error="form.errors.score" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Score')" />

          <text-input v-model="form.value" :error="form.errors.value" class="w-full pb-8 pr-6" :label="__('Answer')" />
        </div>
      </div>

      <div class="flex items-center justify-between px-2 py-4 text-right">
        <button v-if="answer && answer.id" type="button" class="mx-2 text-red-400" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteAnswer">
          {{ __('Delete') }}
        </button>

        <div class="flex items-center">
          <button class="btn-secondary mx-2 bg-gray-400" @click="close">
            {{ __('Close') }}
          </button>

          <button v-if="answer && answer.id" type="submit" class="btn-primary ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ __('Update') }}
          </button>

          <button v-else type="submit" class="btn-primary ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ __('Add') }}
          </button>
        </div>
      </div>
    </form>
  </modal>
</template>
