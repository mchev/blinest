<script setup>
import { ref, watch, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Spinner from '@/Components/Spinner.vue'

const props = defineProps({
  room: Object,
  show: Boolean,
})

const emit = defineEmits(['close'])

const loading = ref(false)
const error = ref(null)
const scores = ref(null)
const activeTab = ref('lifetime')

const tabs = [
  { id: 'lifetime', label: 'All-time', icon: 'trophy' },
  { id: 'teams', label: 'Teams', icon: 'users' },
  { id: 'week', label: 'Last 7 days', icon: 'calendar' },
]

// Cache des scores par room pour éviter les requêtes répétées
const scoresCache = new Map()

const currentScores = computed(() => {
  if (!scores.value) return []
  const tabScores = scores.value[activeTab.value] || []
  // S'assurer que c'est un tableau
  return Array.isArray(tabScores) ? tabScores : []
})

const userScore = computed(() => {
  if (!scores.value?.user) return null

  if (activeTab.value === 'teams') {
    return scores.value.user.team
  } else if (activeTab.value === 'week') {
    return scores.value.user.week
  } else {
    return scores.value.user.lifetime
  }
})

const userPosition = computed(() => {
  if (!userScore.value || !currentScores.value.length) return null

  const userTotal = userScore.value.total || userScore.value.score || 0
  const userId = activeTab.value === 'teams' ? userScore.value.team_id : userScore.value.user_id

  if (!userId) return null

  // Trouver la position de l'utilisateur dans le classement
  const position = currentScores.value.findIndex((score, index) => {
    const scoreTotal = score.total || 0
    const scoreId = activeTab.value === 'teams' ? score.team?.id || score.totalscorable_id : score.user?.id || score.totalscorable_id

    return scoreId === userId || (scoreTotal <= userTotal && index > 0)
  })

  return position >= 0 ? position + 1 : null
})

const formatNumber = (num) => {
  if (!num && num !== 0) return '0'
  return new Intl.NumberFormat('fr-FR', {
    maximumFractionDigits: 1,
    minimumFractionDigits: 0,
  }).format(num)
}

const getMedalClass = (index) => {
  if (index === 0) return 'bg-brand-secondary text-brand-midnight shadow-lg'
  if (index === 1) return 'bg-brand-accent text-brand-midnight shadow-lg'
  if (index === 2) return 'bg-brand-primary text-white shadow-lg'
  return ''
}

const fetchScores = async () => {
  // Vérifier le cache (valide 5 minutes)
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
    const response = await axios.get(`/rooms/${props.room.id}/scores`)
    scores.value = response.data
    // Mettre en cache
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

// Charger les scores quand le modal s'ouvre
watch(
  () => props.show,
  (isOpen) => {
    if (isOpen && !scores.value) {
      fetchScores()
    }
  },
  { immediate: true },
)

// Recharger quand on change d'onglet (optionnel, pour avoir des données fraîches)
const switchTab = (tabId) => {
  activeTab.value = tabId
  // Optionnel : recharger si les données sont anciennes
  const cacheKey = props.room.id
  const cached = scoresCache.get(cacheKey)
  if (cached && Date.now() - cached.timestamp > 60000) {
    fetchScores()
  }
}
</script>

<template>
  <Modal :show="show" maxWidth="5xl" @close="emit('close')">
    <div class="overflow-hidden text-white">
      <!-- Header -->
      <div class="flex items-center justify-between border-b border-white/10 bg-brand-midnight/60 p-4 backdrop-blur-sm">
        <h2 class="retro-title retro-title--secondary flex items-center text-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6 text-brand-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          {{ room.name }} <span class="ml-2 text-sm font-normal text-brand-accent">{{ __('Leaderboard') }}</span>
        </h2>
        <button @click="emit('close')" :title="__('Close')" class="retro-icon-btn !h-9 !w-9">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Loading state -->
      <div v-if="loading" class="flex w-full flex-col items-center justify-center p-16">
        <Spinner class="mb-4 h-12 w-12 text-brand-accent" />
        <p class="text-white/60">{{ __('Loading scores...') }}</p>
      </div>

      <!-- Error state -->
      <div v-else-if="error" class="flex flex-col items-center justify-center p-16">
        <svg xmlns="http://www.w3.org/2000/svg" class="mb-4 h-16 w-16 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="mb-4 text-brand-primary-light">{{ error }}</p>
        <button @click="fetchScores" class="game-btn-play-join !min-h-[2.5rem] !w-auto px-4">
          {{ __('Retry') }}
        </button>
      </div>

      <!-- Content -->
      <div v-else class="p-4">
        <!-- Tabs -->
        <div class="mb-6 flex gap-1 border-b border-white/10">
          <button v-for="tab in tabs" :key="tab.id" @click="switchTab(tab.id)" class="group relative -mb-px border-b-2 px-4 py-2 font-medium transition-all duration-200" :class="activeTab === tab.id ? 'border-brand-accent text-white' : 'border-transparent text-white/60 hover:border-white/20 hover:text-white'">
            <span class="flex items-center gap-2">
              <svg v-if="tab.icon === 'trophy'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              <svg v-else-if="tab.icon === 'users'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <svg v-else-if="tab.icon === 'calendar'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              {{ __(tab.label) }}
            </span>
            <span v-if="activeTab === tab.id" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-accent"></span>
          </button>
        </div>

        <!-- User's score highlight -->
        <div v-if="userScore" class="animate-fade-in mb-6 flex items-center justify-between border border-brand-accent/30 bg-brand-accent/10 p-4">
          <div class="flex items-center">
            <div class="mr-3 bg-brand-accent p-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-midnight" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <div class="text-sm text-brand-accent">{{ __('Your Score') }}</div>
              <div class="text-lg font-bold text-white">
                {{ formatNumber(userScore.total || userScore.score || 0) }}
                <span class="ml-1 text-xs font-normal text-white/60">{{ __('POINTS') }}</span>
                <span v-if="userPosition" class="ml-3 text-sm font-normal text-brand-secondary"> #{{ userPosition }} </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Top 3 podium - Gaming Style -->
        <div v-if="currentScores.length > 0" class="relative mb-0" style="margin-bottom: -8px; z-index: 1">
          <!-- Background glow effects -->
          <div class="pointer-events-none absolute inset-0 flex items-end justify-center gap-4" style="height: 200px; bottom: 0">
            <div class="animate-pulse-slow h-24 w-24 rounded-full bg-gray-400/10 blur-2xl" style="animation-delay: 0.1s"></div>
            <div class="animate-pulse-slow h-28 w-28 rounded-full bg-yellow-400/20 blur-2xl" style="animation-delay: 0.2s"></div>
            <div class="animate-pulse-slow h-24 w-24 rounded-full bg-amber-500/10 blur-2xl" style="animation-delay: 0.3s"></div>
          </div>

          <div class="relative z-10 flex items-end justify-center gap-6" style="min-height: 200px">
            <!-- 2nd place -->
            <Transition name="podium-enter" appear>
              <div v-if="currentScores[1]" class="podium-silver podium-3d-left relative" style="animation-delay: 0.1s; width: 120px">
                <!-- Podium stand with content inside -->
                <div class="podium-stand-silver relative w-full overflow-visible rounded-t-xl bg-gradient-to-b from-gray-300 via-gray-400 to-gray-500 shadow-xl">
                  <!-- Content inside podium - vertical layout -->
                  <div class="flex min-h-[128px] flex-col items-center gap-1.5 px-2 pb-2 pt-3">
                    <!-- Avatar -->
                    <div class="group relative flex-shrink-0">
                      <!-- Glow effect -->
                      <div class="absolute -inset-1.5 rounded-full bg-gradient-to-r from-gray-300 to-gray-500 opacity-40 blur-md transition-opacity duration-300 group-hover:opacity-60"></div>
                      <!-- Avatar frame -->
                      <div class="relative rounded-full bg-gradient-to-br from-gray-200 via-gray-300 to-gray-400 p-0.5 shadow-lg">
                        <div class="rounded-full bg-brand-midnight p-0.5">
                          <img :src="activeTab === 'teams' ? currentScores[1].team?.photo : currentScores[1].user?.photo" :alt="activeTab === 'teams' ? currentScores[1].team?.name : currentScores[1].user?.name" class="h-10 w-10 rounded-full object-cover" loading="lazy" @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(activeTab === 'teams' ? currentScores[1].team?.name || 'Team' : currentScores[1].user?.name || 'User') + '&color=7F9CF5&background=EBF4FF'" />
                        </div>
                      </div>
                      <!-- Rank badge below avatar -->
                      <div class="mx-auto mt-1.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-gradient-to-br from-gray-300 to-gray-500 text-xs font-black text-white shadow-lg">2</div>
                    </div>

                    <!-- Name -->
                    <div class="max-w-full truncate px-1 text-center text-[10px] font-bold text-white drop-shadow">
                      {{ activeTab === 'teams' ? currentScores[1].team?.name : currentScores[1].user?.name }}
                    </div>

                    <!-- Additional info for users -->
                    <div v-if="activeTab !== 'teams' && currentScores[1].user?.user_level" class="text-[9px] text-gray-300">Lv.{{ currentScores[1].user.user_level.level }}</div>

                    <!-- Score -->
                    <div class="text-xs font-black text-gray-100 drop-shadow">
                      {{ formatNumber(currentScores[1].total) }}
                    </div>
                  </div>

                  <!-- Corner decorations -->
                  <div class="absolute left-1.5 top-1.5 h-2 w-2 rounded-sm border border-white/30"></div>
                  <div class="absolute right-1.5 top-1.5 h-2 w-2 rounded-sm border border-white/30"></div>
                </div>
              </div>
            </Transition>

            <!-- 1st place -->
            <Transition name="podium-enter" appear>
              <div v-if="currentScores[0]" class="podium-gold relative z-20" style="animation-delay: 0.2s; width: 130px; transform: scale(1.05)">
                <!-- Podium stand with content inside -->
                <div class="podium-stand-gold relative w-full overflow-visible rounded-t-xl bg-gradient-to-b from-yellow-400 via-yellow-500 to-yellow-600 shadow-xl">
                  <!-- Crown effect above avatar -->
                  <div class="animate-bounce-slow absolute -top-6 left-1/2 z-20 -translate-x-1/2 transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 drop-shadow" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M5 16L3 5l5.5 3L12 4l3.5 4L21 5l-2 11H5zm14.3-4L19 6.4l-2.8 3.6h3.1zm-3.9 0l-1.4-1.8L12 9l-1.9 1.2-1.4 1.8H15.4zM5 12l1.4-1.8L8 12H5z" />
                    </svg>
                  </div>

                  <!-- Content inside podium - vertical layout -->
                  <div class="flex min-h-[160px] flex-col items-center gap-1.5 px-2 pb-2 pt-3">
                    <!-- Avatar -->
                    <div class="group relative flex-shrink-0">
                      <!-- Multiple glow layers -->
                      <div class="animate-pulse-glow absolute -inset-2 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 opacity-50 blur-xl"></div>
                      <div class="absolute -inset-1.5 rounded-full bg-gradient-to-r from-yellow-300 to-yellow-500 opacity-30 blur-lg"></div>
                      <!-- Avatar frame with gold border -->
                      <div class="animate-glow-gold relative rounded-full bg-gradient-to-br from-yellow-300 via-yellow-400 to-yellow-600 p-1 shadow-xl">
                        <div class="rounded-full bg-brand-midnight p-0.5">
                          <img :src="activeTab === 'teams' ? currentScores[0].team?.photo : currentScores[0].user?.photo" :alt="activeTab === 'teams' ? currentScores[0].team?.name : currentScores[0].user?.name" class="h-12 w-12 rounded-full object-cover ring-1 ring-yellow-400/50" loading="lazy" @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(activeTab === 'teams' ? currentScores[0].team?.name || 'Team' : currentScores[0].user?.name || 'User') + '&color=7F9CF5&background=EBF4FF'" />
                        </div>
                      </div>
                      <!-- Rank badge below avatar -->
                      <div class="relative mx-auto mt-1.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-gradient-to-br from-yellow-400 to-yellow-600 text-xs font-black text-white shadow-xl">
                        <span class="relative z-10">1</span>
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-500 opacity-50 blur-sm"></div>
                      </div>
                      <!-- Particle effects (CSS) -->
                      <div class="pointer-events-none absolute inset-0">
                        <div class="particle particle-1"></div>
                        <div class="particle particle-2"></div>
                        <div class="particle particle-3"></div>
                      </div>
                    </div>

                    <!-- Name -->
                    <div class="max-w-full truncate px-1 text-center text-[10px] font-black text-white drop-shadow">
                      {{ activeTab === 'teams' ? currentScores[0].team?.name : currentScores[0].user?.name }}
                    </div>

                    <!-- Additional info for users -->
                    <div v-if="activeTab !== 'teams' && currentScores[0].user?.user_level" class="text-[9px] text-yellow-300">Lv.{{ currentScores[0].user.user_level.level }}</div>

                    <!-- Score -->
                    <div class="animate-pulse-score text-xs font-black text-yellow-200 drop-shadow">
                      {{ formatNumber(currentScores[0].total) }}
                    </div>
                  </div>

                  <!-- Corner decorations -->
                  <div class="absolute left-2 top-2 h-3 w-3 rotate-45 rounded-sm border border-white/40"></div>
                  <div class="absolute right-2 top-2 h-3 w-3 rotate-45 rounded-sm border border-white/40"></div>
                  <!-- Animated particles inside -->
                  <div class="animate-float absolute left-4 top-4 h-1.5 w-1.5 rounded-full bg-white/40"></div>
                  <div class="animate-float-delayed absolute right-5 top-6 h-1 w-1 rounded-full bg-white/50"></div>
                </div>
              </div>
            </Transition>

            <!-- 3rd place -->
            <Transition name="podium-enter" appear>
              <div v-if="currentScores[2]" class="podium-bronze podium-3d-right relative" style="animation-delay: 0.3s; width: 120px">
                <!-- Podium stand with content inside -->
                <div class="podium-stand-bronze relative w-full overflow-visible rounded-t-xl bg-gradient-to-b from-amber-600 via-amber-700 to-amber-800 shadow-xl">
                  <!-- Content inside podium - vertical layout -->
                  <div class="flex min-h-[112px] flex-col items-center gap-1.5 px-2 pb-2 pt-3">
                    <!-- Avatar -->
                    <div class="group relative flex-shrink-0">
                      <!-- Glow effect -->
                      <div class="absolute -inset-1.5 rounded-full bg-gradient-to-r from-amber-600 to-amber-800 opacity-40 blur-md transition-opacity duration-300 group-hover:opacity-60"></div>
                      <!-- Avatar frame -->
                      <div class="relative rounded-full bg-gradient-to-br from-amber-600 via-amber-700 to-amber-800 p-0.5 shadow-lg">
                        <div class="rounded-full bg-brand-midnight p-0.5">
                          <img :src="activeTab === 'teams' ? currentScores[2].team?.photo : currentScores[2].user?.photo" :alt="activeTab === 'teams' ? currentScores[2].team?.name : currentScores[2].user?.name" class="h-10 w-10 rounded-full object-cover" loading="lazy" @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(activeTab === 'teams' ? currentScores[2].team?.name || 'Team' : currentScores[2].user?.name || 'User') + '&color=7F9CF5&background=EBF4FF'" />
                        </div>
                      </div>
                      <!-- Rank badge below avatar -->
                      <div class="mx-auto mt-1.5 flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-gradient-to-br from-amber-600 to-amber-800 text-xs font-black text-white shadow-lg">3</div>
                    </div>

                    <!-- Name -->
                    <div class="max-w-full truncate px-1 text-center text-[10px] font-bold text-white drop-shadow">
                      {{ activeTab === 'teams' ? currentScores[2].team?.name : currentScores[2].user?.name }}
                    </div>

                    <!-- Additional info for users -->
                    <div v-if="activeTab !== 'teams' && currentScores[2].user?.user_level" class="text-[9px] text-amber-300">Lv.{{ currentScores[2].user.user_level.level }}</div>

                    <!-- Score -->
                    <div class="text-xs font-black text-amber-200 drop-shadow">
                      {{ formatNumber(currentScores[2].total) }}
                    </div>
                  </div>

                  <!-- Corner decorations -->
                  <div class="absolute left-1.5 top-1.5 h-2 w-2 rounded-sm border border-white/30"></div>
                  <div class="absolute right-1.5 top-1.5 h-2 w-2 rounded-sm border border-white/30"></div>
                </div>
              </div>
            </Transition>
          </div>
        </div>

        <!-- Full leaderboard -->
        <div class="relative z-20 overflow-hidden rounded-lg border border-white/10 bg-brand-deep/80 backdrop-blur-sm">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-brand-midnight/50">
                  <th class="sticky left-0 z-10 bg-brand-midnight/50 p-3 text-left font-medium text-white/70">{{ __('Rank') }}</th>
                  <th class="p-3 text-left font-medium text-white/70">{{ __('Name') }}</th>
                  <th class="p-3 text-right font-medium text-white/70">{{ __('Score') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(score, index) in currentScores" :key="activeTab === 'teams' ? score.team?.id || score.totalscorable_id || index : score.user?.id || score.totalscorable_id || index" class="border-t border-white/10 transition-colors duration-150 hover:bg-brand-midnight/40" :class="{ 'bg-brand-accent/20': userPosition && index + 1 === userPosition }">
                  <td class="sticky left-0 z-10 w-16 bg-brand-deep/95 p-3">
                    <div v-if="index < 3" class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br font-bold shadow-md" :class="getMedalClass(index)">
                      <svg v-if="index === 0" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                      </svg>
                      <span v-else>{{ index + 1 }}</span>
                    </div>
                    <div v-else class="font-medium text-white/60">{{ index + 1 }}</div>
                  </td>
                  <td class="p-3">
                    <div class="flex items-center">
                      <img :src="activeTab === 'teams' ? score.team?.photo : score.user?.photo" :alt="activeTab === 'teams' ? score.team?.name : score.user?.name" class="mr-3 h-8 w-8 rounded-full object-cover ring-1 ring-white/20" loading="lazy" @error="$event.target.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(activeTab === 'teams' ? score.team?.name || 'Team' : score.user?.name || 'User') + '&color=7F9CF5&background=EBF4FF'" />
                      <Link v-if="activeTab === 'teams' ? score.team?.id : score.user?.id && !score.user?.is_guest" :href="activeTab === 'teams' ? route('teams.show', { team: score.team.id }) : route('user.profile', { user: score.user.id })" class="font-medium text-white transition-colors duration-150 hover:text-brand-accent">
                        {{ activeTab === 'teams' ? score.team?.name : score.user?.name }}
                      </Link>
                      <span v-else class="font-medium text-white/60">
                        {{ activeTab === 'teams' ? score.team?.name : score.user?.name || __('Deleted user') }}
                      </span>
                    </div>
                  </td>
                  <td class="p-3 text-right">
                    <span class="font-bold text-white">{{ formatNumber(score.total) }}</span>
                    <span class="ml-1 text-xs text-white/60">{{ __('PTS') }}</span>
                  </td>
                </tr>
                <tr v-if="currentScores.length === 0" class="border-t border-white/10">
                  <td colspan="3" class="p-6 text-center text-white/60">
                    <div class="flex flex-col items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ __('No scores available yet') }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.5s ease-out forwards;
}

.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.4s ease-out;
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Gaming Podium Animations */
.podium-enter-enter-active {
  transition: all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.podium-enter-enter-from {
  opacity: 0;
  transform: translateY(50px) scale(0.8) rotateY(-15deg);
}

.podium-gold {
  filter: drop-shadow(0 0 20px rgba(234, 179, 8, 0.5));
}

.podium-silver {
  filter: drop-shadow(0 0 15px rgba(156, 163, 175, 0.4));
}

.podium-bronze {
  filter: drop-shadow(0 0 15px rgba(217, 119, 6, 0.4));
}

.podium-stand-gold,
.podium-stand-silver,
.podium-stand-bronze {
  transform-style: preserve-3d;
  perspective: 1000px;
}

.podium-stand-gold:hover {
  transform: translateY(-2px) scale(1.01);
  transition: transform 0.3s ease;
}

.podium-stand-silver:hover,
.podium-stand-bronze:hover {
  transform: translateY(-1px) scale(1.005);
  transition: transform 0.3s ease;
}

/* Glow animations */
@keyframes pulse-glow {
  0%,
  100% {
    opacity: 0.6;
    transform: scale(1);
  }
  50% {
    opacity: 0.9;
    transform: scale(1.1);
  }
}

.animate-pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-slow {
  0%,
  100% {
    opacity: 0.3;
  }
  50% {
    opacity: 0.6;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}

@keyframes glow-gold {
  0%,
  100% {
    box-shadow: 0 0 20px rgba(234, 179, 8, 0.5), 0 0 40px rgba(234, 179, 8, 0.3);
  }
  50% {
    box-shadow: 0 0 30px rgba(234, 179, 8, 0.8), 0 0 60px rgba(234, 179, 8, 0.5);
  }
}

.animate-glow-gold {
  animation: glow-gold 2s ease-in-out infinite;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0) translateX(0);
    opacity: 0.4;
  }
  50% {
    transform: translateY(-10px) translateX(5px);
    opacity: 0.8;
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float 3s ease-in-out infinite 1.5s;
}

@keyframes bounce-slow {
  0%,
  100% {
    transform: translateY(0) translateX(-50%);
  }
  50% {
    transform: translateY(-10px) translateX(-50%);
  }
}

.animate-bounce-slow {
  animation: bounce-slow 2s ease-in-out infinite;
}

@keyframes pulse-score {
  0%,
  100% {
    transform: scale(1);
    text-shadow: 0 0 10px rgba(234, 179, 8, 0.5);
  }
  50% {
    transform: scale(1.05);
    text-shadow: 0 0 20px rgba(234, 179, 8, 0.8);
  }
}

.animate-pulse-score {
  animation: pulse-score 2s ease-in-out infinite;
}

/* Particle effects */
.particle {
  position: absolute;
  width: 4px;
  height: 4px;
  background: radial-gradient(circle, rgba(234, 179, 8, 0.8) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}

.particle-1 {
  top: 20%;
  left: 20%;
  animation: particle-float 4s ease-in-out infinite;
}

.particle-2 {
  top: 60%;
  right: 20%;
  animation: particle-float 4s ease-in-out infinite 1.3s;
}

.particle-3 {
  bottom: 20%;
  left: 50%;
  animation: particle-float 4s ease-in-out infinite 2.6s;
}

@keyframes particle-float {
  0%,
  100% {
    transform: translate(0, 0) scale(1);
    opacity: 0.6;
  }
  25% {
    transform: translate(10px, -15px) scale(1.2);
    opacity: 1;
  }
  50% {
    transform: translate(-5px, -25px) scale(0.8);
    opacity: 0.8;
  }
  75% {
    transform: translate(-15px, -10px) scale(1.1);
    opacity: 0.9;
  }
}

/* 3D Effect for podiums 2 and 3 */
.podium-3d-left {
  transform-style: preserve-3d;
  perspective: 1000px;
}

.podium-3d-left .podium-stand-silver {
  transform: perspective(1000px) rotateY(-12deg) rotateX(3deg);
  transition: transform 0.3s ease;
}

.podium-3d-left:hover .podium-stand-silver {
  transform: perspective(1000px) rotateY(-8deg) rotateX(2deg) translateY(-2px);
}

.podium-3d-right {
  transform-style: preserve-3d;
  perspective: 1000px;
}

.podium-3d-right .podium-stand-bronze {
  transform: perspective(1000px) rotateY(12deg) rotateX(3deg);
  transition: transform 0.3s ease;
}

.podium-3d-right:hover .podium-stand-bronze {
  transform: perspective(1000px) rotateY(8deg) rotateX(2deg) translateY(-2px);
}
</style>
