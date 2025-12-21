<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import LevelBadge from '@/Components/LevelBadge.vue'
import EloBadge from '@/Components/EloBadge.vue'
import LevelModal from '@/Components/LevelModal.vue'

const page = usePage()
const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })
  return translation
}

const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
  type: {
    type: String,
    required: true, // 'level', 'score', 'week', 'teams'
  },
})

// Modal state
const showModal = ref(false)
const modalData = ref({
  level: 1,
  currentXp: 0,
  xpForNextLevel: 100,
  totalXp: 0,
  levelMetrics: null,
})
const isLoading = ref(false)

// Fetch user level metrics
const fetchUserLevelMetrics = async (userId) => {
  if (isLoading.value) return

  isLoading.value = true
  try {
    const response = await fetch(route('rankings.users.level-metrics', { user: userId }))
    const data = await response.json()
    
    modalData.value = {
      level: data.level,
      currentXp: data.current_xp,
      xpForNextLevel: data.xp_for_next_level,
      totalXp: data.total_xp,
      levelMetrics: data.level_metrics,
    }
    showModal.value = true
  } catch (error) {
    console.error('Error fetching user level metrics:', error)
  } finally {
    isLoading.value = false
  }
}

// Handle user click
const handleUserClick = (event, item) => {
  if (props.type === 'level' && item.user?.id) {
    event.preventDefault()
    fetchUserLevelMetrics(item.user.id)
  }
}

const getMedalIcon = (itemIndex, currentPage) => {
  if (currentPage === 1 && itemIndex === 0) return '🥇'
  if (currentPage === 1 && itemIndex === 1) return '🥈'
  if (currentPage === 1 && itemIndex === 2) return '🥉'
  return null
}

const getRankColor = (itemIndex, currentPage) => {
  if (currentPage === 1 && itemIndex === 0) return 'text-yellow-400'
  if (currentPage === 1 && itemIndex === 1) return 'text-gray-300'
  if (currentPage === 1 && itemIndex === 2) return 'text-amber-600'
  return 'text-neutral-400'
}

const getBorderColor = (itemIndex, currentPage) => {
  if (currentPage === 1 && itemIndex === 0) return 'border-yellow-500/30'
  if (currentPage === 1 && itemIndex === 1) return 'border-gray-400/30'
  if (currentPage === 1 && itemIndex === 2) return 'border-amber-600/30'
  return 'border-neutral-700'
}

const getBackgroundGradient = (itemIndex, currentPage) => {
  if (currentPage === 1 && itemIndex === 0) return 'from-yellow-500/5 to-yellow-600/0'
  if (currentPage === 1 && itemIndex === 1) return 'from-gray-400/5 to-gray-500/0'
  if (currentPage === 1 && itemIndex === 2) return 'from-amber-600/5 to-amber-700/0'
  return 'from-neutral-800/50 to-neutral-900/50'
}

// Calculer l'index réel en tenant compte de la pagination
const getRealIndex = (itemIndex, currentPage, perPage) => {
  return (currentPage - 1) * perPage + itemIndex + 1
}

// Formater les nombres avec séparateurs
const formatNumber = (num) => {
  if (!num && num !== 0) return '0'
  return new Intl.NumberFormat('fr-FR').format(num)
}

// Calculer le pourcentage de progression XP
const getXpProgress = (userLevel) => {
  if (!userLevel) return 0
  const currentXp = userLevel.current_xp || 0
  const xpForNext = userLevel.xp_for_next_level || 100
  if (xpForNext === 0) return 100
  return Math.min((currentXp / xpForNext) * 100, 100)
}

// Obtenir le niveau et les infos XP
const getLevelInfo = (item) => {
  const userLevel = item.user?.userLevel || item.userLevel
  if (!userLevel) return null
  return {
    level: userLevel.level || 1,
    totalXp: userLevel.total_xp || 0,
    currentXp: userLevel.current_xp || 0,
    xpForNext: userLevel.xp_for_next_level || 100,
    progress: getXpProgress(userLevel),
  }
}
</script>

