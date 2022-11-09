<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'

const props = defineProps({
  user: Object,
})

const emit = defineEmits(['userBanned'])

const form = useForm({
  comment: '',
  expired_at: null,
})

const reasons = ['Pseudonyme inapproprié.', 'Langage inapproprié.', 'Propos injurieux, sexistes ou racistes.', "Menace ou harcèle d'autres joueurs.", 'Donne les réponses dans le chat.', 'Utilise un nouveau compte alors que le joueur a déjà été banni.', 'Troll, spam.', 'Triche.']

const banUser = () => {
  form.post(`/moderation/users/${props.user.id}/ban`, {
    preserveScroll: false,
    preserveState: true,
    onSuccess: () => {
      emit('userBanned', true)
    },
  })
}
</script>
<template>
  <form @submit.prevent="banUser" class="m-4 rounded border p-4 text-sm">
    <SelectInput v-model="form.expired_at" class="mb-2" :label="__('Duration')" required>
      <option value="+1 day">{{ __('One day') }}</option>
      <option value="+1 week">{{ __('One week') }}</option>
      <option value="+1 month">{{ __('One month') }}</option>
      <option :value="null">{{ __('Unlimited') }}</option>
    </SelectInput>
    <SelectInput v-model="form.comment" :label="__('Reason')" required>
      <option v-for="reason in reasons" :value="reason">{{ __(reason) }}</option>
    </SelectInput>
    <button type="submit" class="btn-danger btn-sm my-4" @click="banUser">{{ __('Confirm and ban') }} {{ user.name }}</button>
  </form>
</template>
