<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const isUrl = (...urls) => {
  let currentUrl = usePage().url.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith('admin/' + url)).length
}
</script>
<template>
  <div class="text-neutral-100">
    <div class="mb-6">
      <Link class="group" :class="isUrl('dashboard') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.dashboard')">
        <div :class="isUrl('dashboard') ? 'font-bold' : 'font-normal'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('pages') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.pages.index')">
        <div :class="isUrl('pages') ? 'font-bold' : 'font-normal'">Pages</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('faqs') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.faqs.index')">
        <div :class="isUrl('faqs') ? 'font-bold' : 'font-normal'">FAQ</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('users') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.users')">
        <div :class="isUrl('users') ? 'font-bold' : 'font-normal'">Users</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('teams') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.teams')">
        <div :class="isUrl('teams') ? 'font-bold' : 'font-normal'">Teams</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('playlists') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.playlists')">
        <div :class="isUrl('playlists') ? 'font-bold' : 'font-normal'">{{ __('Playlists') }}</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('categories') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.categories.index')">
        <div :class="isUrl('categories') ? 'font-bold' : 'font-normal'">Categories</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('rooms') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.rooms')">
        <div :class="isUrl('rooms') ? 'font-bold' : 'font-normal'">Rooms</div>
      </Link>
    </div>
    <div class="mb-6">
      <Link class="group" :class="isUrl('answer_types') ? 'btn-primary' : 'btn-secondary'" :href="route('admin.answer_types.index')">
        <div :class="isUrl('answer_types') ? 'font-bold' : 'font-normal'">Answer Types</div>
      </Link>
    </div>
  </div>
</template>
