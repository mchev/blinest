<script setup>
import { ref, watch, onMounted } from 'vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  data: Object,
})

const input = ref(null)
const track = ref(props?.data?.track)
const text = ref('')

watch(
  () => props.data,
  (value) => {
    track.value = value.track
    focus()
  },
)

onMounted(() => {
  focus()
})

const focus = () => {
  input.value.focus()
}

const check = () => {
  if (text.value.length && props.data) {
    axios.post(`/rounds/${props.data.round_id}/tracks/${track.value.id}/check`, { text: text.value }).then((response) => {
      console.log(response)
      focus()
    })
  }
  text.value = ''
}
</script>
<template>
  <form @submit.prevent="check" class="flex w-full items-center justify-center">
    <div class="flex items-center w-full">
      <input ref="input" v-model="text" type="text" class="flex-grow h-14 text-2xl rounded-bl-md border-none text-gray-600 p-2 uppercase focus:outline-none focus:shadow-none focus:ring-0" placeholder="Une idée?" autofocus />
      <button type="submit" class="btn-send h-14">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <title>{{ __('Send') }}</title>
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
        </svg>
      </button>
    </div>
  </form>
</template>
