<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import LevelBadge from '@/Components/LevelBadge.vue'
import LevelModal from '@/Components/LevelModal.vue'

const page = usePage()
const showLevelModal = ref(false)
const levelModalData = ref(null)

const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })
  return translation
}

const props = defineProps({
  position: {
    type: Number,
    default: null,
  },
  score: {
    type: [Number, String],
    default: null,
  },
  type: {
    type: String,
    required: true, // 'level', 'score', 'week', 'teams'
  },
})

const user = usePage().props.auth?.user

const positionColor = computed(() => {
  if (!props.position) return 'text-neutral-400'
  if (props.position <= 3) return 'text-yellow-400'
  if (props.position <= 10) return 'text-blue-400'
  if (props.position <= 50) return 'text-green-400'
  return 'text-neutral-400'
})

const positionBadge = computed(() => {
  if (!props.position) return null
  if (props.position === 1) return '🥇'
  if (props.position === 2) return '🥈'
  if (props.position === 3) return '🥉'
  return `#${props.position}`
})
</script>

<template>
  <div
    v-if="user && position"
    class="mt-6 rounded-xl border-2 border-yellow-500/30 bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 p-4 shadow-lg sm:mt-8 sm:p-6"
  >
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-center gap-3 sm:gap-4">
        <div
          :class="[
            'flex h-12 w-12 items-center justify-center rounded-full text-xl font-bold flex-shrink-0 sm:h-16 sm:w-16 sm:text-2xl',
            positionColor,
            'bg-gradient-to-br from-yellow-500/20 to-yellow-600/10',
            'border-2 border-yellow-500/30',
          ]"
        >
          {{ positionBadge }}
        </div>
        <div class="min-w-0 flex-1">
          <h3 class="text-base font-bold text-white truncate sm:text-lg">{{ user.name }}</h3>
          <p class="text-xs text-neutral-400 sm:text-sm">
            <span v-if="type === 'level'">{{ __('Level') }} {{ user.level || 1 }}</span>
            <span v-else-if="type === 'score'">{{ score }} {{ __('PTS') }}</span>
            <span v-else-if="type === 'elo'">{{ score }} {{ __('ELO') }}</span>
            <span v-else-if="type === 'week'">{{ score }} {{ __('PTS') }} ({{ __('Last 7 days') }})</span>
            <span v-else-if="type === 'minigames'">{{ score }} {{ __('PTS') }}</span>
            <span v-else-if="type === 'teams'">{{ score }} {{ __('PTS') }}</span>
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2 sm:gap-3">
        <LevelBadge
          v-if="type === 'level' && user.level"
          :level="user.level || 1"
          :current-xp="user.current_xp"
          :xp-for-next-level="user.xp_for_next_level"
          :total-xp="user.total_xp"
          :level-metrics="user.level_metrics"
          size="lg"
          variant="default"
          @click="(data) => { levelModalData = data; showLevelModal = true }"
        />
        <Link
          :href="route('user.profile', user)"
          class="rounded-lg bg-yellow-500/20 px-3 py-1.5 text-xs font-medium text-yellow-400 transition-colors hover:bg-yellow-500/30 sm:px-4 sm:py-2 sm:text-sm"
        >
          {{ __('View Profile') }}
        </Link>
      </div>
    </div>
    <LevelModal
      v-if="levelModalData"
      :show="showLevelModal"
      :level="levelModalData.level"
      :current-xp="levelModalData.currentXp"
      :xp-for-next-level="levelModalData.xpForNextLevel"
      :total-xp="levelModalData.totalXp"
      :level-metrics="levelModalData.levelMetrics"
      @close="showLevelModal = false"
    />
  </div>
</template>

