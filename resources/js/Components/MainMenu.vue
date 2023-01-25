<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const isUrl = (...urls) => {
  let currentUrl = usePage().url.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith(url)).length
}
</script>
<template>
  <div class="flex flex-col items-end gap-6 md:flex-row">
    <div class="">
      <Link class="group flex items-center" :href="route('rooms.index')">
        <div :class="isUrl('rooms') ? 'font-bold' : 'font-normal'">{{ __('Rooms') }}</div>
      </Link>
    </div>
    <div class="">
      <Link class="group flex items-center" :href="route('playlists')">
        <div :class="isUrl('playlists') ? 'font-bold' : 'font-normal'">{{ __('Playlists') }}</div>
      </Link>
    </div>
    <div class="">
      <Link class="group flex items-center" :href="route('teams.index')">
        <div :class="isUrl('teams') ? 'font-bold' : 'font-normal'">{{ __('Team') }}</div>
      </Link>
    </div>
    <div class="">
      <Link class="group flex items-center" :href="route('rankings.index')">
        <div :class="isUrl('rankings') ? 'font-bold' : 'font-normal'">{{ __('Rankings') }}</div>
      </Link>
    </div>
  </div>
</template>
