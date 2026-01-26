<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import UserProfileBadge from '@/Components/UserProfileBadge.vue'
import PodiumModal from './PodiumModal.vue'

const props = defineProps({
  room: Object,
  users: Array,
  channel: String,
  data: Object,
  initialTrack: {
    type: Object,
    default: null,
  },
})

const me = usePage().props.auth.user
const scores = ref([])
const userList = ref(props?.users)
const track = ref(props?.initialTrack || null)
const showPodiumModal = ref(false)

// Initialiser les scores depuis Redis si disponibles
const initializeScoresFromRedis = () => {
  if (!props.data?.scores) {
    return
  }

  const redisScores = props.data.scores

  // Mettre à jour les scores des users
  userList.value.forEach((user) => {
    const userId = user.id
    const userScore = redisScores[userId] || 0

    // Initialiser le score si nécessaire
    if (!user.score) {
      user.score = { total: 0, answers: [] }
    }

    // Mettre à jour le score total depuis Redis
    user.score.total = userScore
  })

  // Trier par score décroissant
  userList.value.sort((a, b) => (b.score?.total || 0) - (a.score?.total || 0))
}

watch(
  () => props.users,
  (value) => {
    userList.value = value
    // Réinitialiser les scores si les users changent
    initializeScoresFromRedis()
  },
)

watch(
  () => props.data,
  () => {
    // Réinitialiser les scores si data change
    initializeScoresFromRedis()
  },
  { deep: true }
)

