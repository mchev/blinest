<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Card from '@/Components/Card.vue'

const props = defineProps({
  users: Array,
  channel: String,
  data: Object,
})

const me = usePage().props.value.auth.user
const scores = ref([])
const userList = ref(props?.users)
const track = ref(null)

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
  <Card>
    <template #header>
      <h3 class="text-xl font-bold">Classement</h3>
    </template>

    <div class="h-64 md:h-80 2xl:h-96 overflow-y-scroll">
      <transition-group name="flip-list" tag="ul">
        <li v-for="user in userList" :key="user.id" class="flex justify-between border-b px-2 py-4" :class="{ 'bg-neutral-200': me.id === user.id }">
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
</template>
