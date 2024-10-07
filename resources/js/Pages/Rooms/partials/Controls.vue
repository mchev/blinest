<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
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
  if(!props.round) {
    alert("Erreur : la room est en lecture mais aucune partie n'est en cours.")
    return
  }
  endingRound.value = true
  router.post(`/rounds/${props.round.id}/stop`, {
    preserveScroll: true,
    preserveState: false,
  })
}
const startRound = () => {
  startingRound.value = true
  router.post(`/rooms/${props.room.id}/start`, {
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

  <div class="flex items-center gap-2">
    <button type="button" class="btn-secondary btn-sm gap-1" @click="showingOptionsModal = true">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
      </svg>
      <span class="hidden md:block">{{ __('Options') }}</span>
    </button>
    <template v-if="!room.is_autostart">
      <LoadingButton v-if="room.is_playing" :loading="endingRound" type="button" class="btn-danger btn-sm" @click="stopRound">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
          <path fill-rule="evenodd" d="M4.5 7.5a3 3 0 013-3h9a3 3 0 013 3v9a3 3 0 01-3 3h-9a3 3 0 01-3-3v-9z" clip-rule="evenodd" />
        </svg>
        <span class="hidden md:block">{{ __('Stop round') }}</span>
      </LoadingButton>
      <LoadingButton v-else type="button" :loading="startingRound" class="btn-primary btn-sm" @click="startRound">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
          <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
        </svg>
        <span class="hidden md:block">{{ __('Start round') }}</span>
      </LoadingButton>
    </template>
  </div>

  <Modal :show="showingOptionsModal" @close="showingOptionsModal = false">
    <Card>
      <template #header>  {{ __('Room options') }}</template>
      <EditOptionsForm :room="room" :modal="true" @close="showingOptionsModal = false" />
    </Card>
  </Modal>
</template>