<template>
  <div class="space-y-2.5 sm:space-y-3">
    <div
      v-for="(item, index) in items.data"
      :key="type === 'teams' ? item.team?.id : item.user?.id"
      class="group relative overflow-hidden rounded-xl border bg-gradient-to-r p-3.5 shadow-sm transition-all duration-300 hover:shadow-md hover:shadow-yellow-500/10 sm:p-4"
      :class="[
        getBorderColor(index, items.current_page),
        getBackgroundGradient(index, items.current_page),
      ]"
    >
      <!-- Top 3 Glow Effect -->
      <div
        v-if="items.current_page === 1 && index < 3"
        class="absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
        :class="
          index === 0
            ? 'bg-gradient-to-r from-yellow-500/10 via-transparent to-transparent'
            : index === 1
              ? 'bg-gradient-to-r from-gray-400/10 via-transparent to-transparent'
              : 'bg-gradient-to-r from-amber-600/10 via-transparent to-transparent'
        "
      />

      <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
        <!-- Rank Badge -->
        <div class="flex items-center gap-3 sm:gap-4">
          <div
            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg font-bold shadow-sm sm:h-14 sm:w-14 sm:rounded-xl"
            :class="
              items.current_page === 1 && index === 0
                ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-yellow-900'
                : items.current_page === 1 && index === 1
                  ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-gray-900'
                  : items.current_page === 1 && index === 2
                    ? 'bg-gradient-to-br from-amber-600 to-amber-800 text-amber-100'
                    : 'bg-neutral-800 text-neutral-400'
            "
          >
            <span
              v-if="getMedalIcon(index, items.current_page)"
              class="text-xl sm:text-2xl"
            >
              {{ getMedalIcon(index, items.current_page) }}
            </span>
            <span
              v-else
              class="text-sm font-extrabold sm:text-base"
            >
              #{{ getRealIndex(index, items.current_page, items.per_page) }}
            </span>
          </div>

          <!-- Photo -->
          <Link
            v-if="type !== 'teams' && type !== 'level' && item.user?.id"
            :href="route('user.profile', { user: item.user.id })"
            class="relative flex-shrink-0 transition-transform duration-200 hover:scale-105"
          >
            <div class="relative">
              <img
                :src="item.user.photo"
                :alt="item.user.name"
                class="h-11 w-11 rounded-full border-2 object-cover shadow-md sm:h-14 sm:w-14"
                :class="
                  items.current_page === 1 && index === 0
                    ? 'border-yellow-400 ring-2 ring-yellow-500/30'
                    : items.current_page === 1 && index === 1
                      ? 'border-gray-300 ring-2 ring-gray-400/30'
                      : items.current_page === 1 && index === 2
                        ? 'border-amber-600 ring-2 ring-amber-700/30'
                        : 'border-neutral-600'
                "
              />
              <!-- Online indicator (optional) -->
              <div
                v-if="items.current_page === 1 && index < 3"
                class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-neutral-900"
                :class="
                  index === 0
                    ? 'bg-yellow-400'
                    : index === 1
                      ? 'bg-gray-300'
                      : 'bg-amber-600'
                "
              />
            </div>
          </Link>
          <button
            v-else-if="type === 'level' && item.user?.id"
            @click="handleUserClick($event, item)"
            :disabled="isLoading"
            class="relative flex-shrink-0 transition-transform duration-200 hover:scale-105 cursor-pointer disabled:opacity-50"
          >
            <div class="relative">
              <img
                :src="item.user.photo"
                :alt="item.user.name"
                class="h-11 w-11 rounded-full border-2 object-cover shadow-md sm:h-14 sm:w-14"
                :class="
                  items.current_page === 1 && index === 0
                    ? 'border-yellow-400 ring-2 ring-yellow-500/30'
                    : items.current_page === 1 && index === 1
                      ? 'border-gray-300 ring-2 ring-gray-400/30'
                      : items.current_page === 1 && index === 2
                        ? 'border-amber-600 ring-2 ring-amber-700/30'
                        : 'border-neutral-600'
                "
              />
              <!-- Online indicator (optional) -->
              <div
                v-if="items.current_page === 1 && index < 3"
                class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-neutral-900"
                :class="
                  index === 0
                    ? 'bg-yellow-400'
                    : index === 1
                      ? 'bg-gray-300'
                      : 'bg-amber-600'
                "
              />
            </div>
          </button>
          <Link
            v-else-if="type === 'teams' && item.team"
            :href="route('teams.show', item.team.id)"
            class="relative flex-shrink-0 transition-transform duration-200 hover:scale-105"
          >
            <img
              :src="item.team.photo"
              :alt="item.team.name"
              class="h-11 w-11 rounded-full border-2 border-neutral-600 object-cover shadow-md sm:h-14 sm:w-14"
            />
          </Link>
        </div>

        <!-- User/Team Info -->
        <div class="flex flex-1 flex-col gap-2 min-w-0 sm:flex-row sm:items-center sm:gap-4">
          <div class="flex-1 min-w-0">
            <Link
              v-if="type !== 'teams' && type !== 'level' && item.user?.id"
              :href="route('user.profile', { user: item.user.id })"
              class="block truncate text-base font-bold text-white transition-colors hover:text-yellow-400 sm:text-lg"
            >
              {{ item.user.name }}
            </Link>
            <button
              v-else-if="type === 'level' && item.user?.id"
              @click="handleUserClick($event, item)"
              :disabled="isLoading"
              class="block truncate text-base font-bold text-white transition-colors hover:text-yellow-400 sm:text-lg text-left cursor-pointer disabled:opacity-50"
            >
              {{ item.user.name }}
            </button>
            <span
              v-else-if="type !== 'teams' && item.user"
              class="block truncate text-base font-bold text-neutral-400 sm:text-lg"
            >
              {{ item.user?.name || __('Deleted user') }}
            </span>
            <Link
              v-else-if="type === 'teams' && item.team"
              :href="route('teams.show', item.team.id)"
              class="block truncate text-base font-bold text-white transition-colors hover:text-yellow-400 sm:text-lg"
            >
              {{ item.team.name }}
            </Link>

            <!-- Level Badge (for non-level rankings) -->
            <div
              v-if="type !== 'level' && type !== 'teams' && (item.user?.userLevel?.level || item.userLevel?.level)"
              class="mt-1.5 sm:mt-2 flex items-center gap-1.5"
            >
              <LevelBadge
                :level="item.user?.userLevel?.level || item.userLevel?.level || 1"
                size="sm"
                variant="compact"
              />
              <EloBadge
                v-if="item.user?.elo || item.elo"
                :elo="item.user?.elo || item.elo || 1500"
                size="sm"
                variant="minimal"
              />
            </div>

            <!-- XP Progress Bar (for level ranking) -->
            <div
              v-if="type === 'level'"
              class="mt-2 space-y-1 sm:mt-2.5"
            >
              <div class="flex items-center justify-between text-xs text-neutral-400 sm:text-sm">
                <span>{{ formatNumber(getLevelInfo(item)?.totalXp || 0) }} {{ __('XP') }}</span>
                <span
                  v-if="getLevelInfo(item)?.xpForNext"
                  class="text-neutral-500"
                >
                  {{ formatNumber(getLevelInfo(item)?.currentXp || 0) }} / {{ formatNumber(getLevelInfo(item)?.xpForNext || 0) }}
                </span>
              </div>
              <div class="h-1.5 overflow-hidden rounded-full bg-neutral-800 sm:h-2">
                <div
                  class="h-full rounded-full bg-gradient-to-r transition-all duration-500"
                  :class="
                    items.current_page === 1 && index === 0
                      ? 'from-yellow-400 to-yellow-600'
                      : items.current_page === 1 && index === 1
                        ? 'from-gray-300 to-gray-500'
                        : items.current_page === 1 && index === 2
                          ? 'from-amber-600 to-amber-800'
                          : 'from-yellow-500 to-yellow-600'
                  "
                  :style="{ width: `${getLevelInfo(item)?.progress || 0}%` }"
                />
              </div>
            </div>
          </div>

          <!-- Score/Level Display -->
          <div class="flex items-center justify-between sm:justify-end sm:flex-col sm:items-end sm:gap-1">
            <div
              v-if="type === 'level'"
              class="text-right"
            >
              <div
                class="text-lg font-extrabold sm:text-xl"
                :class="
                  items.current_page === 1 && index === 0
                    ? 'text-yellow-400'
                    : items.current_page === 1 && index === 1
                      ? 'text-gray-300'
                      : items.current_page === 1 && index === 2
                        ? 'text-amber-600'
                        : 'text-white'
                "
              >
                {{ __('Level') }} {{ getLevelInfo(item)?.level || 1 }}
              </div>
            </div>
            <div
              v-else
              class="text-right"
            >
              <div
                class="text-lg font-extrabold sm:text-xl"
                :class="
                  items.current_page === 1 && index === 0
                    ? 'text-yellow-400'
                    : items.current_page === 1 && index === 1
                      ? 'text-gray-300'
                      : items.current_page === 1 && index === 2
                        ? 'text-amber-600'
                        : 'text-white'
                "
              >
                {{ formatNumber(type === 'teams' ? item.total_score : (item.total_score || item.score)) }}
                <span class="ml-1 text-xs font-normal text-neutral-400 sm:text-sm">{{ __('PTS') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Level Modal -->
  <LevelModal
    :show="showModal"
    :level="modalData.level"
    :current-xp="modalData.currentXp"
    :xp-for-next-level="modalData.xpForNextLevel"
    :total-xp="modalData.totalXp"
    :level-metrics="modalData.levelMetrics"
    @close="showModal = false"
  />
</template>
