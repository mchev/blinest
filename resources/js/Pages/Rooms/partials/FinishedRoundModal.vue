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
    default: null,
  },
})

const emit = defineEmits(['close'])

const room = computed(() => props.room || props.round?.room)

const countdown = ref(parseInt(room.value?.pause_between_rounds || 0))
const users_results = ref(null)
const teams_results = ref(null)
const activeTab = ref('players')

const hasPlayers = computed(() => Boolean(users_results.value?.length))
const hasTeams = computed(() => Boolean(teams_results.value?.length))
const showTabs = computed(() => hasPlayers.value && hasTeams.value)

const visibleTab = computed(() => {
  if (!showTabs.value) {
    return hasPlayers.value ? 'players' : 'teams'
  }

  if (activeTab.value === 'teams' && hasTeams.value) {
    return 'teams'
  }

  return hasPlayers.value ? 'players' : 'teams'
})

watch(
  () => props.round,
  () => {
    if (room.value) {
      countdown.value = parseInt(room.value.pause_between_rounds || 0)
    }
  },
)

watch(
  () => props.show,
  (value) => {
    if (value) {
      users_results.value = props.users_podium ?? users_results.value
      teams_results.value = props.teams_podium ?? teams_results.value
      activeTab.value = 'players'
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
  const interval = setInterval(() => {
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
  <Modal :show="show" maxWidth="3xl" @close="close">
    <Card>
      <template #header>
        <h2 class="retro-title retro-title--primary">{{ __('Round finished') }}</h2>
      </template>

      <div class="flex min-w-0 flex-col">
        <div v-if="showTabs" class="mb-4 flex gap-1 overflow-x-auto border-b border-white/10">
          <button type="button" class="relative -mb-px shrink-0 border-b-2 px-4 py-2 text-sm font-medium transition-colors" :class="activeTab === 'players' ? 'border-brand-accent text-white' : 'border-transparent text-white/60 hover:text-white'" @click="activeTab = 'players'">
            {{ __('Ranking') }}
          </button>
          <button type="button" class="relative -mb-px shrink-0 border-b-2 px-4 py-2 text-sm font-medium transition-colors" :class="activeTab === 'teams' ? 'border-brand-accent text-white' : 'border-transparent text-white/60 hover:text-white'" @click="activeTab = 'teams'">
            {{ __('Teams') }}
          </button>
        </div>

        <div v-if="visibleTab === 'players' && hasPlayers" class="w-full min-w-0">
          <h3 v-if="!showTabs" class="retro-title retro-title--secondary mb-2 py-2 text-center text-lg sm:text-xl">{{ __('Ranking') }}</h3>
          <Podium :list="users_results" />
          <ul class="max-h-64 overflow-y-auto overflow-x-hidden">
            <li v-for="(result, index) in users_results" :key="result.user?.id ?? index" class="room-rank-row m-1 min-w-0 text-sm sm:text-base">
              <span class="shrink-0 text-lg font-bold sm:text-xl">{{ index + 1 }}</span>
              <span class="flex min-w-0 flex-grow items-center gap-2">
                <span class="truncate font-medium">{{ result.user.name }}</span>
                <EloBadge v-if="result.user.elo" :elo="result.user.elo" size="sm" variant="compact" />
              </span>
              <span class="shrink-0 whitespace-nowrap font-semibold"
                >{{ result.total }}<sup class="ml-1 text-xs">{{ __('PTS') }}</sup></span
              >
            </li>
          </ul>
        </div>

        <div v-else-if="visibleTab === 'teams' && hasTeams" class="w-full min-w-0">
          <h3 v-if="!showTabs" class="retro-title retro-title--secondary mb-2 py-2 text-center text-lg sm:text-xl">{{ __('Teams') }}</h3>
          <Podium :list="teams_results" />
          <ul class="max-h-64 overflow-y-auto overflow-x-hidden">
            <li v-for="(result, index) in teams_results" :key="result.team?.id ?? index" class="room-rank-row m-1 min-w-0 text-sm sm:text-base">
              <span class="shrink-0 text-lg font-bold sm:text-xl">{{ index + 1 }}</span>
              <span class="min-w-0 flex-grow truncate font-medium">{{ result.team.name }}</span>
              <span class="shrink-0 whitespace-nowrap font-semibold"
                >{{ result.total }}<sup class="ml-1 text-xs">{{ __('PTS') }}</sup></span
              >
            </li>
          </ul>
        </div>

        <div v-if="!hasPlayers && !hasTeams">
          {{ __('No scores') }}
        </div>
      </div>

      <template #footer>
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
          <div class="flex min-w-0 flex-grow flex-col">
            <div v-if="room && room.is_autostart" class="relative flex h-6 w-full min-w-0 items-center overflow-hidden bg-brand-midnight">
              <div class="flex h-6 items-center justify-center bg-brand-accent/80 text-white transition-all duration-1000 ease-linear" :style="'width:' + (countdown / parseInt(room.pause_between_rounds || 1)) * 100 + '%'">
                <span class="absolute bottom-0 left-0 right-0 top-0 flex items-center justify-center text-xs text-white sm:text-sm">{{ __('Next game in') }} {{ countdown }}</span>
              </div>
            </div>
          </div>
          <div class="flex shrink-0 items-center justify-end">
            <button type="button" class="btn-secondary" @click="close">{{ __('Close') }}</button>
          </div>
        </div>
      </template>
    </Card>
  </Modal>
</template>
