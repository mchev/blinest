<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'
import PodiumModal from './PodiumModal.vue'

const props = defineProps({
  room: Object,
  users: Array,
  channel: String,
  data: Object,
})

const me = usePage().props.value.auth.user
const scores = ref([])
const userList = ref(props?.users)
const track = ref(null)
const showPodiumModal = ref(false)

watch(
  () => props.users,
  (value) => {
    userList.value = value
  },
)

onMounted(() => {
  Echo.channel(props.channel)
    .listen('NewScore', (e) => {
      scores.value.push(e.score)
      let index = userList.value.findIndex((x) => x.id === e.score.user_id)
      userList.value[index].score.total = e.score.total
      userList.value[index].score.points = e.score.points
      userList.value[index].score.answers.push(...e.score.answers)
      userList.value.sort((a, b) => b.score.total - a.score.total)
    })
    .listen('TrackPlayed', (e) => {
      track.value = e.track
      userList.value.map((x) => {
        if (x.score) return (x.score.answers = [])
      })
    })
    .listen('RoundStarted', () => {
      userList.value.forEach((x) => {
        x.score.total = 0
      })
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})
</script>
<template>
  <div>
    <Card>
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h3 class="text-xl font-bold">Classement</h3>
          <button type="button" @click="showPodiumModal = true" :title="__('Show rankings for this room')">
            <Icon name="podium" class="mr-2 h-8 w-8" />
          </button>
        </div>
      </template>

      <div class="h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96">
        <transition-group name="flip-list" tag="ul">
          <li v-for="user in userList" :key="user.id" class="flex justify-between rounded border-b border-neutral-600 px-2 py-4" :class="{ 'bg-neutral-700': me.id === user.id }">
            <div>
              {{ user.name }}
              <div class="flex items-center">
                <span v-for="userAnswer in user.score.answers" v-if="user.score" class="relative mr-3 flex items-center rounded bg-purple-300 px-1 text-[10px] font-bold uppercase text-neutral-500 text-white">
                  <span v-if="userAnswer.speedBonus" class="mr-1 text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                      <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                    </svg>
                  </span>
                  {{ __(userAnswer.name) }}
                  <span v-if="userAnswer.order < 4" class="absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-teal-600 text-[8px] text-neutral-100">
                    {{ userAnswer.order }}
                  </span>
                </span>
                <span v-for="answer in track.answers" v-if="track">
                  <span v-if="!user?.score?.answers.find((x) => x.id === answer.id)" class="relative mr-3 flex rounded bg-neutral-300 px-1 text-[10px] font-bold uppercase text-neutral-500 text-white">
                    {{ __(answer.name) }}
                  </span>
                </span>
              </div>
            </div>
            <div>{{ user?.score ? user.score.total : 0 }} <sup>PTS</sup></div>
          </li>
        </transition-group>
      </div>
    </Card>

    <PodiumModal v-if="showPodiumModal" :room="room" :show="showPodiumModal" @close="showPodiumModal = false" />
  </div>
</template>
