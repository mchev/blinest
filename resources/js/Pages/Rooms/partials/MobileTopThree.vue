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

const podiumSlots = computed(() => {
  const [first, second, third] = topThree.value

  if (! first) {
    return []
  }

  if (! second) {
    return [{ player: first, rank: 1 }]
  }

  if (! third) {
    return [
      { player: second, rank: 2 },
      { player: first, rank: 1 },
    ]
  }

  return [
    { player: second, rank: 2 },
    { player: first, rank: 1 },
    { player: third, rank: 3 },
  ]
})

const myRank = computed(() => {
  if (! me) {
    return null
  }

  const index = sortedUsers.value.findIndex((user) => user.id === me.id)

  return index === -1 ? null : index + 1
})

const myScore = computed(() => scores.value[me?.id] ?? 0)

const isMeInTopThree = computed(() => topThree.value.some((user) => user.id === me?.id))

const showMyRankBar = computed(() => me && myRank.value && ! isMeInTopThree.value)

const rankMedalClass = (rank) => {
  if (rank === 1) {
    return 'room-mobile-podium__rank--gold'
  }

  if (rank === 2) {
    return 'room-mobile-podium__rank--silver'
  }

  return 'room-mobile-podium__rank--bronze'
}

const slotClass = (slot) => ({
  'room-mobile-podium__slot--first': slot.rank === 1,
  'room-mobile-podium__slot--me': me && me.id === slot.player.id,
})
</script>

<template>
  <div v-if="sortedUsers.length > 0" class="room-mobile-podium">
    <div class="room-mobile-podium__grid" :class="{ 'room-mobile-podium__grid--solo': podiumSlots.length === 1 }">
      <div
        v-for="slot in podiumSlots"
        :key="slot.player.id"
        class="room-mobile-podium__slot"
        :class="slotClass(slot)"
      >
        <div class="room-mobile-podium__avatar-wrap">
          <img
            v-if="slot.player.photo"
            :src="slot.player.photo"
            :alt="slot.player.name"
            class="room-mobile-podium__avatar"
          />
          <span
            class="room-mobile-podium__rank-badge"
            :class="rankMedalClass(slot.rank)"
          >
            {{ slot.rank }}
          </span>
        </div>
        <p class="room-mobile-podium__name" :title="slot.player.name">
          {{ slot.player.name }}
        </p>
        <p class="room-mobile-podium__score">
          {{ scores[slot.player.id] ?? 0 }}
          <span class="text-white/45">{{ __('PTS') }}</span>
        </p>
      </div>
    </div>

    <div v-if="showMyRankBar" class="room-mobile-podium__me">
      <span class="text-xs font-semibold uppercase tracking-wide text-white/55">{{ __('You') }}</span>
      <span class="font-bold text-brand-accent">#{{ myRank }}</span>
      <span class="font-semibold text-white">{{ myScore }} {{ __('PTS') }}</span>
    </div>
  </div>
</template>
