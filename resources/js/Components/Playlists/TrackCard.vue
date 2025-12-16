<script setup>
import { ref } from 'vue'
import Icon from '@/Components/Icon.vue'
import MiniPlayer from '@/Components/MiniPlayer.vue'
import SelectInput from '@/Components/SelectInput.vue'
import Dropdown from '@/Components/Dropdown.vue'

const props = defineProps({
  track: {
    type: Object,
    required: true
  },
  answer_types: {
    type: Object,
    required: true
  },
  onEditAnswer: Function,
  onCreateAnswer: Function,
  onEditHint: Function,
  onUpdateDifficulty: Function,
  onRemove: Function,
  loadingStates: Object
})

const emit = defineEmits(['edit-answer', 'create-answer', 'edit-hint', 'update-difficulty', 'remove'])

</script>

<template>
  <div class="group relative rounded-lg border border-neutral-700/50 bg-neutral-800/50 p-4 transition-all duration-200 hover:border-neutral-600 hover:bg-neutral-800">
    <!-- Header: Provider & Actions -->
    <div class="mb-3 flex items-start justify-between">
      <div class="flex items-center gap-3">
        <a 
          :href="track.provider_url"
          target="_blank"
          class="text-neutral-400 hover:text-teal-500 transition-colors"
        >
          <Icon :name="track.provider" class="h-5 w-5" />
        </a>
        <MiniPlayer :key="`mini-player-card-${track.id}`" :track="track" />
      </div>
      
      <Dropdown placement="bottom-end">
        <button
          type="button"
          class="flex h-8 w-8 items-center justify-center rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white transition-colors"
          :disabled="props.loadingStates?.removeTrack === track.id"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
          </svg>
        </button>
        
        <template #dropdown>
          <div class="min-w-[180px] py-1">
            <button
              type="button"
              class="w-full px-4 py-2 text-left text-sm text-neutral-300 hover:bg-neutral-700 transition-colors"
              @click="$emit('create-answer', track)"
            >
              <div class="flex items-center gap-2">
                <Icon name="plus" class="h-4 w-4" />
                <span>{{ __('Add an answer') }}</span>
              </div>
            </button>
            <button
              type="button"
              class="w-full px-4 py-2 text-left text-sm text-neutral-300 hover:bg-neutral-700 transition-colors"
              @click="$emit('edit-hint', track)"
            >
              <div class="flex items-center gap-2">
                <Icon :name="track.hint ? 'edit' : 'plus'" class="h-4 w-4" />
                <span>{{ track.hint ? __('Edit hint') : __('Add hint') }}</span>
              </div>
            </button>
            <div class="my-1 border-t border-neutral-700"></div>
            <button
              type="button"
              class="w-full px-4 py-2 text-left text-sm text-red-400 hover:bg-red-400/10 transition-colors"
              @click="$emit('remove', track)"
              :disabled="props.loadingStates?.removeTrack === track.id"
            >
              <div class="flex items-center gap-2">
                <Icon name="delete" class="h-4 w-4" />
                <span>{{ __('Delete') }}</span>
              </div>
            </button>
          </div>
        </template>
      </Dropdown>
    </div>

    <!-- Answers -->
    <div class="mb-3 space-y-1.5">
      <div
        v-for="answer in track.answers"
        :key="answer.id"
        class="flex items-center justify-between rounded-md bg-neutral-700/30 px-2.5 py-1.5 transition-colors hover:bg-neutral-700/50 cursor-pointer"
        @click="$emit('edit-answer', track, answer)"
      >
        <div class="flex items-center gap-2 min-w-0 flex-1">
          <span class="text-sm font-medium text-neutral-200">{{ __(answer.type.name) }}:</span>
          <span class="text-sm text-neutral-200 truncate">{{ answer.value }}</span>
        </div>
        <span class="ml-2 text-xs text-teal-400 font-medium">{{ answer.score }}pts</span>
      </div>
      <button
        type="button"
        class="flex w-full items-center gap-1.5 rounded-md border border-dashed border-neutral-600 px-2.5 py-1.5 text-sm text-neutral-200 transition-colors hover:border-neutral-500 hover:text-neutral-100"
        @click="$emit('create-answer', track)"
      >
        <Icon name="plus" class="h-3 w-3" />
        <span>{{ __('Add an answer') }}</span>
      </button>
    </div>

    <!-- Hint -->
    <div v-if="track.hint" class="mb-3 flex items-start gap-2 rounded-md bg-yellow-400/5 border border-yellow-400/20 px-2.5 py-1.5">
      <Icon name="hint" class="h-3.5 w-3.5 text-yellow-400 flex-shrink-0 mt-0.5" />
      <p class="text-sm text-neutral-200 flex-1">{{ track.hint }}</p>
    </div>
    <button
      v-else
      type="button"
      class="mb-3 flex w-full items-center gap-1.5 text-sm text-neutral-200 hover:text-neutral-100 transition-colors"
      @click="$emit('edit-hint', track)"
    >
      <Icon name="plus" class="h-3 w-3" />
      <span>{{ __('Add hint') }}</span>
    </button>

    <!-- Footer: Difficulty, Votes, Date -->
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-700/50 pt-3">
      <div class="flex items-center gap-3">
        <SelectInput
          :model-value="track.dificulty"
          @change="(e) => $emit('update-difficulty', e, track)"
          class="w-28 text-xs"
          :disabled="props.loadingStates?.updateDifficulty === track.id"
        >
          <option :value="0">{{ __('Easy') }}</option>
          <option :value="1">{{ __('Medium') }}</option>
          <option :value="2">{{ __('Difficult') }}</option>
          <option :value="3">{{ __('Expert') }}</option>
        </SelectInput>
      </div>
      
      <div class="flex items-center gap-4 text-sm text-neutral-200">
        <div class="flex items-center gap-1">
          <Icon name="thumb-up" class="h-3.5 w-3.5 text-teal-400" />
          <span>{{ track.up_votes }}</span>
        </div>
        <div class="flex items-center gap-1">
          <Icon name="thumb-down" class="h-3.5 w-3.5 text-red-400" />
          <span>{{ track.down_votes }}</span>
        </div>
        <span class="text-xs">{{ track.created_at }}</span>
      </div>
    </div>
  </div>
</template>

