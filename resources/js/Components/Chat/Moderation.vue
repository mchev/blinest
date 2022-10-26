<script setup>
import { ref } from 'vue'
import { usePage, useForm } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'

const props = defineProps({
  message: Object,
  room: Object,
})

const emit = defineEmits(['close'])

const form = useForm({
  comment: '',
  expired_at: null
})
const user = usePage().props.value.auth.user
const authorIsModerator = props.room.moderators.find((x) => x.id === props.message.user.id)
const userIsPublicModerator = usePage().props.value.publicModerators.find((x) => x.id === user.id)
const show = ref(true)
const showingBanForm = ref(false)
const reasons = [
  'Pseudonyme inapproprié.',
  'Langage inapproprié.',
  'Propos injurieux, sexistes ou racistes.',
  'Menace ou harcèle d\'autres joueurs.',
  'Donne les réponses dans le chat.',
  'Utilise un nouveau compte alors que le joueur a déjà été banni.',
  'Troll, spam.',
  'Triche.',
]

const deleteMessage = () => {
  axios.delete(`/moderation/messages/${props.message.id}`).then((response) => {
    close()
  })
}

// TODO
const banUser = () => {
  form.post(`/moderation/users/${props.message.user.id}/ban`, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      close()
    }
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
        <div class="flex items-center justify-between w-full">
          <h2>{{ __('Moderation') }}</h2>
          <span class="italic text-sm pr-2">{{ message.user.name }}</span>
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

      <div v-show="showingBanForm" class="p-4 text-sm m-4 border rounded">
        <SelectInput v-model="form.expired_at" class="mb-2" :label="__('Duration')">
          <option value="+1 day">{{ __('One day') }}</option>
          <option value="+1 week">{{ __('One week') }}</option>
          <option value="+1 month">{{ __('One month') }}</option>
          <option :value="null">{{ __('Unlimited') }}</option>
        </SelectInput>
        <SelectInput v-model="form.comment" :label="__('Reason')">
          <option v-for="reason in reasons" :value="reason">{{ __(reason) }}</option>
        </SelectInput>
        <button v-if="!authorIsModerator" class="btn-danger btn-sm my-4" @click="banUser">{{ __('Confirm and ban') }} {{ message.user.name }}</button>
      </div>

    </Card>
  </Modal>
</template>
