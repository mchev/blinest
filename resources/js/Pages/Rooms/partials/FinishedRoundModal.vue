<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import EloBadge from '@/Components/EloBadge.vue'
import Podium from './Podium.vue'

const props = defineProps({
  round: Object,
  users_podium: Array,
  teams_podium: Array,
  show: Boolean,
  room: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close'])

// Use room from props if available, otherwise try round.room
const room = computed(() => props.room || props.round?.room)

const countdown = ref(parseInt(room.value?.pause_between_rounds || 0))
const users_results = ref(null)
const teams_results = ref(null)

watch(
  () => props.round,
  (value) => {
    if (room.value) {
      countdown.value = parseInt(room.value.pause_between_rounds || 0)
    }
  },
)

watch(
  () => props.show,
  (value) => {
    if (value) {
      startCountdown()
    }
  },
)

watch(
  () => props.users_podium,
  (value) => {
    if (value) {
      users_results.value = value
    }
  },
)

watch(
  () => props.teams_podium,
  (value) => {
    if (value) {
      teams_results.value = value
    }
  },
)

onMounted(() => {
  startCountdown()
})

const startCountdown = () => {
  let interval = setInterval(() => {
    if (countdown.value === 0) {
      clearInterval(interval)
    } else {
      countdown.value--
    }
  }, 1000)
}

const close = () => {
  emit('close')
}
</script>
<template>
  <Modal :show="show" @close="close">
    <Card>
      <template #header>
        <h2 class="retro-title retro-title--primary">{{ __('Round finished') }}</h2>
      </template>
      <div class="flex min-w-0 flex-col gap-6 lg:flex-row lg:justify-between">
        <div class="min-w-0 w-full" v-if="users_results && users_results.length">
          <h3 class="retro-title retro-title--secondary mb-2 py-2 text-center text-lg sm:text-xl">{{ __('Ranking') }}</h3>
          <Podium :list="users_results" />
          <ul class="max-h-48 overflow-y-auto overflow-x-hidden">
            <li v-for="(result, index) in users_results" :key="result.user?.id ?? index" class="room-rank-row m-1 min-w-0 text-sm sm:text-base">
              <span class="shrink-0 text-lg font-bold sm:text-xl">{{ index + 1 }}</span>
              <span class="flex min-w-0 flex-grow items-center gap-2">
                <span class="truncate font-medium">{{ result.user.name }}</span>
                <EloBadge v-if="result.user.elo" :elo="result.user.elo" size="sm" variant="compact" />
              </span>
              <span class="shrink-0 whitespace-nowrap font-semibold">{{ result.total }}<sup class="ml-1 text-xs">{{ __('PTS') }}</sup></span>
            </li>
          </ul>
        </div>
        <div class="min-w-0 w-full" v-if="teams_results && teams_results.length">
          <h3 class="retro-title retro-title--secondary mb-2 py-2 text-center text-lg sm:text-xl">{{ __('Teams') }}</h3>
          <Podium :list="teams_results" />
          <ul class="max-h-48 overflow-y-auto overflow-x-hidden">
            <li v-for="(result, index) in teams_results" :key="result.team?.id ?? index" class="room-rank-row m-1 min-w-0 text-sm sm:text-base">
              <span class="shrink-0 text-lg font-bold sm:text-xl">{{ index + 1 }}</span>
              <span class="min-w-0 flex-grow truncate font-medium">{{ result.team.name }}</span>
              <span class="shrink-0 whitespace-nowrap font-semibold">{{ result.total }}<sup class="ml-1 text-xs">{{ __('PTS') }}</sup></span>
            </li>
          </ul>
        </div>
      </div>
      <div v-if="users_results && !users_results.length && teams_results && !teams_results.length">
        {{ __('No scores') }}
      </div>
      <template #footer>
          <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
            <div class="flex min-w-0 flex-grow flex-col">
              <div v-if="room && room.is_autostart" class="relative flex h-6 w-full min-w-0 items-center overflow-hidden bg-brand-midnight">
                <div class="flex h-6 items-center justify-center bg-brand-accent/80 text-white transition-all duration-1000 ease-linear" :style="'width:' + (countdown / parseInt(room.pause_between_rounds || 1)) * 100 + '%'">
                  <span class="absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-xs text-white sm:text-sm">{{ __('Next game in') }} {{ countdown }}</span>
                </div>
              </div>
            </div>
          <div class="flex shrink-0 items-center justify-end">
            <button type="button" class="btn-secondary" @click="$emit('close')">{{ __('Close') }}</button>
          </div>
        </div>
      </template>
    </Card>
  </Modal>
</template>