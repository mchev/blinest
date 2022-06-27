<template>
  <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
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

        <text-input v-model="form.score" type="number" step="0.5" min="0" :error="form.errors.score" class="w-full pb-8 pr-6 lg:w-1/2" :label="__('Score')" />

        <text-input v-model="form.value" :error="form.errors.value" class="w-full pb-8 pr-6" :label="__('Answer')" />
      </div>
    </div>

    <div class="bg-gray-100 px-6 py-4 text-right dark:bg-gray-900">
      <button v-if="answer && answer.id" class="mx-2 text-red-400" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteAnswer">
        {{ __('Delete') }}
      </button>

      <button class="btn-primary mx-2 bg-gray-400" @click="close">
        {{ __('Close') }}
      </button>

      <button v-if="answer && answer.id" class="btn-primary ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="updateAnswer">
        {{ __('Update') }}
      </button>

      <button v-else class="btn-primary ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="storeAnswer">
        {{ __('Add') }}
      </button>
    </div>
  </modal>
</template>

<script>
import { defineComponent } from 'vue'
import Modal from '@/Components/Modal'
import TextInput from '@/Components/TextInput'
import SelectInput from '@/Components/SelectInput'

export default {
  components: {
    Modal,
    TextInput,
    SelectInput,
  },

  inheritAttrs: false,

  props: {
    show: {
      default: false,
    },
    maxWidth: {
      default: '2xl',
    },
    closeable: {
      default: true,
    },
    answer_types: Object,
    answer: {
      default: {
        key: 'Artist',
        value: '',
        score: '0.5',
      },
    },
  },
  emits: ['close'],

  data() {
    return {
      form: this.$inertia.form({
        answer_type_id: 1,
        value: '',
        score: '0.5',
      }),
    }
  },

  watch: {
    answer: {
      deep: true,
      handler() {
        this.form.answer_type_id = this.answer ? this.answer.answer_type_id : 1
        this.form.value = this.answer ? this.answer.value : ''
        this.form.score = this.answer ? this.answer.score : '0.5'
      },
    },
  },

  methods: {
    close() {
      this.$emit('close')
    },

    update() {
      this.$emit('update')
    },

    updateAnswer() {
      this.form.put(route('tracks.answers.update', [this.show, this.answer.id]), {
        preserveScroll: true,
        onSuccess: () => {
          this.update()
          this.close()
        },
      })
    },

    storeAnswer() {
      this.form.post(route('tracks.answers.store', this.show), {
        preserveScroll: true,
        onSuccess: () => {
          this.update()
          this.close()
        },
      })
    },

    deleteAnswer() {
      if (confirm()) {
        this.form.delete(route('answers.delete', this.answer), {
          preserveScroll: true,
          onSuccess: () => {
            this.update()
            this.close()
          },
        })
      }
    },
  },
}
</script>
