<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
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
const startingRound = ref(false)
const endingRound = ref(false)
const showingOptionsModal = ref(false)

const stopRound = () => {
  endingRound.value = true
  Inertia.post(`/rounds/${props.round.id}/stop`, {
    preserveScroll: true,
    preserveState: false,
  })
}
const startRound = () => {
  startingRound.value = true
  Inertia.post(`/rooms/${props.room.id}/start`, {
    preserveScroll: true,
    preserveState: false,
  })
}

onMounted(() => {
  Echo.channel(props.channel)
    .listen('RoundStarted', () => {
      startingRound.value = false
      props.room.is_playing = true
    })
    .listen('RoundFinished', (e) => {
      endingRound.value = false
      props.room.is_playing = false
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})
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
        <button type="button" class="btn-secondary btn-sm" @click="showingOptionsModal = true">Options</button>
        <template v-if="!room.is_autostart">
          <LoadingButton v-if="room.is_playing" :loading="endingRound" type="button" class="btn-danger btn-sm" @click="stopRound">{{ __('Stop round') }}</LoadingButton>
          <LoadingButton v-else type="button" :loading="startingRound" class="btn-primary btn-sm" @click="startRound">{{ __('Start round') }}</LoadingButton>
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
