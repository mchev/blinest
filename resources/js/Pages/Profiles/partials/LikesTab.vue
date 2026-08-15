<script setup>
import { ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  user: Object,
  isExpanded: {
    type: Boolean,
    default: false,
  },
})

const currentUser = usePage().props.auth.user
const likes = ref(props.user.likes ? [...props.user.likes.data] : [])
const nextPage = ref(props.user.likes ? props.user.likes.current_page + 1 : 1)
const lastPage = ref(props.user.likes ? props.user.likes.last_page : 1)
const loading = ref(false)

watch(() => props.user.likes, (newLikes) => {
  if (newLikes) {
    likes.value = [...newLikes.data]
    nextPage.value = newLikes.current_page + 1
    lastPage.value = newLikes.last_page
  }
}, { immediate: true })

const unlike = (track) => {
  router.delete(route('profiles.likes.delete', track.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      const index = likes.value.findIndex(l => l.id === track.id)
      if (index > -1) {
        likes.value.splice(index, 1)
      }
    },
  })
}

const loadMore = () => {
  if (loading.value || nextPage.value > lastPage.value || !props.user.likes) return
  loading.value = true
  router.get(
    window.location.pathname,
    { likes: nextPage.value },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
      onSuccess: (page) => {
        const newLikes = page.props.user.likes?.data || []
        likes.value.push(...newLikes)
        nextPage.value++
        loading.value = false
      },
      onError: () => {
        loading.value = false
      },
    }
  )
}

const expandSection = () => {
  router.get(
    window.location.pathname,
    { likes: 1, tab: 'likes' },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['user'],
    }
  )
}
</script>
<template>
  <div class="space-y-3">
    <div v-for="track in likes" :key="track.id" class="retro-list-row">
      <img v-if="track.cover || track.artwork_url" :src="track.cover || track.artwork_url" class="h-12 w-12 squircle-nested-xs object-cover" loading="lazy" />
      <div class="flex min-w-0 flex-1 flex-col">
        <span class="truncate font-medium text-white">
          {{
            track.title
            || (track.answers && track.answers.find(a => a.type && a.type.name === 'Title')?.value)
            || 'No title'
          }}
        </span>
        <span class="truncate text-sm text-white/60">
          {{
            track.artist
            || (track.answers && track.answers.find(a => a.type && a.type.name === 'Artist')?.value)
            || 'No artist'
          }}
        </span>
      </div>
      <button v-if="currentUser && currentUser.id === props.user.id" @click="unlike(track)" class="ml-auto p-1 text-white/50 transition-colors hover:text-brand-primary">
        <Icon name="delete" class="h-5 w-5" />
      </button>
    </div>
    <div v-if="likes.length === 0" class="py-12 text-center text-white/60">{{ __('No likes found') }}</div>
    <div v-if="loading" class="flex justify-center py-8">
      <svg class="h-6 w-6 animate-spin text-brand-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
      </svg>
    </div>
    <div v-if="!isExpanded && likes.length > 0 && likes.length < (user.likes?.total || 0)" class="flex justify-center pt-4">
      <button @click="expandSection" class="retro-nav-btn--primary text-sm">
        {{ __('View all') }} ({{ user.likes?.total || 0 }})
      </button>
    </div>
    <div v-else-if="isExpanded && nextPage <= lastPage && !loading && likes.length > 0" class="flex justify-center pt-4">
      <button @click="loadMore" class="retro-nav-btn--primary text-sm">
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
