<script setup>
import { ref } from 'vue'
import Card from '@/Components/Card.vue'
import Modal from '@/Components/Modal.vue'
import EditOptionsForm from './EditOptionsForm.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  channel: String,
  room: Object,
  round: [Object, Boolean],
})

const isPlaying = true
const loading = ref(false)
const showingOptionsModal = ref(false)

const stopRound = () => {
  loading.value = true
  axios.post(`/rounds/${props.round.id}/stop`).then((response) => {
    loading.value = false
  })
}
const startRound = () => {
  loading.value = true
  axios.post(`/rooms/${props.room.id}/start`).then((response) => {
    loading.value = false
  })
}

// const resumeTrack = () => {
//   axios.post(`/rounds/${props.round.id}/track/resume`)
// }
// const pauseTrack = () => {
//   axios.post(`/rounds/${props.round.id}/track/pause`)
// }
// const playPreviousTrack = () => {
//   axios.post(`/rounds/${props.round.id}/track/prev`)
// }
// const playNextTrack = () => {
//   axios.post(`/rounds/${props.round.id}/track/next`)
// }
</script>
<template>
  <Card :class="$attrs.class">
    <div class="flex flex-col items-center gap-4 text-sm lg:flex-row lg:justify-between">
      <div>
        <div class="mx-auto flex flex-wrap items-center gap-4">
          <span class="uppercase text-neutral-500">Controls</span>
        </div>
      </div>
      <div class="flex items-center gap-4">
        <button v-if="!round || !round.is_playing" type="button" class="btn-secondary btn-sm" @click="showingOptionsModal = true">Options</button>
        <template v-if="!room.is_autostart">
          <LoadingButton v-if="round && (round.is_playing || room.is_playing)" :loading="loading" type="button" class="btn-danger btn-sm" @click="stopRound">{{ __('Stop round') }}</LoadingButton>
          <LoadingButton v-else type="button" :loading="loading" class="btn-primary btn-sm" @click="startRound">{{ __('Start round') }}</LoadingButton>
        </template>
      </div>
    </div>
  </Card>
  <Modal :show="showingOptionsModal" @close="showingOptionsModal = false">
    <Card>
      <template #header> Options de la room </template>
      <EditOptionsForm :room="room" :modal="true" @close="showingOptionsModal = false" />
    </Card>
  </Modal>
</template>
