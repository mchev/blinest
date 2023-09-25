<script setup>
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import Icon from '@/Components/Icon.vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
})

const currentUser = usePage().props.auth.user;

const unlike = (track) => {
  router.delete(`/profile/likes/${track.id}`)
}
</script>
<template>
  <div class="overflow-x-auto">
    <ul>
      <li v-for="track in user.likes.data" :key="track.id" class="mb-2 mt-2 flex border-b">
        <div class="p-2">
          <img :src="track.artwork_url" :alt="track.album_name" class="h-12 w-auto rounded lg:h-20" />
        </div>
        <div class="flex-grow p-2">
          <div class="flex h-full justify-between">
            <ul>
              <li v-for="answer in track.answers" :key="answer.id" class="mb-2 flex items-start overflow-ellipsis text-sm">
                <div class="flex items-start gap-2">
                  <span class="flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
                </div>
              </li>
            </ul>
            <div class="flex-col items-end lg:flex">
              <a v-if="track.track_url" class="mb-2 hidden items-center whitespace-nowrap text-xs opacity-50 hover:opacity-90 lg:flex" :href="track.track_url" target="_blank" :title="__('Listen on') + ' ' + track.provider"> {{ __('Listen on') }} <Icon :name="track.provider" class="ml-1 h-5 w-5" /> </a>
              <button v-if="user.id === currentUser.id" class="text-red-500" type="button" :title="__('Unlike')" @click="unlike(track)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                  <line x1="2" y1="2" x2="22" y2="22" />
                  <path d="M16.5 16.5 12 21l-7-7c-1.5-1.45-3-3.2-3-5.5a5.5 5.5 0 0 1 2.14-4.35" />
                  <path d="M8.76 3.1c1.15.22 2.13.78 3.24 1.9 1.5-1.5 2.74-2 4.5-2A5.5 5.5 0 0 1 22 8.5c0 2.12-1.3 3.78-2.67 5.17" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <Pagination :links="user.likes.links" />
  </div>
</template>
