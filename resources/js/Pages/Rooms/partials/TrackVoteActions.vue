<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Icon from '@/Components/Icon.vue'
import TrackDownvoteMenu from './TrackDownvoteMenu.vue'

const props = defineProps({
  track: {
    type: Object,
    required: true,
  },
  roomId: {
    type: Number,
    required: true,
  },
  variant: {
    type: String,
    default: 'desktop',
  },
})

const submittingUp = ref(false)

const adjustVoteCount = (field, delta) => {
  if (typeof props.track[field] === 'number') {
    props.track[field] = Math.max(0, props.track[field] + delta)
  }
}

const voteUp = async () => {
  if (!props.roomId || submittingUp.value) {
    return
  }

  const wasUpvoted = Boolean(props.track.user_voted_up)
  submittingUp.value = true

  try {
    await axios.post(`/rooms/${props.roomId}/tracks/${props.track.id}/upvote`)

    if (wasUpvoted) {
      return
    }

    if (props.track.user_voted_down) {
      adjustVoteCount('downvotes', -1)
    }

    props.track.user_voted_up = true
    props.track.user_voted_down = false
    adjustVoteCount('upvotes', 1)
  } catch (error) {
    console.error('Error upvoting track:', error)
  } finally {
    submittingUp.value = false
  }
}
</script>

<template>
  <div class="room-track-votes" :class="variant === 'mobile' ? 'room-track-votes--mobile' : 'room-track-votes--desktop'">
    <button
      type="button"
      class="room-track-vote room-track-vote--up"
      :class="{ 'room-track-vote--up-active': track.user_voted_up }"
      :title="__('Upvote this track')"
      :aria-label="__('Upvote this track')"
      :aria-pressed="track.user_voted_up"
      :disabled="submittingUp"
      @click="voteUp"
    >
      <span class="room-track-vote__icon" aria-hidden="true">
        <Icon name="thumb-up" class="h-3.5 w-3.5" />
      </span>
      <span class="room-track-vote__count">{{ track.upvotes }}</span>
    </button>

    <TrackDownvoteMenu :track="track" :room-id="roomId" :variant="variant" />
  </div>
</template>
