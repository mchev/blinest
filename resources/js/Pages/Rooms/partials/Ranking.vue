<script setup>
import { ref, computed } from 'vue'
import { TransitionGroup } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import UserProfileBadge from '@/Components/UserProfileBadge.vue'
import PodiumModal from './PodiumModal.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
  },
  roomState: {
    type: Object,
    required: true,
    validator: (v) =>
      v &&
      Array.isArray(v.users) &&
      (v.scores == null || (typeof v.scores === 'object' && (! Array.isArray(v.scores) || v.scores.length === 0))),
  },
  track: {
    type: Object,
    default: null,
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const me = usePage().props.auth.user
const showPodiumModal = ref(false)

const sortedUsers = computed(() => {
  const users = props.roomState?.users || []
  const rawScores = props.roomState?.scores
  const scores = rawScores == null || Array.isArray(rawScores) ? {} : rawScores
  return [...users].sort((a, b) => (scores[b.id] || 0) - (scores[a.id] || 0))
})

function userScore(user) {
  const rawScores = props.roomState?.scores
  const scores = rawScores == null || Array.isArray(rawScores) ? {} : rawScores
  return scores[user.id] ?? 0
}

function userAnswers(user) {
  return props.roomState?.answersByUser?.[user.id] ?? []
}
</script>
<template>
  <div :class="{ 'h-full min-h-0 flex flex-col': compact }">
    <Card :class="{ 'room-ranking-card--compact h-full min-h-0 flex flex-col': compact }">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h3
            class="retro-title retro-title--secondary flex items-center"
            :class="compact ? 'text-lg' : 'text-xl'"
          >
            {{ __('Ranking') }}
          </h3>
          <div v-if="! compact" class="flex items-center">
            <span class="badge border border-white/10 bg-brand-midnight text-white/80 mr-2">{{ sortedUsers.length }} {{ __('players') }}</span>
            <button
              v-if="me"
              type="button"
              @click="showPodiumModal = true"
              :title="__('Show rankings for this room')"
              class="retro-icon-btn !h-8 !w-8 text-brand-secondary"
            >
              <Icon name="trophy" class="size-5" />
            </button>
          </div>
          <span v-else class="text-sm font-medium uppercase tracking-wide text-white/55">
            {{ sortedUsers.length }} {{ __('players') }}
          </span>
        </div>
      </template>

      <div
        class="overflow-y-auto pr-2"
        :class="compact ? 'room-ranking-scroll--compact min-h-0 flex-1' : 'h-48 sm:h-64 md:h-80 2xl:h-96'"
        style="scrollbar-width: thin; scrollbar-color: rgb(249 237 105 / 0.4) rgb(26 26 46 / 0.5);"
      >
        <p v-if="sortedUsers.length === 1" class="py-3 text-center text-sm text-white/60">
          {{ __('Waiting for other players…') }}
        </p>
        <component
          :is="compact ? 'ul' : TransitionGroup"
          :name="compact ? undefined : 'flip-list'"
          tag="ul"
          :class="compact ? 'space-y-1' : 'space-y-1 sm:space-y-2'"
        >
          <li
            v-for="(user, index) in sortedUsers"
            :key="user.id"
            class="room-rank-row"
            :class="{
              'room-rank-row--me': me && me.id === user.id,
              'room-rank-row--compact': compact,
            }"
          >
            <div
              class="flex shrink-0 items-center justify-center"
              :class="[
                compact ? 'h-8 w-8' : 'h-6 w-6 sm:h-8 sm:w-8',
                {
                  'bg-brand-secondary text-brand-midnight': index === 0,
                  'bg-brand-accent text-brand-midnight': index === 1,
                  'bg-brand-primary text-white': index === 2,
                  'bg-brand-midnight text-white/70': index > 2,
                },
              ]"
            >
              <span :class="compact ? 'text-base font-bold' : 'text-base sm:text-lg font-bold'">{{ index + 1 }}</span>
            </div>
            <div class="flex items-center shrink-0">
              <UserProfileBadge
                v-if="! compact"
                :user="user"
                size="md"
                variant="badge"
                :show-level="true"
                :show-elo="true"
                :ring-color="index === 0 ? 'ring-brand-secondary' : index === 1 ? 'ring-brand-accent' : index === 2 ? 'ring-brand-primary' : 'ring-white/20'"
              />
              <img
                v-else-if="user.photo"
                :src="user.photo"
                :alt="user.name"
                class="h-8 w-8 rounded-full ring-1 ring-white/20"
              />
            </div>
            <div class="flex flex-grow flex-col min-w-0">
              <div class="mb-1 flex items-center gap-2 flex-wrap" :class="{ 'sm:mb-2': ! compact }">
                <Link
                  v-if="user?.id"
                  :href="route('user.profile', { user: user.id })"
                  class="font-medium text-white hover:text-brand-secondary transition-colors truncate"
                  :class="compact ? 'text-base' : ''"
                >
                  {{ user.name }}
                </Link>
                <span v-else class="font-medium text-white/60 truncate" :class="compact ? 'text-sm' : ''">
                  {{ user?.name || __('Deleted user') }}
                </span>
                <Link
                  v-if="user.team && ! compact"
                  :href="route('teams.show', user.team)"
                  class="border border-white/10 bg-brand-midnight px-1.5 sm:px-2 py-0.5 text-[8px] sm:text-[9px] uppercase text-white/70 hover:border-white/20 transition-colors whitespace-nowrap"
                >
                  {{ user.team.name }}
                </Link>
              </div>
              <div v-if="userAnswers(user).length" class="flex flex-wrap items-center gap-1">
                <span
                  v-for="userAnswer in userAnswers(user)"
                  :key="userAnswer.id"
                  class="room-answer-badge"
                  :class="[
                    { 'mr-2': userAnswer.order < 4 && ! compact },
                    compact ? 'room-answer-badge--compact' : '',
                  ]"
                >
                  <span v-if="userAnswer.speedBonus && ! compact" class="mr-1 text-brand-secondary">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      class="h-2.5 w-2.5 sm:h-3 sm:w-3"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </span>
                  {{ compact ? __(userAnswer.name).charAt(0) : __(userAnswer.name) }}
                  <span
                    v-if="userAnswer.order < 4"
                    class="absolute -right-2 -top-1 flex items-center justify-center bg-brand-secondary font-bold text-brand-midnight"
                    :class="compact ? 'h-3 w-3 text-[8px]' : 'h-3 w-3 sm:h-4 sm:w-4 text-[8px] sm:text-[10px]'"
                  >
                    {{ userAnswer.order }}
                  </span>
                </span>
                <template v-if="props.track?.answers && ! compact">
                  <span
                    v-for="answer in props.track.answers"
                    :key="answer.id"
                    v-show="!userAnswers(user).some((a) => a.id === answer.id)"
                    class="room-answer-badge--ghost"
                  >
                    {{ __(answer.name) }}
                  </span>
                </template>
              </div>
            </div>
            <div
              class="font-bold shrink-0"
              :class="[
                compact ? 'text-lg' : 'text-base sm:text-lg',
                index === 0 ? 'text-brand-secondary' : 'text-white',
              ]"
            >
              {{ userScore(user) }}
              <sup class="text-xs text-white/50">{{ __('PTS') }}</sup>
            </div>
          </li>

          <li
            v-if="sortedUsers.length === 0"
            class="flex items-center justify-center py-8 text-white/50"
          >
            <div class="text-center">
              <Icon name="users" class="h-12 w-12 mx-auto mb-2 opacity-50" />
              <p>{{ __('No players yet') }}</p>
            </div>
          </li>
        </component>
      </div>
    </Card>

    <PodiumModal
      v-if="me && showPodiumModal"
      :room="room"
      :show="showPodiumModal"
      @close="showPodiumModal = false"
    />
  </div>
</template>
