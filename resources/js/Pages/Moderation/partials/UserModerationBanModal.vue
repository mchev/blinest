<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  user: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['close'])

const translate = useTranslate()

const reason = ref('')
const duration = ref(1440)
const formErrors = ref({})

const presetReasons = ['Pseudonyme inapproprié.', 'Langage inapproprié.', 'Propos injurieux, sexistes ou racistes.', "Menace ou harcèlement d'autres joueurs.", 'Donne les réponses dans le chat.', 'Utilise un nouveau compte alors que le joueur a déjà été banni.', 'Troll, spam.', 'Triche.']

watch(
  () => props.show,
  (visible) => {
    if (visible) {
      reason.value = ''
      duration.value = 1440
      formErrors.value = {}
    }
  },
)

const close = () => {
  emit('close')
}

const submit = () => {
  if (!reason.value.trim()) {
    formErrors.value = { reason: translate('Moderation ban reason required') }

    return
  }

  router.post(
    route('moderation.users.ban', props.user.id),
    {
      reason: reason.value,
      duration: duration.value > 0 ? duration.value : null,
    },
    {
      preserveScroll: true,
      onSuccess: () => close(),
      onError: (errors) => {
        formErrors.value = errors
      },
    },
  )
}
</script>

<template>
  <Transition enter-active-class="ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 sm:items-center">
        <div class="fixed inset-0 bg-black/70" @click="close" />

        <div class="relative w-full max-w-lg rounded-2xl border border-white/10 bg-brand-deep p-6 shadow-2xl">
          <h3 class="text-lg font-semibold text-white">{{ __('Moderation ban user title', { name: user.name }) }}</h3>
          <p class="mt-1 text-sm text-white/50">#{{ user.id }}</p>

          <div v-if="formErrors.error" class="mt-4 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-300">
            {{ formErrors.error }}
          </div>

          <div class="mt-5 space-y-4">
            <div>
              <label for="ban-reason" class="mb-2 block text-sm font-medium text-white/70">{{ __('Reason') }}</label>
              <select id="ban-reason" v-model="reason" class="mb-2 w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-sm text-white">
                <option value="">{{ __('Moderation choose reason') }}</option>
                <option v-for="preset in presetReasons" :key="preset" :value="preset">{{ preset }}</option>
              </select>
              <textarea v-model="reason" rows="3" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-sm text-white placeholder-white/35" :class="{ 'border-red-500/50': formErrors.reason }" :placeholder="__('Moderation ban reason placeholder')" />
              <p v-if="formErrors.reason" class="mt-1 text-sm text-red-400">{{ formErrors.reason }}</p>
            </div>

            <div>
              <label for="ban-duration" class="mb-2 block text-sm font-medium text-white/70">{{ __('Duration') }}</label>
              <select id="ban-duration" v-model="duration" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-sm text-white">
                <option :value="60">{{ __('Moderation ban 1 hour') }}</option>
                <option :value="1440">{{ __('One day') }}</option>
                <option :value="10080">{{ __('One week') }}</option>
                <option :value="43200">{{ __('One month') }}</option>
                <option :value="0">{{ __('Unlimited') }}</option>
              </select>
              <p class="mt-1 text-xs text-white/45">{{ __('Moderation ban duration hint') }}</p>
            </div>
          </div>

          <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" class="rounded-xl border border-white/10 px-4 py-2 text-sm text-white/70 hover:bg-white/5" @click="close">
              {{ __('Cancel') }}
            </button>
            <button type="button" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm font-medium text-red-200 hover:bg-red-500/20" @click="submit">
              {{ __('Confirm and ban') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>
