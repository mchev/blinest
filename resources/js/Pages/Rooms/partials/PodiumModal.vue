<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Spinner from '@/Components/Spinner.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  room: Object,
  show: Boolean,
})

const loading = ref(true)
const scores = ref(null)
const activeTab = ref('lifetime')

const tabs = [
  { id: 'lifetime', label: 'All-time' },
  { id: 'teams', label: 'Teams' },
  { id: 'week', label: 'Last 7 days' },
]

const currentScores = computed(() => {
  if (!scores.value) return []
  return scores.value[activeTab.value] || []
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

onMounted(() => {
  axios.get(`/rooms/${props.room.id}/scores`).then((response) => {
    loading.value = false
    scores.value = response.data
  })
})

const getMedalClass = (index) => {
  if (index === 0) return 'from-yellow-400 to-yellow-600 text-yellow-100'
  if (index === 1) return 'from-gray-300 to-gray-500 text-gray-100'
  if (index === 2) return 'from-amber-600 to-amber-800 text-amber-100'
  return ''
}
</script>

<template>
  <Modal :show="show" maxWidth="5xl">
    <div class="bg-gradient-to-b from-neutral-800 to-neutral-900 text-neutral-200 rounded-lg overflow-hidden shadow-2xl border border-neutral-700">
      <!-- Header -->
      <div class="bg-gradient-to-r from-purple-900/50 to-purple-700/30 backdrop-blur-sm p-4 border-b border-neutral-700 flex items-center justify-between">
        <h2 class="font-bold text-xl text-white flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          {{ room.name }} <span class="ml-2 text-sm font-normal text-purple-300">{{ __('Leaderboard') }}</span>
        </h2>
        <button @click="$emit('close')" :title="__('Close')" class="p-2 rounded-full hover:bg-neutral-700/50 transition-all duration-200 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Loading state -->
      <div v-if="loading" class="flex w-full items-center justify-center p-16">
        <Spinner class="h-12 w-12 text-purple-500" />
      </div>

      <!-- Content -->
      <div v-else class="p-4">
        <!-- Tabs -->
        <div class="flex border-b border-neutral-700 mb-6">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-4 py-2 font-medium transition-all duration-200 border-b-2 -mb-px"
            :class="activeTab === tab.id ? 'border-purple-500 text-white' : 'border-transparent hover:border-neutral-600 hover:text-white'"
          >
            {{ __(tab.label) }}
          </button>
        </div>

        <!-- User's score highlight -->
        <div v-if="userScore" class="mb-6 bg-gradient-to-r from-purple-900/30 to-purple-700/20 rounded-lg p-4 border border-purple-800/50 flex items-center justify-between">
          <div class="flex items-center">
            <div class="bg-purple-700 rounded-full p-2 mr-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <div class="text-sm text-purple-300">{{ __('Your Score') }}</div>
              <div class="font-bold text-white">{{ userScore.total || userScore.score || 0 }} <span class="text-xs font-normal text-purple-300">{{ __('POINTS') }}</span></div>
            </div>
          </div>
        </div>

        <!-- Top 3 podium -->
        <div v-if="currentScores.length > 0" class="mb-4">
          <div class="flex items-end justify-center gap-3 h-36 mb-3">
            <!-- 2nd place -->
            <div v-if="currentScores[1]" class="flex flex-col items-center">
              <div class="mb-1">
                <img 
                  :src="activeTab === 'teams' ? currentScores[1].team?.photo : currentScores[1].user?.photo" 
                  :alt="activeTab === 'teams' ? currentScores[1].team?.name : currentScores[1].user?.name"
                  class="h-12 w-12 rounded-full object-cover border-2 border-gray-300 shadow-lg"
                  onerror="this.src='https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'"
                />
              </div>
              <div class="text-xs font-medium text-center text-white max-w-24 truncate">
                {{ activeTab === 'teams' ? currentScores[1].team?.name : currentScores[1].user?.name }}
              </div>
              <div class="text-gray-300 text-sm font-bold">{{ currentScores[1].total }} pts</div>
              <div class="h-24 w-20 mt-1 bg-gradient-to-b from-gray-300 to-gray-500 rounded-t-lg flex items-center justify-center">
                <span class="text-xl font-bold text-white">2</span>
              </div>
            </div>
            
            <!-- 1st place -->
            <div v-if="currentScores[0]" class="flex flex-col items-center scale-110 z-10">
              <div class="mb-1">
                <div class="relative">
                  <img 
                    :src="activeTab === 'teams' ? currentScores[0].team?.photo : currentScores[0].user?.photo" 
                    :alt="activeTab === 'teams' ? currentScores[0].team?.name : currentScores[0].user?.name"
                    class="h-14 w-14 rounded-full object-cover border-2 border-yellow-400 shadow-lg"
                    onerror="this.src='https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'"
                  />
                  <div class="absolute -top-2 -right-2 bg-yellow-500 text-white h-5 w-5 rounded-full flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                  </div>
                </div>
              </div>
              <div class="text-xs font-medium text-center text-white max-w-24 truncate">
                {{ activeTab === 'teams' ? currentScores[0].team?.name : currentScores[0].user?.name }}
              </div>
              <div class="text-yellow-300 text-sm font-bold">{{ currentScores[0].total }} pts</div>
              <div class="h-32 w-20 mt-1 bg-gradient-to-b from-yellow-400 to-yellow-600 rounded-t-lg flex items-center justify-center">
                <span class="text-xl font-bold text-white">1</span>
              </div>
            </div>
            
            <!-- 3rd place -->
            <div v-if="currentScores[2]" class="flex flex-col items-center">
              <div class="mb-1">
                <img 
                  :src="activeTab === 'teams' ? currentScores[2].team?.photo : currentScores[2].user?.photo" 
                  :alt="activeTab === 'teams' ? currentScores[2].team?.name : currentScores[2].user?.name"
                  class="h-12 w-12 rounded-full object-cover border-2 border-amber-600 shadow-lg"
                  onerror="this.src='https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'"
                />
              </div>
              <div class="text-xs font-medium text-center text-white max-w-24 truncate">
                {{ activeTab === 'teams' ? currentScores[2].team?.name : currentScores[2].user?.name }}
              </div>
              <div class="text-amber-400 text-sm font-bold">{{ currentScores[2].total }} pts</div>
              <div class="h-20 w-20 mt-1 bg-gradient-to-b from-amber-600 to-amber-800 rounded-t-lg flex items-center justify-center">
                <span class="text-xl font-bold text-white">3</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Full leaderboard -->
        <div class="overflow-hidden rounded-lg border border-neutral-700 bg-neutral-800/50 backdrop-blur-sm">
          <table class="w-full">
            <thead>
              <tr class="bg-neutral-700/50">
                <th class="p-3 text-left font-medium text-neutral-300">{{ __('Rank') }}</th>
                <th class="p-3 text-left font-medium text-neutral-300">{{ __('Name') }}</th>
                <th class="p-3 text-right font-medium text-neutral-300">{{ __('Score') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="(score, index) in currentScores" 
                :key="index"
                class="border-t border-neutral-700 hover:bg-neutral-700/30 transition-colors duration-150"
              >
                <td class="p-3 w-16">
                  <div 
                    v-if="index < 3" 
                    class="w-8 h-8 rounded-full bg-gradient-to-br flex items-center justify-center font-bold"
                    :class="getMedalClass(index)"
                  >
                    {{ index + 1 }}
                  </div>
                  <div v-else class="text-neutral-400 font-medium">{{ index + 1 }}</div>
                </td>
                <td class="p-3">
                  <div class="flex items-center">
                    <img 
                      :src="activeTab === 'teams' ? score.team?.photo : score.user?.photo" 
                      :alt="activeTab === 'teams' ? score.team?.name : score.user?.name"
                      class="h-8 w-8 rounded-full object-cover mr-3"
                      onerror="this.src='https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'"
                    />
                    <Link 
                      :href="activeTab === 'teams' ? route('teams.show', { team: score.team.id }) : route('user.profile', score.user)"
                      class="font-medium text-white hover:text-purple-300 transition-colors duration-150"
                    >
                      {{ activeTab === 'teams' ? score.team?.name : score.user?.name }}
                    </Link>
                  </div>
                </td>
                <td class="p-3 text-right">
                  <span class="font-bold text-white">{{ score.total }}</span>
                  <span class="text-xs text-neutral-400 ml-1">{{ __('PTS') }}</span>
                </td>
              </tr>
              <tr v-if="currentScores.length === 0" class="border-t border-neutral-700">
                <td colspan="3" class="p-6 text-center text-neutral-400">
                  {{ __('No scores available yet') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Modal>
</template>