<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
})

const bookmarks = ref([...props.user.bookmarks.data])
const nextPage = ref(props.user.bookmarks.current_page + 1)
const lastPage = ref(props.user.bookmarks.last_page)
const loading = ref(false)

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value) return
  loading.value = true
  router.get(
    window.location.pathname,
    { bookmarks: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
      onSuccess: (page) => {
        const newBookmarks = page.props.user.bookmarks.data
        bookmarks.value.push(...newBookmarks)
        nextPage.value++
        loading.value = false
      },
      onError: () => { loading.value = false },
    }
  )
}
</script>
<template>
  <div class="space-y-2">
    <div class="flex items-center gap-2 mb-4 animate-bounce">
      <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
      <span class="font-bold text-yellow-300 text-lg">{{ __('Your bookmarks!') }}</span>
    </div>
    <div v-for="room in bookmarks" :key="room.id" class="flex items-center gap-4 bg-neutral-900/80 rounded-xl px-4 py-3 shadow">
      <Link class="flex items-center" :href="route('rooms.show', room.slug)">
        <img v-if="room.photo" :src="room.photo" class="h-12 w-12 rounded-full ring-2 ring-pink-300 mr-3" loading="lazy" />
        <div class="flex flex-col">
          <span class="font-bold text-pink-200">{{ room.name }}</span>
          <span class="text-xs text-neutral-400">{{ room.category?.name }}</span>
        </div>
      </Link>
      <!-- Add unbookmark button if needed -->
    </div>
    <div v-if="bookmarks.length === 0" class="text-center text-pink-700 py-8">{{ __('No bookmarks found') }}</div>
    <div v-if="loading" class="flex justify-center py-4">
      <div class="animate-pulse flex gap-4">
        <div class="h-10 w-10 bg-pink-200 rounded-full"></div>
        <div class="flex-1 space-y-2">
          <div class="h-4 bg-pink-200 rounded w-1/2"></div>
          <div class="h-4 bg-pink-200 rounded w-1/3"></div>
        </div>
      </div>
    </div>
    <div v-if="nextPage.value <= lastPage.value && !loading" class="flex justify-center mt-4">
      <button @click="loadMore" class="px-6 py-2 rounded-full bg-gradient-to-r from-yellow-400 to-pink-400 text-white font-bold shadow-lg hover:scale-105 hover:from-pink-400 hover:to-yellow-400 transition-all duration-200">
        <span class="inline-flex items-center gap-2">
          <svg class="w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13v4h3a1 1 0 110 2h-4a1 1 0 01-1-1V5a1 1 0 112 0z"/></svg>
          {{ __('Load more bookmarks!') }}
        </span>
      </button>
    </div>
  </div>
</template>