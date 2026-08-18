<script setup>
import { ref, watch, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Spinner from '@/Components/Spinner.vue'
import MetricTooltip from '@/Components/MetricTooltip.vue'

const props = defineProps({
  room: Object,
  show: Boolean,
})

const emit = defineEmits(['close'])

const page = usePage()
const authUser = computed(() => page.props.auth?.user)

const loading = ref(false)
const error = ref(null)
const scores = ref(null)
const activeTab = ref('lifetime')

const tabs = [
  { id: 'lifetime', label: 'All-time', icon: 'trophy' },
  { id: 'teams', label: 'Teams', icon: 'users' },
  { id: 'week', label: 'Last 7 days', icon: 'calendar' },
]

const scoresCache = new Map()

const isTeamsTab = computed(() => activeTab.value === 'teams')

const currentScores = computed(() => {
  if (!scores.value) {
    return []
  }

  const tabScores = scores.value[activeTab.value] || []

  return Array.isArray(tabScores) ? tabScores : []
})

const currentUserEntry = computed(() => {
  if (!scores.value?.user || isTeamsTab.value) {
    return null
  }

  const userId = authUser.value?.id

  if (!userId) {
    return null
  }

  return currentScores.value.find((entry) => (entry.user_id || entry.user?.id) === userId) ?? null
})

const userScore = computed(() => {
  if (!scores.value?.user) {
    return null
  }

  if (activeTab.value === 'teams') {
    return scores.value.user.team
  }

  if (activeTab.value === 'week') {
    return scores.value.user.week
  }

  return scores.value.user.lifetime
})

const userPosition = computed(() => {
  if (!userScore.value) {
    return null
  }

  if (userScore.value.rank) {
    return userScore.value.rank
  }

  const userId = isTeamsTab.value ? userScore.value.team_id : userScore.value.user_id

  if (!userId) {
    return null
  }

  const index = currentScores.value.findIndex((entry) => {
    if (isTeamsTab.value) {
      return (entry.team_id || entry.team?.id) === userId
    }

    return (entry.user_id || entry.user?.id) === userId
  })

  return index >= 0 ? index + 1 : null
})

const formatNumber = (num) => {
  if (!num && num !== 0) {
    return '0'
  }

  return new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 1,
    minimumFractionDigits: 0,
  }).format(num)
}

const formatSeconds = (seconds) => {
  if (seconds === null || seconds === undefined) {
    return '—'
  }

  return `${formatNumber(seconds)}s`
}

const getMedalEmoji = (index) => {
  if (index === 0) {
    return '🥇'
  }

  if (index === 1) {
    return '🥈'
  }

  if (index === 2) {
    return '🥉'
  }

  return null
}

const getRowHighlight = (index) => {
  if (userPosition.value && index + 1 === userPosition.value) {
    return 'border-brand-accent/40 bg-brand-accent/10'
  }

  if (index === 0) {
    return 'border-yellow-500/30 bg-yellow-500/5'
  }

  if (index === 1) {
    return 'border-gray-400/25 bg-gray-400/5'
  }

  if (index === 2) {
    return 'border-amber-600/25 bg-amber-600/5'
  }

  return 'border-white/10 bg-brand-deep/40'
}

const entryKey = (entry, index) => {
  if (isTeamsTab.value) {
    return entry.team_id || entry.team?.id || index
  }

  return entry.user_id || entry.user?.id || index
}

const fetchScores = async () => {
  const cacheKey = props.room.id
  const cached = scoresCache.get(cacheKey)

  if (cached && Date.now() - cached.timestamp < 300000) {
    scores.value = cached.data
    loading.value = false

    return
  }

  loading.value = true
  error.value = null

  try {
    const response = await axios.get(route('rooms.scores.index', { room: props.room.id }))
    scores.value = response.data
    scoresCache.set(cacheKey, {
      data: response.data,
      timestamp: Date.now(),
    })
  } catch (err) {
    error.value = err.response?.data?.message || __('Failed to load scores. Please try again.')
    console.error('Error fetching scores:', err)
  } finally {
    loading.value = false
  }
}

watch(
  () => props.show,
  (isOpen) => {
    if (isOpen && !scores.value) {
      fetchScores()
    }
  },
  { immediate: true },
)

const switchTab = (tabId) => {
  activeTab.value = tabId

  const cacheKey = props.room.id
  const cached = scoresCache.get(cacheKey)

  if (cached && Date.now() - cached.timestamp > 60000) {
    fetchScores()
  }
}
</script>

