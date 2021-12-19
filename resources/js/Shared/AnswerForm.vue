<template>
	
    <select-input v-model="form.key" class="w-2/3 mb-4" label="Type">
      <option v-for="type in types" :value="type">{{ type }}</option>
    </select-input>

    <text-input v-model="form.value" class="w-2/3 mb-4" label="Answer" />

    <text-input type="number" step="0.5" min="0" v-model="form.score" class="w-2/3 mb-4" label="Score" />

</template>

<script>

  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'

  export default {

    inheritAttrs: false,

    components: {
      TextInput,
      SelectInput,
    },

    props: {
      answer: {
        type: Object,
        default: {
        	create: true,
        	key: null,
        	value: null,
        	score: 0.5,
        }
      },
    },

    data() {
      return {
        types: ["Artist", "Title", "Album", "Movie", "Show", "Anime", "Cartoon", "Brand", "Acronym"],
        form: this.$inertia.form(this.answer),
      }
    },

    methods: {

      updateAnswer() {
	      this.form.post(route('tracks.answers.update', this.answer.track_id), {
	        onSuccess: () => this.$emit('close'),
	      })
      },

      storeAnswer() {
          axios.delete(route('tracks.delete', track.id))
            .then(function (response) {
              track.added = false;
            })
      },

      deleteAnswer() {
          axios.delete(route('tracks.delete', track.id))
            .then(function (response) {
              track.added = false;
            })
      }

    },

  }

</script>