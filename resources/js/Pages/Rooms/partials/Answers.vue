<script setup>
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import { ref, watch, onMounted, onUnmounted } from 'vue'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  users: Array,
  channel: String,
})

const user = usePage().props.value.auth.user
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
  Echo.channel(props.channel)
    .listen('RoundStarted', (e) => {
      round.value = e.round
      tracks.value = []
    })
    .listen('TrackPlayed', (e) => {
      round.value = e.round
    })
    .listen('TrackEnded', (e) => {
      tracks.value.unshift(e.track)
      round.value = e.round
    })
    .listen('TrackVoted', (e) => {
      let index = tracks.value.findIndex((x) => x.id === e.track.id)
      tracks.value[index].downvotes = e.track.downvotes
      tracks.value[index].upvotes = e.track.upvotes
    })
})

onUnmounted(() => {
  Echo.leave(props.channel)
})

const voteTrackDown = (track) => {
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/downvote`)
}

const voteTrackUp = (track) => {
  axios.post(`/rooms/${round.value.room.id}/tracks/${track.id}/upvote`)
}
</script>
<template>
  <Card>
    <template #header>
      <div class="flex w-full items-center justify-between">
        <h3 class="text-xl font-bold">Playlist</h3>
        <span v-if="round" class="text-xl font-bold text-neutral-500"
          ><span class="text-neutral-700">{{ round.current }}</span> / {{ round.tracks.length }}</span
        >
      </div>
    </template>

    <transition-group name="flip-list" tag="ul" class="h-64 overflow-y-scroll pr-2 md:h-80 2xl:h-96">
      <li v-for="track in tracks" :key="track.id" class="mb-2 flex rounded bg-neutral-900 opacity-70">
        <div class="p-2">
          <img :src="track.artwork_url" :alt="track.album_name" class="h-20 w-auto rounded" />
        </div>
        <div class="flex-grow p-2">
          <div class="flex h-full justify-between">
            <ul>
              <li v-for="answer in track.answers" :key="answer.id" class="mb-2 flex items-start overflow-ellipsis text-sm">
                <span class="mr-1 flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
              </li>
            </ul>
            <div class="flex-col items-end hidden lg:flex">
              <a v-if="track.track_url" class="flex items-center whitespace-nowrap text-xs opacity-50 hover:opacity-90" :href="track.track_url" target="_blank" :title="__('Listen on') + ' ' + track.provider"> {{ __('Listen on') }} <Icon :name="track.provider" class="ml-1 h-5 w-5" /> </a>
              <div class="mt-4 flex items-center text-xs" v-if="user">
                <button @click="voteTrackUp(track)" class="mr-4 flex items-center" :title="__('Like')"><Icon name="thumb-up" class="mr-1 h-5 w-5" /> {{ track.upvotes }}</button>
                <button @click="voteTrackDown(track)" class="flex items-center" :title="__('Don\'t like')"><Icon name="thumb-down" class="mr-1 h-5 w-5" /> {{ track.downvotes }}</button>
              </div>
            </div>
          </div>
        </div>
      </li>
    </transition-group>
  </Card>
</template>
