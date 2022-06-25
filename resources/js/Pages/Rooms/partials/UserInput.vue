<script setup>
import { ref, watch } from 'vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  data: Object,
})

const track = ref(props.data.track)
const text = ref('')

watch(
  () => props.data,
  (value) => {
    track.value = value.track
  },
)

const check = () => {
  if (text.value.length) {
    axios.post(`/rounds/${props.data.round_id}/tracks/${track.value.id}/check`, {text : text.value}).then((response) => {
      console.log(response)
      text.value = ''
    })
  }
}
</script>
<template>
  <form @submit.prevent="check" class="flex items-center">
    <TextInput v-model="text" type="text" autofocus />
    <button class="btn-primary">Send</button>
  </form>
</template>
