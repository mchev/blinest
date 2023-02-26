<script setup>
import { ref, watch, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'
import Podium from './Podium.vue'

const props = defineProps({
  round: Object,
  users_podium: Array,
  teams_podium: Array,
  show: Boolean,
})

const emit = defineEmits(['close'])

const countdown = ref(parseInt(props.round.room.pause_between_rounds))
const users_results = ref(null)
const teams_results = ref(null)

watch(
  () => props.round,
  (value) => {
    countdown.value = parseInt(props.round.room.pause_between_rounds)
  },
)

watch(
  () => props.show,
  (value) => {
    if (value) {
      startCountdown()
    }
  },
)

watch(
  () => props.users_podium,
  (value) => {
    if (value) {
      users_results.value = value
    }
  },
)

watch(
  () => props.teams_podium,
  (value) => {
    if (value) {
      teams_results.value = value
    }
  },
)

onMounted(() => {
  startCountdown()
})

const startCountdown = () => {
  let interval = setInterval(() => {
    if (countdown.value === 0) {
      clearInterval(interval)
    } else {
      countdown.value--
    }
  }, 1000)
}

const close = () => {
  emit('close')
}
</script>
<template>
  <Modal :show="show" @close="close">
    <Card>
      <template #header>
        <h2>{{ __('Round finished') }}</h2>
      </template>
      <div class="flex justify-between">
        <div class="w-full" v-if="users_results && users_results.length">
          <h3 class="mb-2 py-2 text-center text-xl font-bold">{{ __('Ranking') }}</h3>
          <Podium :list="users_results" />
          <ul class="max-h-48 overflow-auto">
            <li v-for="(result, index) in users_results" class="broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2">
              <span class="text-xl font-bold">{{ index + 1 }}</span>
              <span class="flex-grow">{{ result.user.name }}</span>
              <span>{{ result.total }}<sup class="ml-1">{{ __('PTS') }}</sup></span>
            </li>
          </ul>
        </div>
        <div class="w-full" v-if="teams_results && teams_results.length">
          <h3 class="mb-2 py-2 text-center text-xl font-bold">{{ __('Teams') }}</h3>
          <Podium :list="teams_results" />
          <ul class="max-h-48 overflow-auto">
            <li v-for="(result, index) in teams_results" class="broder-neutral-500 m-1 flex items-center gap-2 rounded border p-2">
              <span class="text-xl font-bold">{{ index + 1 }}</span>
              <span class="flex-grow">{{ result.team.name }}</span>
              <span>{{ result.total }}<sup class="ml-1">{{ __('PTS') }}</sup></span>
            </li>
          </ul>
        </div>
      </div>
      <div v-if="users_results && !users_results.length && teams_results && !teams_results.length">
        {{ __('No scores') }}
      </div>
      <template #footer>
        <div class="flex w-full items-center gap-6">
          <div class="flex flex-grow flex-col">
            <div class="relative flex h-6 w-full items-center overflow-hidden rounded-lg bg-purple-200">
              <div class="flex h-6 items-center justify-center rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 text-neutral-700 transition-all duration-1000 ease-linear" :style="'width:' + (countdown / parseInt(props.round.room.pause_between_rounds)) * 100 + '%'">
                <span class="absolute left-0 right-0 top-0 bottom-0 flex items-center justify-center text-sm text-neutral-600">{{ __('Next game in') }} {{ countdown }}</span>
              </div>
            </div>
          </div>
          <div class="ml-auto flex items-center">
            <button type="button" class="btn-secondary mr-2" @click="$emit('close')">{{ __('Close') }}</button>
          </div>
        </div>
      </template>
    </Card>
  </Modal>
</template>