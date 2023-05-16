<script setup>
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  likes: Object,
})
</script>
<template>
  <div class="overflow-x-auto">
    <ul>
      <li v-for="track in likes.data" :key="track.id" class="mb-2 flex border-b mt-2">
        <div class="p-2">
          <img :src="track.artwork_url" :alt="track.album_name" class="h-20 w-auto rounded" />
        </div>
        <div class="flex-grow p-2">
          <div class="flex h-full justify-between">
            <ul>
              <li v-for="answer in track.answers" :key="answer.id" class="mb-2 flex items-start overflow-ellipsis text-sm">
                <div class="flex items-center gap-2">
                  <span class="flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white">{{ __(answer.type.name) }}</span> {{ answer.value }}
                </div>
              </li>
            </ul>
            <div class="hidden flex-col items-end lg:flex">
              <a v-if="track.track_url" class="flex items-center whitespace-nowrap text-xs opacity-50 hover:opacity-90" :href="track.track_url" target="_blank" :title="__('Listen on') + ' ' + track.provider"> {{ __('Listen on') }} <Icon :name="track.provider" class="ml-1 h-5 w-5" /> </a>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <Pagination :links="likes.links" />
  </div>
</template>