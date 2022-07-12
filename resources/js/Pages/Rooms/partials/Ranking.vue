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
    })
    .listen('TrackPlayed', (e) => {
      track.value = e.track
      userList.value.find((x) => x.id === me.id).score.answers = []
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

      <div class="h-64 overflow-y-scroll md:h-80 2xl:h-96 pr-2">
        <transition-group name="flip-list" tag="ul">
          <li v-for="user in userList" :key="user.id" class="flex justify-between border-b border-neutral-600 px-2 py-4 rounded" :class="{ 'bg-neutral-700': me.id === user.id }">
            <div>
              {{ user.name }}
              <div class="flex items-center">
                <span v-for="answer in track.answers" v-if="track" class="mr-1 rounded px-1 text-[10px] font-bold uppercase text-neutral-500 text-white" :class="user?.score?.answers.includes(answer.id) ? 'bg-purple-300' : 'bg-neutral-300'">
                  {{ __(answer.name) }}
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
