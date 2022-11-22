<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, usePage, useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import CheckboxInput from '@/Components/CheckboxInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  room: Object,
  modal: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['close'])

const user = usePage().props.value.auth.user

const form = useForm({
  is_chat_active: props.room.is_chat_active,
  is_autostart: props.room.is_autostart,
  color: props.room.color,
  has_password: props.room.password ? true : false,
  password: props.room.password,
  tracks_by_round: props.room.tracks_by_round,
  track_duration: props.room.track_duration,
  pause_between_tracks: props.room.pause_between_tracks,
  pause_between_rounds: props.room.pause_between_rounds,
})

const update = () => {
  form.put(route('rooms.options', props.room.id), {
    preserveScroll: true,
    onSuccess: () => {
      emit('close')
    }
  })
}
</script>
<template>
  <form @submit.prevent="update" id="optionsForm" class="flex flex-wrap">
    <div class="flex w-full flex-wrap">
      <label for="tracks_by_round-range" class="mb-2 block text-sm font-medium"
        >{{ __('Tracks by round') }} : <span class="font-bold">{{ form.tracks_by_round }}</span></label
      >
      <input id="tracks_by_round-range" type="range" min="1" max="100" v-model="form.tracks_by_round" :error="form.errors.tracks_by_round" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

      <label for="track_duration-range" class="mb-2 block text-sm font-medium"
        >{{ __('Track duration') }} : <span class="font-bold">{{ form.track_duration }} {{ __('seconds') }}</span></label
      >
      <input id="track_duration-range" type="range" min="5" max="30" v-model="form.track_duration" :error="form.errors.track_duration" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

      <label for="pause_between_tracks-range" class="mb-2 block text-sm font-medium"
        >{{ __('Pause between tracks') }} : <span class="font-bold">{{ form.pause_between_tracks }} {{ __('seconds') }}</span></label
      >
      <input id="pause_between_tracks-range" type="range" min="0" max="30" v-model="form.pause_between_tracks" :error="form.errors.pause_between_tracks" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

      <label for="pause_between_rounds-range" class="mb-2 block text-sm font-medium"
        >{{ __('Pause between rounds') }} : <span class="font-bold">{{ form.pause_between_rounds }} {{ __('seconds') }}</span></label
      >
      <input id="pause_between_rounds-range" type="range" min="0" max="60" v-model="form.pause_between_rounds" :error="form.errors.pause_between_rounds" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />
    </div>

    <div class="flex w-full flex-wrap">
      <checkbox-input v-model="form.is_chat_active" :error="form.errors.is_chat_active" class="pr-4 pb-4" :label="__('Chatbox')" />
      <!-- <checkbox-input v-model="form.is_autostart" :error="form.errors.is_autostart" class="pr-4 pb-4" :label="__('Autostart')" /> -->
      <checkbox-input v-model="form.has_password" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Password')" />
    </div>

    <div class="flex w-full flex-wrap">
      <text-input v-show="form.has_password" v-model="form.password" :error="form.errors.password" class="pb-6" type="password" autocomplete="new-password" :label="__('Password')" />
    </div>

    <div class="flex items-center gap-2 ml-auto">
      <button @click="$emit('close')" class="btn-secondary" type="button">{{ __('Close') }}</button>
      <loading-button :loading="form.processing" class="btn-primary" form="optionsForm" type="submit">{{ __('Update') }}</loading-button>
    </div>
  </form>
</template>
