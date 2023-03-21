<script setup>
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  user: Object,
})
</script>
<template>
  <div class="overflow-x-auto">
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="font-bold">
          <th class="px-6 pb-4 pt-6 text-left">{{ __('Room') }}</th>
          <th class="px-6 pb-4 pt-6">{{ __('Last round played') }}</th>
          <th class="px-6 pb-4 pt-6">{{ __('Score') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="score in user.scores.data" :key="score.id">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('rooms.show', score.room.slug)">
              <img v-if="score.room.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="score.room.photo" />
              <div class="flex flex-col">
                {{ score.room.name }}
              </div>
            </Link>
          </td>
          <td class="border-t">
              {{ score.updated_at }}
          </td>
          <td class="border-t">
              {{ score.score }} <sup>{{ __('PTS') }}</sup>
          </td>
        </tr>
        <tr v-if="user.scores.total === 0">
          <td class="border-t border-neutral-500 px-6 py-4" colspan="4">{{ __('No scores found.') }}</td>
        </tr>
      </tbody>
    </table>
    <Pagination :links="user.scores.links" />
  </div>
</template>