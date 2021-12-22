<template>
	
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">

        <div class="px-6 py-4">
            <div class="text-lg">
              {{ __('Edit Answer') }}
            </div>

            <div class="flex flex-wrap mt-4">

              <select-input v-model="form.key" :error="form.errors.key" class="pb-8 pr-6 w-full lg:w-1/2" :label="__('Type')">
                <option v-for="type in types" :value="type">{{ __(type) }}</option>
              </select-input>

              <text-input type="number" step="0.5" min="0" v-model="form.score" :error="form.errors.score" class="pb-8 pr-6 w-full lg:w-1/2" :label="__('Score')" />

              <text-input v-model="form.value" :error="form.errors.value" class="pb-8 pr-6 w-full" :label="__('Answer')" />

            </div>

        </div>

        <div class="px-6 py-4 bg-gray-100 dark:bg-gray-900 text-right">

            <button v-if="answer && answer.id" class="text-red-400 mx-2" @click="deleteAnswer" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ __('Delete') }}
            </button>

            <button class="btn-indigo bg-gray-400 mx-2" @click="close">
              {{ __('Close') }}
            </button>

            <button v-if="answer && answer.id" class="btn-indigo ml-2" @click="updateAnswer" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ __('Update') }}
            </button>
            
            <button v-else class="btn-indigo ml-2" @click="storeAnswer" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ __('Add') }}
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
          default: {
            key: "Artist",
            value: "",
            score: "0.5",
          },
      }
    },

    data() {
      return {
        types: ["Artist", "Title", "Album", "Feat.", "Movie", "Show", "Anime", "Cartoon", "Brand", "Acronym"],
        form: this.$inertia.form({
            key: 'Artist',
            value: '',
            score: '0.5',
        })
      }
    },

    watch: {
      answer: {
        deep: true,
        handler() {
          this.form.key = (this.answer) ? this.answer.key : "Artist";
          this.form.value = (this.answer) ? this.answer.value : "";
          this.form.score = (this.answer) ? this.answer.score : "0.5";
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
            this.update();
            this.close();
          }
	      })
      },

      storeAnswer() {
        this.form.post(route('tracks.answers.store', this.show), {
          preserveScroll: true, 
          onSuccess: () => {
            this.update();
            this.close();
          }
        })
      },

      deleteAnswer() {
        if(confirm()) {
          this.form.delete(route('answers.delete', this.answer), {
            preserveScroll: true, 
            onSuccess: () => {
              this.update();
              this.close();
            }
          })
        }
      },

    },

  }

</script>