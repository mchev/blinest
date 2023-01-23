<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

const props = defineProps({
  room: Object,
})

const emit = defineEmits(['close', 'reported'])

const show = ref(false)

const form = useForm({
  message: '',
})

const alertModerators = () => {
  form.post(`/rooms/${props.room.id}/alert`, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      emit('reported')
      emit('close')
    }
  })
}
</script>
<template>
  <Modal :show="show" @close="$emit('close')">
    <Card>
      <template #header>
        <h3 class="flex items-center pl-4 text-xl font-bold">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          {{ __('Alerting moderators') }}
        </h3>
      </template>
      <div class="p-4">
        <Tip>Attention, cette fonctionnalité n'est à utiliser qu'en cas de problème de respect des règles sur le chat ou d'une panne sur la partie.</Tip>
        <TextareaInput v-model="form.message" :error="form.errors.message" label="Commentaire (facultatif)"/>
        <div class="mt-8 flex justify-end">
          <button class="btn-secondary mr-2" @click="$emit('close')">{{ __('Close') }}</button>
          <button class="btn-danger" @click="alertModerators">{{ __('Alerting moderators') }}</button>
        </div>
      </div>
    </Card>
  </Modal>
</template>
