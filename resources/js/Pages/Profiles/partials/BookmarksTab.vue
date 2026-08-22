<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  paginator: {
    type: Object,
    default: null,
  },
  profileId: {
    type: Number,
    required: true,
  },
})

const bookmarks = ref(props.paginator ? [...props.paginator.data] : [])
const nextPage = ref(props.paginator ? props.paginator.current_page + 1 : 1)
const lastPage = ref(props.paginator ? props.paginator.last_page : 1)
const loading = ref(false)

watch(
  () => props.paginator,
  (newPaginator) => {
    if (newPaginator) {
      bookmarks.value = [...newPaginator.data]
      nextPage.value = newPaginator.current_page + 1
      lastPage.value = newPaginator.last_page
    }
  },
  { immediate: true },
)

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.paginator) {
    return
  }

  loading.value = true

  router.get(
    route('user.profile', props.profileId),
    { tab: 'bookmarks', page: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['bookmarks', 'activeTab'],
      onSuccess: (page) => {
        const newBookmarks = page.props.bookmarks?.data || []
        bookmarks.value.push(...newBookmarks)
        nextPage.value = page.props.bookmarks.current_page + 1
        lastPage.value = page.props.bookmarks.last_page
        loading.value = false
      },
      onError: () => {
        loading.value = false
      },
    },
  )
}
</script>

<template>
  <div class="space-y-3">
    <div v-for="room in bookmarks" :key="room.id" class="squircle-nested-sm flex items-center gap-4 border border-neutral-700/50 bg-neutral-800/50 px-4 py-3 transition-colors hover:bg-neutral-800/70">
      <Link class="flex min-w-0 items-center" :href="route('rooms.show', room.slug)">
        <img v-if="room.photo" :src="room.photo" class="squircle-nested-xs h-12 w-12 shrink-0 object-cover" loading="lazy" />
        <div class="ml-3 flex min-w-0 flex-col">
          <span class="truncate font-medium text-white">{{ room.name }}</span>
          <span v-if="room.category" class="text-xs text-neutral-400">{{ room.category.name }}</span>
        </div>
      </Link>
    </div>

    <div v-if="bookmarks.length === 0" class="py-12 text-center text-neutral-400">{{ __('No bookmarks found') }}</div>

    <div v-if="loading" class="flex justify-center py-8">
      <svg class="h-6 w-6 animate-spin text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
      </svg>
    </div>

    <div v-if="nextPage <= lastPage && !loading && bookmarks.length > 0" class="flex justify-center pt-4">
      <button type="button" @click="loadMore" class="squircle-nested-xs border border-neutral-600 bg-neutral-700 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-600">
        <span class="inline-flex items-center gap-2">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
          {{ __('Load more') }}
        </span>
      </button>
    </div>
  </div>
</template>
