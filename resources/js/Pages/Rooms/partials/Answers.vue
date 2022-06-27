<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  users: Array,
  channel: String,
})

const userList = ref(props.users)
const round = ref(null)
const tracks = ref([])

watch(
  () => props.users,
  (value) => {
    userList.value = value
  },
)

onMounted(() => {
  Echo.channel(props.channel).listen('RoundStarted', (e) => {
    round.value = e.round
    tracks.value = []
  })

  Echo.channel(props.channel).listen('TrackEnded', (e) => {
    console.log(e)
    tracks.value.unshift(e.track)
    round.value = e.round
  })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})
</script>
<template>
  <Card>
    <template #header>
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold">Playlist</h3>
        <span v-if="round" class="text-neutral-500 font-bold text-xl"><span class="text-neutral-700">{{ round.current + 1 }}</span> / {{ round.tracks.length + 1 }}</span>
      </div>
    </template>

    <ul class="h-96 overflow-y-scroll">
      <li v-for="track in tracks" :key="track.id" class="flex mb-2 border-b">
        <div class="p-2">
          <img :src="track.artwork_url" :alt="track.album_name" class="rounded h-20 w-auto">
        </div>
        <div class="p-2 flex-grow">
          <transition-group name="flip-list" tag="ul">
            <li v-for="answer in track.answers" :key="answer.id" class="text-sm mb-1 flex items-start">
              <span class="flex-shrink-0 mr-1 rounded text-neutral-500 bg-neutral-300 px-1 font-bold text-[10px] uppercase text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
            </li>
          </transition-group>
        </div>
      </li>
    </ul>
  </Card>
</template>
