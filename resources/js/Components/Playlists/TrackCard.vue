<script setup>
import { computed } from 'vue'
import Icon from '@/Components/Icon.vue'
import MiniPlayer from '@/Components/MiniPlayer.vue'
import SelectInput from '@/Components/SelectInput.vue'

const DOWNVOTE_REASON_LABELS = {
  sound_quality: 'Poor sound quality',
  difficulty: 'Too difficult',
  passage_choice: 'Bad passage choice',
  personal_taste: 'Not my taste',
  controversial_artist: 'Controversial artist',
  other: 'Other reason',
}

const props = defineProps({
  track: {
    type: Object,
    required: true,
  },
  answer_types: {
    type: Object,
    required: true,
  },
  onEditAnswer: Function,
  onCreateAnswer: Function,
  onEditHint: Function,
  onUpdateDifficulty: Function,
  onRemove: Function,
  loadingStates: Object,
})

const emit = defineEmits(['edit-answer', 'create-answer', 'edit-hint', 'update-difficulty', 'remove'])

const downvoteBreakdown = computed(() => {
  if (!props.track.downvote_breakdown) {
    return []
  }

  return Object.entries(props.track.downvote_breakdown)
    .map(([reason, count]) => ({
      reason,
      count,
      label: __(DOWNVOTE_REASON_LABELS[reason] ?? reason),
    }))
    .sort((a, b) => b.count - a.count)
})
</script>

<template>
  <div class="group relative rounded-lg border border-neutral-700/50 bg-neutral-800/50 p-4 transition-all duration-200 hover:border-neutral-600 hover:bg-neutral-800">
    <!-- Header: Provider & Actions -->
    <div class="mb-3 flex items-start justify-between">
      <div class="flex items-center gap-3">
        <a :href="track.provider_url" target="_blank" class="text-neutral-400 transition-colors hover:text-teal-500">
          <Icon :name="track.provider" class="h-5 w-5" />
        </a>
        <MiniPlayer :key="`mini-player-card-${track.id}`" :track="track" />
      </div>

      <button type="button" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-400 transition-colors hover:bg-red-400/10 hover:text-red-300 disabled:opacity-50" :title="__('Delete')" :disabled="props.loadingStates?.removeTrack === track.id" @click="$emit('remove', track)">
        <svg v-if="props.loadingStates?.removeTrack === track.id" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-spin">
          <path d="M21 12a9 9 0 1 1-6.219-8.56" />
        </svg>
        <Icon v-else name="delete" class="h-4 w-4 text-red-400" />
      </button>
    </div>

    <!-- Answers -->
    <div class="mb-3 space-y-1.5">
      <div v-for="answer in track.answers" :key="answer.id" class="flex cursor-pointer items-center justify-between rounded-md bg-neutral-700/30 px-2.5 py-1.5 transition-colors hover:bg-neutral-700/50" @click="$emit('edit-answer', track, answer)">
        <div class="flex min-w-0 flex-1 items-center gap-2">
          <span class="text-sm font-medium text-neutral-200">{{ __(answer.type.name) }}:</span>
          <span class="truncate text-sm text-neutral-200">{{ answer.value }}</span>
        </div>
        <span class="ml-2 text-xs font-medium text-teal-400">{{ answer.score }}pts</span>
      </div>
      <button type="button" class="flex w-full items-center gap-1.5 rounded-md border border-dashed border-neutral-600 px-2.5 py-1.5 text-sm text-neutral-200 transition-colors hover:border-neutral-500 hover:text-neutral-100" @click="$emit('create-answer', track)">
        <Icon name="plus" class="h-3 w-3" />
        <span>{{ __('Add an answer') }}</span>
      </button>
    </div>

    <!-- Hint -->
    <div v-if="track.hint" class="mb-3 flex items-start gap-2 rounded-md border border-yellow-400/20 bg-yellow-400/5 px-2.5 py-1.5">
      <Icon name="hint" class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-yellow-400" />
      <p class="flex-1 text-sm text-neutral-200">{{ track.hint }}</p>
    </div>
    <button v-else type="button" class="mb-3 flex w-full items-center gap-1.5 text-sm text-neutral-200 transition-colors hover:text-neutral-100" @click="$emit('edit-hint', track)">
      <Icon name="plus" class="h-3 w-3" />
      <span>{{ __('Add hint') }}</span>
    </button>

    <!-- Footer: Difficulty, Votes, Date -->
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-700/50 pt-3">
      <div class="flex items-center gap-3">
        <SelectInput :model-value="track.dificulty" @change="(e) => $emit('update-difficulty', e, track)" class="w-28 text-xs" :disabled="props.loadingStates?.updateDifficulty === track.id">
          <option :value="0">{{ __('Easy') }}</option>
          <option :value="1">{{ __('Medium') }}</option>
          <option :value="2">{{ __('Difficult') }}</option>
          <option :value="3">{{ __('Expert') }}</option>
        </SelectInput>
      </div>

      <div class="flex flex-col items-end gap-2 text-sm text-neutral-200">
        <div class="flex items-center gap-4">
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
        <div v-if="downvoteBreakdown.length" class="flex max-w-full flex-wrap justify-end gap-1">
          <span v-for="entry in downvoteBreakdown" :key="entry.reason" class="rounded bg-red-400/10 px-1.5 py-0.5 text-[10px] font-medium text-red-300" :title="entry.label">
            {{ entry.label }} ({{ entry.count }})
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
