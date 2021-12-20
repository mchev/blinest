<template>
	
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">

        <div class="px-6 py-4">
            <div class="text-lg">
              {{ __('Edit Answer') }}
            </div>

            <div class="flex flex-wrap mt-4">

              <select-input v-model="form.key" class="pb-8 pr-6 w-full lg:w-1/2" :label="__('Type')">
                <option v-for="type in types" :value="type">{{ __(type) }}</option>
              </select-input>

              <text-input type="number" step="0.5" min="0" v-model="form.score" class="pb-8 pr-6 w-full lg:w-1/2" :label="__('Score')" />

              <text-input v-model="form.value" class="pb-8 pr-6 w-full" :label="__('Answer')" />

            </div>

        </div>

        <div class="px-6 py-4 bg-gray-100 text-right">
            <button class="mr-2" @click="close">
              {{ __('Cancel') }}
            </button>

            <button class="btn-indigo ml-2" @click="updateAnswer" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ __('Update') }}
            </button>
        </div>

    </modal>

</template>

<script>

  import { defineComponent } from 'vue'
  import Modal from '@/Shared/Modal'
  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'

  export default {

    emits: ['close'],

    inheritAttrs: false,

    components: {
      Modal,
      TextInput,
      SelectInput,
    },

    props: {
      show: {
          default: false
      },
      maxWidth: {
          default: '2xl'
      },
      closeable: {
          default: true
      },
      answer: {
          default: false,
      }
    },

    data() {
      return {
        types: ["Artist", "Title", "Album", "Movie", "Show", "Anime", "Cartoon", "Brand", "Acronym"],
      }
    },

    computed: {
      form() {
        return this.$inertia.form({
          key: (this.answer) ? this.anwser.key : 'Artist',
          value: (this.answer) ? this.anwser.value : '',
          score: (this.answer) ? this.anwser.score : '0.5',
        })
      }
    },

    methods: {

      close() {
          this.$emit('close')
      },

      updateAnswer() {
	      this.form.put(route('tracks.answers.update', [this.show, this.answer.id]), {
	        onSuccess: () => this.close()
	      })
      },

      storeAnswer() {
        this.form.post(route('tracks.answers.store', this.show), {
          onSuccess: () => this.close()
        })
      },

      deleteAnswer() {
        this.form.post(route('answers.delete', this.answer), {
          onSuccess: () => this.close()
        })
      },

    },

  }

</script>