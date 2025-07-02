<script setup>
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  user: Object,
})

const currentUser = usePage().props.auth.user;
const likes = ref([...props.user.likes.data])
const nextPage = ref(props.user.likes.current_page + 1)
const lastPage = ref(props.user.likes.last_page)
const loading = ref(false)

const unlike = (track) => {
  router.delete(`/profile/likes/${track.id}`)
}

const loadMore = async () => {
  if (!nextPage.value) return
  loading.value = true
  try {
    const response = await fetch(nextPage.value, { headers: { 'X-Inertia': 'true' } })
    const data = await response.json()
    likes.value.push(...data.props.user.likes.data)
    nextPage.value = data.props.user.likes.next_page_url
  } finally {
    loading.value = false
  }
}
</script>
<template>
  <div class="space-y-2">
    <div class="flex items-center gap-2 mb-4 animate-bounce">
      <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
      <span class="font-bold text-yellow-300 text-lg">{{ __('Your liked tracks!') }}</span>
    </div>
    <div v-for="track in likes" :key="track.id" class="flex items-center gap-4 bg-neutral-900/80 rounded-xl px-4 py-3 shadow border border-neutral-700">
      <img v-if="track.cover || track.artwork_url" :src="track.cover || track.artwork_url" class="h-12 w-12 rounded-full ring-2 ring-pink-300 mr-3" loading="lazy" />
      <div class="flex flex-col min-w-0 flex-1">
        <span class="font-bold text-white text-base truncate">
          {{
            track.title
            || (track.answers && track.answers.find(a => a.type && a.type.name === 'Title')?.value)
            || 'No title'
          }}
        </span>
        <span class="text-sm text-yellow-200 truncate">
          {{
            track.artist
            || (track.answers && track.answers.find(a => a.type && a.type.name === 'Artist')?.value)
            || 'No artist'
          }}
        </span>
        <span v-if="!track.title && !track.artist && !((track.answers && track.answers.find(a => a.type && a.type.name === 'Title')) || (track.answers && track.answers.find(a => a.type && a.type.name === 'Artist')))" class="text-xs text-red-400">{{ JSON.stringify(track) }}</span>
      </div>
      <button v-if="currentUser && currentUser.id === props.user.id" @click="unlike(track)" class="ml-auto text-red-400 hover:text-red-600 transition-colors">
        <Icon name="delete" class="w-6 h-6" />
      </button>
    </div>
    <div v-if="likes.length === 0" class="text-center text-pink-700 py-8">{{ __('No likes found') }}</div>
    <div v-if="loading" class="flex justify-center py-4">
      <div class="animate-pulse flex gap-4">
        <div class="h-10 w-10 bg-pink-200 rounded-full"></div>
        <div class="flex-1 space-y-2">
          <div class="h-4 bg-pink-200 rounded w-1/2"></div>
          <div class="h-4 bg-pink-200 rounded w-1/3"></div>
        </div>
      </div>
    </div>
    <div v-if="nextPage.value && !loading" class="flex justify-center mt-4">
      <button @click="loadMore" class="px-6 py-2 rounded-full bg-gradient-to-r from-yellow-400 to-pink-400 text-white font-bold shadow-lg hover:scale-105 hover:from-pink-400 hover:to-yellow-400 transition-all duration-200">
        <span class="inline-flex items-center gap-2">
          <svg class="w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13v4h3a1 1 0 110 2h-4a1 1 0 01-1-1V5a1 1 0 112 0z"/></svg>
          {{ __('Load more likes!') }}
        </span>
      </button>
    </div>
  </div>
</template>