onMounted(() => {
  // Initialiser les scores depuis Redis au montage
  initializeScoresFromRedis()

  Echo.channel(props.channel)
    .listen('NewScore', (e) => {
      scores.value.push(e.score)
      let index = userList.value.findIndex((x) => x.id === e.score.user_id)
      if (index !== -1) {
        if (!userList.value[index].score) {
          userList.value[index].score = { total: 0, answers: [] }
        }
        userList.value[index].score.total = e.score.total
        // Ajouter les nouvelles réponses (éviter les doublons)
        e.score.answers.forEach((newAnswer) => {
          const existingIndex = userList.value[index].score.answers.findIndex((a) => a.id === newAnswer.id)
          if (existingIndex === -1) {
            userList.value[index].score.answers.push(newAnswer)
          } else {
            // Mettre à jour la réponse existante
            userList.value[index].score.answers[existingIndex] = newAnswer
          }
        })
        userList.value.sort((a, b) => (b.score?.total || 0) - (a.score?.total || 0))
      }
    })
    .listen('TrackPlayed', (e) => {
      track.value = e.track
      // Seulement réinitialiser les réponses (answers) pour la nouvelle track
      // Les scores totaux restent cumulatifs pour tout le round et se mettent à jour automatiquement via NewScore
      userList.value.forEach((x) => {
        if (x.score) {
          x.score.answers = []
        }
      })
      // Ne PAS toucher aux scores totaux - ils se mettent à jour automatiquement via les événements NewScore
    })
    .listen('RoundStarted', (e) => {
      // Réinitialiser tous les scores à 0 d'abord
      userList.value.forEach((x) => {
        if (x.score) {
          x.score.total = 0
          x.score.answers = []
        } else {
          // Initialiser le score si nécessaire
          x.score = { total: 0, answers: [] }
        }
      })
      // Réinitialiser les scores depuis Redis après un nouveau round
      // Le watch sur props.data se chargera de mettre à jour les scores quand ils seront disponibles
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})
</script>
<template>
  <div>
    <Card class="rounded-xl shadow-lg border border-neutral-800">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h3 class="text-xl font-bold flex items-center">
            {{ __('Ranking') }}
          </h3>
          <div class="flex items-center">
            <span class="badge bg-neutral-700/50 text-neutral-200 mr-2">{{ userList.length }} {{ __('players') }}</span>
            <button v-if="me" 
                    type="button" 
                    @click="showPodiumModal = true" 
                    :title="__('Show rankings for this room')"
                    class="text-neutral-400 hover:text-yellow-400 transition-colors">
                    <Icon name="trophy" class="size-6" />
            </button>
          </div>
        </div>
      </template>

      <div class="h-48 sm:h-64 md:h-80 2xl:h-96 overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(234, 179, 8, 0.5) rgba(0, 0, 0, 0.1);">
        <transition-group name="flip-list" tag="ul" class="space-y-1 sm:space-y-2">
          <li v-for="(user, index) in userList" 
              :key="user.id" 
              class="flex items-center gap-2 sm:gap-4 rounded-lg px-2 sm:px-3 py-2 sm:py-3 transition-all duration-200 hover:bg-black/20" 
              :class="{ 'bg-gradient-to-r from-black/20 to-black/40 border border-black/50': me && me.id === user.id }">
            <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full" 
                 :class="{
                   'bg-yellow-500 text-neutral-900': index === 0,
                   'bg-neutral-400 text-neutral-900': index === 1,
                   'bg-amber-700 text-neutral-100': index === 2,
                   'bg-neutral-700 text-neutral-300': index > 2
                 }">
              <span class="text-base sm:text-lg font-bold">{{ index + 1 }}</span>
            </div>
            <div class="flex items-center">
              <UserProfileBadge
                :user="user"
                size="md"
                variant="badge"
                :show-level="true"
                :show-elo="true"
                :ring-color="index === 0 ? 'ring-yellow-500' : index === 1 ? 'ring-neutral-400' : index === 2 ? 'ring-amber-700' : 'ring-neutral-700'"
              />
            </div>
            <div class="flex flex-grow flex-col min-w-0">
              <div class="mb-1 sm:mb-2 flex items-center gap-2 flex-wrap">
                <Link v-if="user?.id" :href="route('user.profile', { user: user.id })" class="font-medium text-neutral-100 hover:text-yellow-400 transition-colors truncate">
                  {{ user.name }}
                </Link>
                <span v-else class="font-medium text-neutral-400 truncate">
                  {{ user?.name || __('Deleted user') }}
                </span>
                <Link v-if="user.team" 
                      :href="route('teams.show', user.team)" 
                      class="rounded-full bg-neutral-700/50 px-1.5 sm:px-2 py-0.5 text-[8px] sm:text-[9px] uppercase text-neutral-300 hover:bg-neutral-600/50 transition-colors whitespace-nowrap">
                  {{ user.team.name }}
                </Link>
              </div>
              <div class="flex flex-wrap items-center gap-1">
                <span v-for="userAnswer in user.score.answers" 
                      v-if="user.score" 
                      class="relative flex items-center rounded-md bg-gradient-to-r from-purple-600 to-purple-500 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase text-white shadow-sm" 
                      :class="{ 'mr-2': userAnswer.order < 4 }">
                  <span v-if="userAnswer.speedBonus" class="mr-1 text-yellow-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-2.5 w-2.5 sm:h-3 sm:w-3">
                      <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                    </svg>
                  </span>
                  {{ __(userAnswer.name) }}
                  <span v-if="userAnswer.order < 4" 
                        class="absolute -right-2 -top-1 flex h-3 w-3 sm:h-4 sm:w-4 items-center justify-center rounded-full bg-yellow-500 text-[8px] sm:text-[10px] font-bold text-neutral-900 shadow">
                    {{ userAnswer.order }}
                  </span>
                </span>
                <span v-for="answer in track.answers" v-if="track">
                  <span v-if="!user?.score?.answers.find((x) => x.id === answer.id)" 
                        class="relative flex rounded-md bg-neutral-700 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase text-neutral-300 shadow-sm">
                    {{ __(answer.name) }}
                  </span>
                </span>
              </div>
            </div>
            <div class="font-bold text-base sm:text-lg" :class="{
              'text-yellow-400': index === 0,
              'text-neutral-300': index > 0
            }">
              {{ user?.score ? user.score.total : 0 }} 
              <sup class="text-[10px] sm:text-xs text-neutral-400">{{ __('PTS') }}</sup>
            </div>
          </li>
          
          <li v-if="userList.length === 0" class="flex items-center justify-center py-8 text-neutral-500">
            <div class="text-center">
              <Icon name="users" class="h-12 w-12 mx-auto mb-2 opacity-50" />
              <p>{{ __('No players yet') }}</p>
            </div>
          </li>
        </transition-group>
      </div>
    </Card>

    <PodiumModal v-if="me && showPodiumModal" :room="room" :show="showPodiumModal" @close="showPodiumModal = false" />
  </div>
</template>