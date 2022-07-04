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
      <div class="flex items-center justify-between w-full">
        <h3 class="text-xl font-bold">Playlist</h3>
        <span v-if="round" class="text-xl font-bold text-neutral-500"><span class="text-neutral-700">{{ round.current + 1 }}</span> / {{ round.tracks.length + 1 }}</span>
      </div>
    </template>

    <ul class="h-64 md:h-80 2xl:h-96 overflow-y-scroll">
      <li v-for="track in tracks" :key="track.id" class="mb-2 flex border-b">
        <div class="p-2">
          <img :src="track.artwork_url" :alt="track.album_name" class="h-20 w-auto rounded" />
        </div>
        <div class="flex-grow p-2">
          <transition-group name="flip-list" tag="ul">
            <li v-for="answer in track.answers" :key="answer.id" class="mb-1 flex items-start text-sm">
              <span class="mr-1 flex-shrink-0 rounded bg-neutral-300 px-1 text-[10px] font-bold uppercase text-neutral-500 text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
            </li>
          </transition-group>
        </div>
      </li>
    </ul>
  </Card>
</template>
