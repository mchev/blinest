<script setup>
import { usePage, useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import CheckboxInput from '@/Components/CheckboxInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
  },
  modal: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close'])

const user = usePage().props.auth.user

const form = useForm({
  is_featured: props.room.is_featured,
  is_chat_active: props.room.is_chat_active,
  is_autostart: props.room.is_autostart,
  is_random: props.room.is_random,
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
    onSuccess: () => emit('close'),
  })
}
</script>
<template>
  <form @submit.prevent="update" id="optionsForm" class="space-y-6">
    <!-- Track Settings Section -->
    <div class="rounded-lg bg-neutral-800/50 p-5 shadow-md border border-neutral-700">
      <h3 class="mb-4 text-lg font-semibold text-purple-400">{{ __('Track Settings') }}</h3>
      
      <div class="space-y-5">
        <!-- Tracks by round -->
        <div>
          <div class="flex justify-between mb-2">
            <label for="tracks_by_round-range" class="text-sm font-medium text-neutral-300">
              {{ __('Tracks by round') }}
            </label>
            <span class="px-2 py-0.5 rounded bg-purple-900/50 text-purple-300 text-xs font-bold">
              {{ form.tracks_by_round }}
            </span>
          </div>
          <div class="relative">
            <input 
              id="tracks_by_round-range" 
              type="range" 
              min="1" 
              max="50" 
              v-model="form.tracks_by_round" 
              :error="form.errors.tracks_by_round" 
              step="1" 
              class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-neutral-700 accent-purple-500" 
            />
            <div class="absolute -bottom-4 left-0 text-xs text-neutral-500">1</div>
            <div class="absolute -bottom-4 right-0 text-xs text-neutral-500">50</div>
          </div>
        </div>
        
        <!-- Track duration -->
        <div class="mt-8">
          <div class="flex justify-between mb-2">
            <label for="track_duration-range" class="text-sm font-medium text-neutral-300">
              {{ __('Track duration') }}
            </label>
            <span class="px-2 py-0.5 rounded bg-purple-900/50 text-purple-300 text-xs font-bold">
              {{ form.track_duration }} {{ __('seconds') }}
            </span>
          </div>
          <div class="relative">
            <input 
              id="track_duration-range" 
              type="range" 
              min="5" 
              max="30" 
              v-model="form.track_duration" 
              :error="form.errors.track_duration" 
              step="1" 
              class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-neutral-700 accent-purple-500" 
            />
            <div class="absolute -bottom-4 left-0 text-xs text-neutral-500">5s</div>
            <div class="absolute -bottom-4 right-0 text-xs text-neutral-500">30s</div>
          </div>
        </div>
        
        <!-- Pause between tracks -->
        <div class="mt-8">
          <div class="flex justify-between mb-2">
            <label for="pause_between_tracks-range" class="text-sm font-medium text-neutral-300">
              {{ __('Pause between tracks') }}
            </label>
            <span class="px-2 py-0.5 rounded bg-purple-900/50 text-purple-300 text-xs font-bold">
              {{ form.pause_between_tracks }} {{ __('seconds') }}
            </span>
          </div>
          <div class="relative">
            <input 
              id="pause_between_tracks-range" 
              type="range" 
              min="0" 
              max="30" 
              v-model="form.pause_between_tracks" 
              :error="form.errors.pause_between_tracks" 
              step="1" 
              class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-neutral-700 accent-purple-500" 
            />
            <div class="absolute -bottom-4 left-0 text-xs text-neutral-500">0s</div>
            <div class="absolute -bottom-4 right-0 text-xs text-neutral-500">30s</div>
          </div>
        </div>
        
        <!-- Pause between rounds -->
        <div class="mt-8">
          <div class="flex justify-between mb-2">
            <label for="pause_between_rounds-range" class="text-sm font-medium text-neutral-300">
              {{ __('Pause between rounds') }}
            </label>
            <span class="px-2 py-0.5 rounded bg-purple-900/50 text-purple-300 text-xs font-bold">
              {{ form.pause_between_rounds }} {{ __('seconds') }}
            </span>
          </div>
          <div class="relative">
            <input 
              id="pause_between_rounds-range" 
              type="range" 
              min="0" 
              max="60" 
              v-model="form.pause_between_rounds" 
              :error="form.errors.pause_between_rounds" 
              step="1" 
              class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-neutral-700 accent-purple-500" 
            />
            <div class="absolute -bottom-4 left-0 text-xs text-neutral-500">0s</div>
            <div class="absolute -bottom-4 right-0 text-xs text-neutral-500">60s</div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Room Options Section -->
    <div class="rounded-lg bg-neutral-800/50 p-5 shadow-md border border-neutral-700">
      <h3 class="mb-4 text-lg font-semibold text-purple-400">{{ __('Room Options') }}</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <CheckboxInput 
          v-if="user.admin" 
          v-model="form.is_featured" 
          :error="form.errors.is_featured" 
          :label="__('Featured')" 
          class="bg-neutral-700/50 rounded-lg p-3 hover:bg-neutral-700 transition-colors duration-200"
        />
        <CheckboxInput 
          v-model="form.is_chat_active" 
          :error="form.errors.is_chat_active" 
          :label="__('Chatbox')" 
          class="bg-neutral-700/50 rounded-lg p-3 hover:bg-neutral-700 transition-colors duration-200"
        />
        <CheckboxInput 
          v-model="form.is_random" 
          :error="form.errors.is_random" 
          :label="__('Random')" 
          class="bg-neutral-700/50 rounded-lg p-3 hover:bg-neutral-700 transition-colors duration-200"
        />
        <CheckboxInput 
          v-model="form.is_autostart" 
          :error="form.errors.is_autostart" 
          :label="__('Autostart')" 
          class="bg-neutral-700/50 rounded-lg p-3 hover:bg-neutral-700 transition-colors duration-200"
        />
        <CheckboxInput 
          v-model="form.has_password" 
          :label="__('Password')" 
          class="bg-neutral-700/50 rounded-lg p-3 hover:bg-neutral-700 transition-colors duration-200"
        />
      </div>
      
      <div class="mt-4" v-show="form.has_password">
        <TextInput 
          v-model="form.password" 
          :error="form.errors.password" 
          type="password" 
          autocomplete="new-password" 
          :label="__('Password')" 
          class="bg-neutral-700/50 rounded-lg"
        />
      </div>
    </div>
    
    <!-- Form Actions -->
    <div class="flex justify-end gap-3 pt-2">
      <button 
        v-if="modal" 
        @click="$emit('close')" 
        class="px-4 py-2 rounded-lg border border-neutral-600 bg-neutral-800 text-neutral-300 hover:bg-neutral-700 transition-colors duration-200" 
        type="button"
      >
        {{ __('Close') }}
      </button>
      <LoadingButton 
        :loading="form.processing" 
        class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white font-medium transition-all duration-200" 
        form="optionsForm" 
        type="submit"
      >
        {{ __('Update') }}
      </LoadingButton>
    </div>
  </form>
</template>
