<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon'

const isUrl = (...urls) => {
  let currentUrl = usePage().url.value.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith('admin/' + url)).length
}
</script>
<template>
  <div class="text-neutral-100">
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.dashboard')">
        <div :class="isUrl('dashboard') ? 'font-bold' : 'font-normal'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.users')">
        <div :class="isUrl('users') ? 'font-bold' : 'font-normal'">Users</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.teams')">
        <div :class="isUrl('teams') ? 'font-bold' : 'font-normal'">Teams</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.playlists')">
        <div :class="isUrl('playlists') ? 'font-bold' : 'font-normal'">Playlists</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.categories.index')">
        <div :class="isUrl('categories') ? 'font-bold' : 'font-normal'">Categories</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.rooms')">
        <div :class="isUrl('rooms') ? 'font-bold' : 'font-normal'">Rooms</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group flex items-center" :href="route('admin.answer_types.index')">
        <div :class="isUrl('answer_types') ? 'font-bold' : 'font-normal'">Answer Types</div>
      </Link>
    </div>
  </div>
</template>