<template>
  <Modal :show="show" maxWidth="5xl" @close="emit('close')">
    <div class="text-white">
      <div class="flex items-center justify-between border-b border-white/10 bg-brand-midnight/60 p-4 backdrop-blur-sm">
        <h2 class="retro-title retro-title--secondary flex items-center text-lg sm:text-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-brand-secondary sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <span class="truncate">{{ room.name }}</span>
          <span class="ml-2 hidden text-sm font-normal text-brand-accent sm:inline">{{ __('Leaderboard') }}</span>
        </h2>
        <button @click="emit('close')" :title="__('Close')" class="retro-icon-btn !h-9 !w-9">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div v-if="loading" class="flex w-full flex-col items-center justify-center p-16">
        <Spinner class="mb-4 h-12 w-12 text-brand-accent" />
        <p class="text-white/60">{{ __('Loading scores...') }}</p>
      </div>

      <div v-else-if="error" class="flex flex-col items-center justify-center p-16">
        <p class="mb-4 text-brand-primary-light">{{ error }}</p>
        <button @click="fetchScores" class="game-btn-play-join !min-h-[2.5rem] !w-auto px-4">
          {{ __('Retry') }}
        </button>
      </div>

      <div v-else class="p-3 sm:p-4">
        <div class="mb-4 flex gap-1 overflow-x-auto border-b border-white/10 sm:mb-6">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            @click="switchTab(tab.id)"
            class="relative -mb-px shrink-0 border-b-2 px-3 py-2 text-sm font-medium transition-colors sm:px-4"
            :class="activeTab === tab.id ? 'border-brand-accent text-white' : 'border-transparent text-white/60 hover:text-white'"
          >
            {{ __(tab.label) }}
          </button>
        </div>

        <div v-if="userScore" class="mb-4 rounded-lg border border-brand-accent/30 bg-brand-accent/10 p-3 sm:p-4">
          <div class="flex flex-col gap-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div>
                <div class="text-xs text-brand-accent">{{ __('Your Score') }}</div>
                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-xl font-bold text-white">{{ formatNumber(userScore.total || userScore.score || 0) }}</span>
                  <span class="text-xs text-white/60">{{ __('PTS') }}</span>
                  <span v-if="userPosition" class="text-sm font-medium text-brand-secondary">#{{ userPosition }}</span>
                </div>
              </div>
            </div>

            <div v-if="!isTeamsTab && currentUserEntry?.stats" class="flex flex-wrap gap-2">
              <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                <MetricTooltip :label="__('Level')" :tooltip="__('Your experience level based on XP earned while playing.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                <span class="text-sm font-bold text-white">{{ currentUserEntry.stats.level }}</span>
              </div>
              <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                <MetricTooltip :label="__('ELO')" :tooltip="__('Your competitive skill rating. It can go up or down depending on your results.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                <span class="text-sm font-bold text-white">{{ formatNumber(currentUserEntry.stats.elo) }}</span>
              </div>
              <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                <MetricTooltip :label="__('Score')" :tooltip="__('Total points earned in this room across all games played.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                <span class="text-sm font-bold text-white">{{ formatNumber(currentUserEntry.stats.score) }}</span>
                <span class="text-[10px] uppercase text-white/50">{{ __('PTS') }}</span>
              </div>
            </div>
          </div>
        </div>

        <div v-if="currentScores.length > 0" class="space-y-2">
          <article
            v-for="(entry, index) in currentScores"
            :key="entryKey(entry, index)"
            class="rounded-lg border p-3 transition-colors sm:p-4"
            :class="getRowHighlight(index)"
          >
            <div v-if="isTeamsTab" class="flex items-center gap-3">
              <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-midnight/70 text-sm font-bold sm:h-11 sm:w-11">
                <span v-if="getMedalEmoji(index)" class="text-lg">{{ getMedalEmoji(index) }}</span>
                <span v-else class="text-white/70">#{{ entry.rank || index + 1 }}</span>
              </div>
              <img
                :src="entry.team?.photo"
                :alt="entry.team?.name"
                class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-white/20 sm:h-11 sm:w-11"
                loading="lazy"
                @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(entry.team?.name || 'Team') + '&color=7F9CF5&background=EBF4FF'"
              />
              <div class="min-w-0 flex-1">
                <Link v-if="entry.team?.id" :href="route('teams.show', { team: entry.team.id })" class="block truncate font-semibold text-white hover:text-brand-accent">
                  {{ entry.team?.name }}
                </Link>
                <span v-else class="block truncate font-semibold text-white/60">{{ entry.team?.name || __('Deleted user') }}</span>
              </div>
              <div class="shrink-0 text-right">
                <div class="text-lg font-bold text-white">{{ formatNumber(entry.total) }}</div>
                <div class="text-[10px] uppercase tracking-wide text-white/50">{{ __('PTS') }}</div>
              </div>
            </div>

            <div v-else class="flex flex-col gap-3">
              <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-midnight/70 text-sm font-bold sm:h-11 sm:w-11">
                  <span v-if="getMedalEmoji(index)" class="text-lg">{{ getMedalEmoji(index) }}</span>
                  <span v-else class="text-white/70">#{{ entry.rank || index + 1 }}</span>
                </div>

                <img
                  :src="entry.user?.photo"
                  :alt="entry.user?.name"
                  class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-white/20 sm:h-11 sm:w-11"
                  loading="lazy"
                  @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(entry.user?.name || 'User') + '&color=7F9CF5&background=EBF4FF'"
                />

                <div class="min-w-0 flex-1">
                  <Link v-if="entry.user?.id && !entry.user?.is_guest" :href="route('user.profile', { user: entry.user.id })" class="block truncate font-semibold text-white hover:text-brand-accent">
                    {{ entry.user?.name }}
                  </Link>
                  <span v-else class="block truncate font-semibold text-white/60">{{ entry.user?.name || __('Deleted user') }}</span>

                  <div v-if="entry.stats" class="mt-2 flex flex-wrap gap-2">
                    <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                      <MetricTooltip :label="__('Level')" :tooltip="__('Your experience level based on XP earned while playing.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                      <span class="text-sm font-bold text-white">{{ entry.stats.level }}</span>
                    </div>
                    <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                      <MetricTooltip :label="__('ELO')" :tooltip="__('Your competitive skill rating. It can go up or down depending on your results.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                      <span class="text-sm font-bold text-white">{{ formatNumber(entry.stats.elo) }}</span>
                    </div>
                    <div class="inline-flex items-baseline gap-1.5 rounded-md border border-white/10 bg-brand-midnight/50 px-2 py-1">
                      <MetricTooltip :label="__('Score')" :tooltip="__('Total points earned in this room across all games played.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                      <span class="text-sm font-bold text-white">{{ formatNumber(entry.stats.score) }}</span>
                      <span class="text-[10px] uppercase text-white/50">{{ __('PTS') }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <dl v-if="entry.stats" class="grid grid-cols-2 gap-2 border-t border-white/10 pt-2 text-xs sm:grid-cols-3 lg:grid-cols-5">
                <div class="rounded-md border border-white/10 bg-brand-midnight/40 px-2 py-1.5">
                  <dt class="text-[10px] uppercase tracking-wide text-white/50">
                    <MetricTooltip :label="__('Avg. per round')" :tooltip="__('Average score earned per round in this room.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                  </dt>
                  <dd class="font-semibold text-white">{{ entry.stats.avg_score_per_round != null ? formatNumber(entry.stats.avg_score_per_round) : '—' }}</dd>
                </div>
                <div class="rounded-md border border-white/10 bg-brand-midnight/40 px-2 py-1.5">
                  <dt class="text-[10px] uppercase tracking-wide text-white/50">
                    <MetricTooltip :label="__('Avg. response time')" :tooltip="__('Average time to complete an excerpt (all answers found), in seconds. Lower is faster.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                  </dt>
                  <dd class="font-semibold text-white">{{ formatSeconds(entry.stats.avg_response_time) }}</dd>
                </div>
                <div class="rounded-md border border-white/10 bg-brand-midnight/40 px-2 py-1.5">
                  <dt class="text-[10px] uppercase tracking-wide text-white/50">
                    <MetricTooltip :label="__('Best round')" :tooltip="__('Your highest score achieved in a single round in this room.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                  </dt>
                  <dd class="font-semibold text-white">{{ entry.stats.best_round_score != null ? formatNumber(entry.stats.best_round_score) : '—' }}</dd>
                </div>
                <div class="rounded-md border border-white/10 bg-brand-midnight/40 px-2 py-1.5">
                  <dt class="text-[10px] uppercase tracking-wide text-white/50">
                    <MetricTooltip :label="__('Best streak')" :tooltip="__('Your longest streak of consecutive round wins in this room.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                  </dt>
                  <dd class="font-semibold text-white">{{ entry.stats.best_win_streak || '—' }}</dd>
                </div>
                <div class="rounded-md border border-white/10 bg-brand-midnight/40 px-2 py-1.5">
                  <dt class="text-[10px] uppercase tracking-wide text-white/50">
                    <MetricTooltip :label="__('Rounds played')" :tooltip="__('Number of complete rounds played in this room.')" label-class="text-[10px] font-medium uppercase tracking-wide text-white/50" placement="bottom" variant="brand" />
                  </dt>
                  <dd class="font-semibold text-white">{{ entry.stats.rounds_played || 0 }}</dd>
                </div>
              </dl>
            </div>
          </article>
        </div>

        <div v-else class="rounded-lg border border-white/10 bg-brand-deep/50 p-8 text-center text-white/60">
          {{ __('No scores available yet') }}
        </div>
      </div>
    </div>
  </Modal>
</template>
