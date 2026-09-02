<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Dropdown from '@/Components/Dropdown.vue'
import Icon from '@/Components/Icon.vue'

const DOWNVOTE_REASONS = [
  { value: 'sound_quality', label: 'Poor sound quality' },
  { value: 'difficulty', label: 'Too difficult' },
  { value: 'passage_choice', label: 'Bad passage choice' },
  { value: 'personal_taste', label: 'Not my taste' },
  { value: 'controversial_artist', label: 'Controversial artist' },
  { value: 'other', label: 'Other reason' },
]

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

const submitting = ref(false)

const adjustDownvoteCount = (delta) => {
  if (typeof props.track.downvotes === 'number') {
    props.track.downvotes = Math.max(0, props.track.downvotes + delta)
  }
}

const postDownvote = async (payload) => {
  if (!props.roomId || submitting.value) {
    return
  }

  submitting.value = true

  try {
    await axios.post(`/rooms/${props.roomId}/tracks/${props.track.id}/downvote`, payload)

    if (payload.reason) {
      if (props.track.user_voted_up) {
        if (typeof props.track.upvotes === 'number') {
          props.track.upvotes = Math.max(0, props.track.upvotes - 1)
        }
      }

      props.track.user_voted_down = true
      props.track.user_voted_up = false
      adjustDownvoteCount(1)
    } else {
      props.track.user_voted_down = false
      adjustDownvoteCount(-1)
    }
  } catch (error) {
    console.error('Error downvoting track:', error)
  } finally {
    submitting.value = false
  }
}

const handleTriggerClick = () => {
  if (props.track.user_voted_down) {
    postDownvote({})
  }
}

const handleReasonClick = (reason) => {
  postDownvote({ reason })
}
</script>

<template>
  <Dropdown v-if="!track.user_voted_down" placement="top-end" :overlay="false" class="room-track-vote-dropdown">
    <template #default>
      <button
        type="button"
        class="room-track-vote room-track-vote--down"
        :title="__('Downvote this track')"
        :aria-label="__('Downvote this track')"
        :aria-pressed="false"
        :aria-haspopup="true"
        :disabled="submitting"
      >
        <span class="room-track-vote__icon" aria-hidden="true">
          <Icon name="thumb-down" class="h-3.5 w-3.5" />
        </span>
        <span class="room-track-vote__count">{{ track.downvotes }}</span>
      </button>
    </template>
    <template #dropdown>
      <div class="room-downvote-menu">
        <p class="room-downvote-menu__title">{{ __('Why are you downvoting?') }}</p>
        <ul class="room-downvote-menu__list" role="menu">
          <li v-for="reason in DOWNVOTE_REASONS" :key="reason.value" role="none">
            <button
              type="button"
              class="room-downvote-menu__option"
              role="menuitem"
              :disabled="submitting"
              @click="handleReasonClick(reason.value)"
            >
              {{ __(reason.label) }}
            </button>
          </li>
        </ul>
      </div>
    </template>
  </Dropdown>

  <button
    v-else
    type="button"
    class="room-track-vote room-track-vote--down room-track-vote--down-active"
    :title="__('Remove downvote')"
    :aria-label="__('Remove downvote')"
    :aria-pressed="true"
    :disabled="submitting"
    @click="handleTriggerClick"
  >
    <span class="room-track-vote__icon" aria-hidden="true">
      <Icon name="thumb-down" class="h-3.5 w-3.5" />
    </span>
    <span class="room-track-vote__count">{{ track.downvotes }}</span>
  </button>
</template>
