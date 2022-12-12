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
const userAnswers = ref([])
const round = ref(null)
const tracks = ref([])

onMounted(() => {
  Echo.channel(props.channel)
    .listen('RoundStarted', (e) => {
      round.value = e.round
      tracks.value = []
      userAnswers.value = []
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
    .listen('NewScore', (e) => {
      if (e.score.user_id === user.id) {
        console.log(e.score)
        userAnswers.value.push(e.score)
      }
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
                <div v-if="(userAnswer = userAnswers.filter((x) => x.track_id === track.id && x.answers.filter((a) => a.id === answer.id)[0])[0])" class="flex items-center gap-2">
                  <div class="relative flex items-center gap-1 rounded bg-purple-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white" :class="{ 'mr-1': userAnswer.answers?.filter((a) => a.id === answer.id)[0]?.order < 4 }">
                    <span v-if="userAnswer.answers?.filter((a) => a.id === answer.id)[0]?.speedBonus" class="text-white">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                        <path fill-rule="evenodd" d="M13.5 4.938a7 7 0 11-9.006 1.737c.202-.257.59-.218.793.039.278.352.594.672.943.954.332.269.786-.049.773-.476a5.977 5.977 0 01.572-2.759 6.026 6.026 0 012.486-2.665c.247-.14.55-.016.677.238A6.967 6.967 0 0013.5 4.938zM14 12a4 4 0 01-4 4c-1.913 0-3.52-1.398-3.91-3.182-.093-.429.44-.643.814-.413a4.043 4.043 0 001.601.564c.303.038.531-.24.51-.544a5.975 5.975 0 011.315-4.192.447.447 0 01.431-.16A4.001 4.001 0 0114 12z" clip-rule="evenodd" />
                      </svg>
                    </span>
                    {{ __(userAnswer.answers?.filter((a) => a.id === answer.id)[0]?.name) }}
                    <span v-if="userAnswer.answers?.filter((a) => a.id === answer.id)[0]?.order < 4" class="absolute -right-2 -top-1 ml-2 flex h-3 w-3 items-center justify-center rounded-full bg-purple-500 text-[11px] text-neutral-100">
                      {{ userAnswer.answers?.filter((a) => a.id === answer.id)[0]?.order }}
                    </span>
                  </div>
                  {{ answer.value }}
                </div>
                <div v-else class="flex items-center gap-2">
                  <span class="flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
                </div>
              </li>
            </ul>
            <div class="hidden flex-col items-end lg:flex">
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
