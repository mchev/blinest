<script setup>
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  show: Boolean,
  room: Object,
})

const emit = defineEmits(['close'])
const tracks = ref([])

const searchForm = useForm({
  search: '',
})

const form = useForm({
  suggestion: '',
})

watch(
  searchForm,
  throttle(() => {
    axios.post(route('rooms.search.tracks', props.room.id), pickBy(searchForm)).then(response => {
      tracks.value = response.data;
    })
  }, 300),
  { deep: true },
)

const submit = () => {
  form.post(route('rooms.suggestions', props.room.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      emit('close')
    },
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
        <textarea-input v-model="form.suggestion" :error="form.errors.suggestion" class="mb-4 w-full" :label="__('What would you like to suggest?')" required />
        <div class="flex mb-4">
          <TextInput v-model="searchForm.search" :label="__('Check if the song is already in the playlist')" :placeholder="__('Search')"/>
        </div>
        <ul v-if="tracks.length" class="w-full bg-neutral-700 w-full mb-4 rounded px-4 py-2 text-sm max-h-60 overflow-y-scroll">
          <li v-for="track in tracks" class="my-2 border-b border-neutral-600">
            <div class="flex gap-4">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <ul v-for="answer in track.answers" class="flex gap-2">
                  <li>{{ __(answer.type.name) }} : {{ answer.value }}</li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
        <Tip>{{ __('No need to send suggestions separately. Gather them in a single suggestion, to give better readability for moderators.') }}</Tip>
      </form>
      <template #footer>
        <div class="ml-auto flex items-center">
          <button type="button" class="btn-secondary mr-2" @click="$emit('close')">{{ __('Cancel') }}</button>
          <loading-button :loading="form.processing" class="btn-primary" form="suggestionForm" type="submit">{{ __('Send') }}</loading-button>
        </div>
      </template>
    </Card>
  </Modal>
</template>
