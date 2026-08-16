<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  roomState: {
    type: Object,
    required: true,
  },
})

const me = usePage().props.auth.user

const scores = computed(() => {
  const rawScores = props.roomState?.scores

  return rawScores == null || Array.isArray(rawScores) ? {} : rawScores
})

const sortedUsers = computed(() => {
  const users = props.roomState?.users || []

  return [...users].sort((a, b) => (scores.value[b.id] || 0) - (scores.value[a.id] || 0))
})

const topThree = computed(() => sortedUsers.value.slice(0, 3))

const myRank = computed(() => {
  if (! me) {
    return null
  }

  const index = sortedUsers.value.findIndex((user) => user.id === me.id)

  return index === -1 ? null : index + 1
})

const myScore = computed(() => {
  if (! me) {
    return 0
  }

  const rawScores = props.roomState?.scores
  const scoreMap = rawScores == null || Array.isArray(rawScores) ? {} : rawScores

  return scoreMap[me.id] ?? 0
})

const rankMedalClass = (index) => {
  if (index === 0) {
    return 'room-mobile-podium__rank--gold'
  }

  if (index === 1) {
    return 'room-mobile-podium__rank--silver'
  }

  return 'room-mobile-podium__rank--bronze'
}
</script>

<template>
  <div v-if="sortedUsers.length > 0" class="room-mobile-podium">
    <div class="room-mobile-podium__leaders">
      <div
        v-for="(player, index) in topThree"
        :key="player.id"
        class="room-mobile-podium__entry"
        :class="{ 'room-mobile-podium__entry--me': me && me.id === player.id }"
      >
        <span class="room-mobile-podium__rank" :class="rankMedalClass(index)">
          {{ index + 1 }}
        </span>
        <img
          v-if="player.photo"
          :src="player.photo"
          :alt="player.name"
          class="room-mobile-podium__avatar"
        />
        <div class="min-w-0 flex-1">
          <p class="truncate text-sm font-semibold text-white">{{ player.name }}</p>
          <p class="text-sm font-bold text-brand-secondary">
            {{ scores[player.id] ?? 0 }}
            <span class="text-white/50">{{ __('PTS') }}</span>
          </p>
        </div>
      </div>
    </div>

    <div v-if="me && myRank" class="room-mobile-podium__me">
      <span class="text-xs uppercase tracking-wide text-white/55">{{ __('You') }}</span>
      <span class="text-base font-bold text-brand-accent">#{{ myRank }}</span>
      <span class="text-base font-semibold text-white">{{ myScore }} {{ __('PTS') }}</span>
    </div>
  </div>
</template>
