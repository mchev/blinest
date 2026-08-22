<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  paginator: {
    type: Object,
    default: null,
  },
  profile: {
    type: Object,
    required: true,
  },
})

const currentUser = usePage().props.auth.user
const likes = ref(props.paginator ? [...props.paginator.data] : [])
const nextPage = ref(props.paginator ? props.paginator.current_page + 1 : 1)
const lastPage = ref(props.paginator ? props.paginator.last_page : 1)
const loading = ref(false)

watch(
  () => props.paginator,
  (newPaginator) => {
    if (newPaginator) {
      likes.value = [...newPaginator.data]
      nextPage.value = newPaginator.current_page + 1
      lastPage.value = newPaginator.last_page
    }
  },
  { immediate: true },
)

const unlike = (track) => {
  router.delete(route('profiles.likes.delete', track.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      const index = likes.value.findIndex((like) => like.id === track.id)

      if (index > -1) {
        likes.value.splice(index, 1)
      }
    },
  })
}

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.paginator) {
    return
  }

  loading.value = true

  router.get(
    route('user.profile', props.profile.id),
    { tab: 'likes', page: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['likes', 'activeTab'],
      onSuccess: (page) => {
        const newLikes = page.props.likes?.data || []
        likes.value.push(...newLikes)
        nextPage.value = page.props.likes.current_page + 1
        lastPage.value = page.props.likes.last_page
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
    <div v-for="track in likes" :key="track.id" class="retro-list-row">
      <img v-if="track.artwork_url" :src="track.artwork_url" class="squircle-nested-xs h-12 w-12 shrink-0 object-cover" loading="lazy" />
      <div class="flex min-w-0 flex-1 flex-col">
        <span class="truncate font-medium text-white">{{ track.title || __('No title') }}</span>
        <span class="truncate text-sm text-white/60">{{ track.artist || __('No artist') }}</span>
      </div>
      <button v-if="currentUser && currentUser.id === profile.id" type="button" @click="unlike(track)" class="ml-auto shrink-0 p-1 text-white/50 transition-colors hover:text-brand-primary">
        <Icon name="delete" class="h-5 w-5" />
      </button>
    </div>

    <div v-if="likes.length === 0" class="py-12 text-center text-white/60">{{ __('No likes found') }}</div>

    <div v-if="loading" class="flex justify-center py-8">
      <svg class="h-6 w-6 animate-spin text-brand-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
      </svg>
    </div>

    <div v-if="nextPage <= lastPage && !loading && likes.length > 0" class="flex justify-center pt-4">
      <button type="button" @click="loadMore" class="retro-nav-btn--primary text-sm">
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
