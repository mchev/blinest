<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon'

const isUrl = (...urls) => {
  let currentUrl = usePage().url.value.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith(url)).length
}
</script>
<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('users.edit', $page.props.auth.user)">
        <icon name="dashboard" class="mr-2 h-4 w-4" :class="isUrl('users') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('users') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">{{ __('My Profile') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('playlists')">
        <icon name="office" class="mr-2 h-4 w-4" :class="isUrl('playlists') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('playlists') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">{{ __('Playlists') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('rooms')">
        <icon name="users" class="mr-2 h-4 w-4" :class="isUrl('rooms') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('rooms') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">{{ __('Rooms') }}</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <icon name="printer" class="mr-2 h-4 w-4" :class="isUrl('reports') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">{{ __('Team') }}</div>
      </Link>
    </div>    
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <icon name="printer" class="mr-2 h-4 w-4" :class="isUrl('reports') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">{{ __('Rankings') }}</div>
      </Link>
    </div>
  </div>
</template>
