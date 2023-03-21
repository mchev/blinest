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
      <tbody>
        <tr v-for="bookmark in user.bookmarks.data" :key="bookmark.id">
          <td class="border-b">
            <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('rooms.show', bookmark.slug)">
              <img v-if="bookmark.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="bookmark.photo" />
              <div class="flex flex-col">
                {{ bookmark.name }}
              </div>
            </Link>
          </td>
        </tr>
        <tr v-if="user.bookmarks.total === 0">
          <td class="border-t border-neutral-500 px-6 py-4" colspan="4">{{ __('No bookmarks found.') }}</td>
        </tr>
      </tbody>
    </table>
    <Pagination :links="user.bookmarks.links" />
  </div>
</template>