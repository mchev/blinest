<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  notification: Object,
})

const emit = defineEmits(['handled'])

const payload = computed(() => {
  const { data } = props.notification ?? {}

  if (data?.teamRequest) {
    return data
  }

  if (data?.data?.teamRequest) {
    return data.data
  }

  return props.notification ?? {}
})

const teamRequestId = computed(() => payload.value.teamRequest?.id ?? null)

const form = useForm({
  notification_id: props.notification.id,
})

const submit = (action) => {
  if (!teamRequestId.value || form.processing) {
    return
  }

  form.post(`/teams/requests/${teamRequestId.value}/${action}`, {
    preserveScroll: true,
    onSuccess: () => emit('handled', props.notification),
  })
}
</script>
<template>
  <div v-if="notification" class="flex-grow">
    <h4 class="mb-1 border-b pb-1 uppercase text-neutral-500">{{ __('Team') }}</h4>
    <div class="my-2 text-sm font-medium">
      {{ payload.message }}
    </div>
    <div class="mt-1 flex items-center justify-end">
      <button type="button" class="btn-danger btn-sm mr-2 opacity-80" :disabled="!teamRequestId || form.processing" @click.stop="submit('decline')">
        {{ form.processing ? __('Decline') + '…' : __('Decline') }}
      </button>
      <button type="button" class="btn-primary btn-sm opacity-80" :disabled="!teamRequestId || form.processing" @click.stop="submit('accept')">
        {{ form.processing ? __('Accept') + '…' : __('Accept') }}
      </button>
    </div>
  </div>
</template>
