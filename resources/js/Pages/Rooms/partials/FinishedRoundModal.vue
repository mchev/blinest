<script setup>
import { ref, watch, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  round: Object,
  podium: Array,
  show: Boolean,
})

const emit = defineEmits(['close'])

const countdown = ref(parseInt(props.round.room.pause_between_rounds))
const results = ref(null)

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
  () => props.podium,
  (value) => {
    if (value) {
      results.value = value
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
        <div class="">
          <h3 class="py-2 text-xl font-bold text-center mb-2">{{ __('Teams') }}</h3>

          <div class="flex border-b border-neutral-600 justify-center gap-1">
            <div class="flex flex-col justify-end">
              <div class="bg-purple-500 rounded-t h-10 p-4 opacity-25">
                <span class="text-xl font-bold">5</span>
              </div>
            </div>
            <div class="flex flex-col justify-end items-center">
              <img class="rounded-full h-10 w-10 mb-2">
              <div class="bg-purple-500 rounded-t h-32 p-4 opacity-60">
                <span class="text-xl font-bold">3</span>
              </div>
            </div>
            <div class="flex flex-col">
              <div class="bg-purple-500 rounded-t flex justify-end h-40 p-4">
                <span>1</span>
              </div>
            </div>
            <div class="flex flex-col">
              <div class="bg-purple-500 rounded-t flex justify-end h-40 p-4">
                <span>1</span>
              </div>
            </div>
            <div class="flex flex-col">
              <div class="bg-purple-500 rounded-t flex justify-end h-40 p-4">
                <span>1</span>
              </div>
            </div>
          </div>


          <ul class="max-h-48 overflow-auto">
            <li v-for="(user, index) in results" class="flex items-center gap-2 border broder-neutral-500 rounded m-1 p-2">
              <span class="text-xl font-bold">{{ index + 1 }}</span>
              <span class="flex-grow">{{ user.name }}</span>
              <span>{{ user.total }}<sup class="ml-1">PTS</sup></span>
            </li>
          </ul>
        </div>
        <div class="">
          <h3 class="py-2 text-xl font-bold text-center mb-2">{{ __('Ranking') }}</h3>
          <ul class="max-h-48 overflow-auto">
            <li v-for="(user, index) in results" class="flex items-center gap-2 border broder-neutral-500 rounded m-1 p-2">
              <span class="text-xl font-bold">{{ index + 1 }}</span>
              <span class="flex-grow">{{ user.name }}</span>
              <span>{{ user.total }}<sup class="ml-1">PTS</sup></span>
            </li>
          </ul>
        </div>
      </div>
      <template #footer>
        <div class="flex w-full items-center gap-6">
          <div class="flex flex-grow flex-col">
            <div class="relative flex h-6 w-full items-center rounded-lg bg-purple-200 overflow-hidden">
              <div class="h-6 rounded-lg bg-gradient-to-br from-purple-300 to-purple-400 transition-all duration-1000 ease-linear flex items-center justify-center text-neutral-700" :style="'width:' + countdown / parseInt(props.round.room.pause_between_rounds) * 100 + '%'"/>
              <span class="text-sm absolute left-0 right-0 text-neutral-600 top-0 bottom-0 flex justify-center items-center">Prochaine partie dans {{ countdown }}</span>
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
