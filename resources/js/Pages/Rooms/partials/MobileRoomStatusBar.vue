<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  round: {
    type: Object,
    default: null,
  },
  roomState: {
    type: Object,
    required: true,
  },
})

const page = usePage()
const me = page.props.auth.user

function t(key) {
  return page.props.language?.[key] ?? key
}

const trackCount = computed(() => `${props.round?.current || 0}/${props.round?.tracks?.length || 0}`)

const progressPercentage = computed(() => {
  if (!props.round?.tracks?.length) {
    return 0
  }

  return Math.round((props.round.current / props.round.tracks.length) * 100)
})

const scores = computed(() => {
  const rawScores = props.roomState?.scores

  return rawScores == null || Array.isArray(rawScores) ? {} : rawScores
})

const sortedUsers = computed(() => {
  const users = props.roomState?.users || []

  return [...users].sort((a, b) => (scores.value[b.id] || 0) - (scores.value[a.id] || 0))
})

const playerRank = computed(() => {
  if (!me) {
    return null
  }

  const index = sortedUsers.value.findIndex((user) => user.id === me.id)

  return index === -1 ? null : index + 1
})

const playerScore = computed(() => {
  if (!me) {
    return 0
  }

  return scores.value[me.id] ?? 0
})

const rankClass = computed(() => {
  if (playerRank.value === 1) {
    return 'room-mobile-status__rank--gold'
  }

  if (playerRank.value === 2) {
    return 'room-mobile-status__rank--silver'
  }

  if (playerRank.value === 3) {
    return 'room-mobile-status__rank--bronze'
  }

  return ''
})

const statusLabel = computed(() => {
  const parts = [`${t('Track')} ${trackCount.value}`]

  if (playerRank.value) {
    parts.push(`${t('Rank')} #${playerRank.value}`, `${playerScore.value} ${t('PTS')}`)
  }

  return parts.join(' · ')
})
</script>

<template>
  <div class="room-mobile-status" role="status" :aria-label="statusLabel">
    <div class="room-mobile-status__line">
      <span class="room-mobile-status__label">{{ t('Track') }}</span>
      <span class="room-mobile-status__count">{{ trackCount }}</span>
      <div class="room-mobile-status__bar" role="progressbar" :aria-valuenow="progressPercentage" aria-valuemin="0" aria-valuemax="100" :aria-label="`${progressPercentage}%`">
        <div class="room-mobile-status__fill" :style="{ width: `${progressPercentage}%` }" />
      </div>
      <div v-if="playerRank" class="room-mobile-status__rank" :class="rankClass">
        <span>#{{ playerRank }}</span>
        <span class="room-mobile-status__score">{{ playerScore }} {{ t('PTS') }}</span>
      </div>
    </div>
  </div>
</template>
