<script setup>
import { ref } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import BanForm from '@/Components/Moderation/BanForm.vue'

const props = defineProps({
  message: Object,
  room: Object,
})

const emit = defineEmits(['close'])

const user = usePage().props.auth.user
const authorIsModerator = props.room.moderators.find((x) => x.id === props.message.user.id)
const userIsPublicModerator = usePage().props.publicModerators.find((x) => x.id === user.id)
const show = ref(true)
const showingBanForm = ref(false)

const deleteMessage = () => {
  axios.delete(route('messages.destroy', props.message)).then((response) => {
    close()
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
        <div class="flex w-full items-center justify-between">
          <h2>{{ __('Moderation') }}</h2>
          <span class="pr-2 text-sm italic">{{ message.user.name }}</span>
        </div>
      </template>

      <blockquote class="my-2 border-l px-4 py-2 font-mono text-neutral-400">
        <time class="mr-1 text-xs">{{ message.time }}</time>
        <br />
        {{ message.body }}
      </blockquote>

      <div class="my-4 flex items-center gap-4">
        <button class="btn-danger btn-sm" @click="deleteMessage">{{ __('Delete message') }}</button>
        <button v-if="!authorIsModerator && userIsPublicModerator" class="btn-danger btn-sm" @click="showingBanForm = !showingBanForm">{{ __('Ban') }} {{ message.user.name }}</button>
        <button class="btn-secondary btn-sm" @click="close">{{ __('Cancel') }}</button>
      </div>

      <BanForm v-show="showingBanForm" :user="message.user" @userBanned="close" />
    </Card>
  </Modal>
</template>
