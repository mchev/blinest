<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  message: Object,
})

const emit = defineEmits('close')

const user = usePage().props.value.auth.user
const show = ref(true)

const close = () => {
  emit('close')
}

</script>
<template>
  <Modal :show="show" @close="close">
    <Card>
      <template #header>
        <h2>{{ __('Moderation') }}</h2>
      </template>

    <div class="flex items-center">
      <h3 class="uppercase flex-1">{{ __('Message') }}</h3>
      <time class="mr-1">{{ message.time }}</time>
    </div>
    <blockquote class="italic px-4 py-2 border-l my-2 text-neutral-400">{{ message.body }}</blockquote>
    <button class="btn-danger btn-sm">{{ __('Delete') }}</button>
    <h3 class="uppercase mt-4">{{ __('User') }}</h3>
    <ul class="my-2 pl-4">
      <li>{{ __('Name') }} : {{ message.user.name }}</li>
      <li>{{ __('Team') }} : {{ message.user.team?.name }}</li>
    </ul>
  </Card>
  </Modal>
</template>
