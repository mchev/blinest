<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon.vue'
import Dropdown from '@/Components/Dropdown.vue'

const user = usePage().props.value.auth.user

const isUrl = (...urls) => {
  let currentUrl = usePage().url.value.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith(url)).length
}
</script>
<template>
  <dropdown placement="bottom-end">
    <template #default>
      <div class="group flex cursor-pointer select-none items-center">
        <div class="mr-1 whitespace-nowrap">
          <img :src="user.photo" class="w-10 h-10 rounded-full" :alt="user.name">
        </div>
      </div>
    </template>
    <template #dropdown>
      <ul>
        <li>
          <Link :href="route('me')" class="m-4 flex pl-2" :class="isUrl('me') ? 'font-bold' : 'font-normal'">
            {{ __('My profile') }}
          </Link>
        </li>
        <li>
          <Link :href="route('playlists')" class="m-4 flex pl-2" :class="isUrl('playlists') ? 'font-bold' : 'font-normal'">
            {{ __('Playlists') }}
          </Link>
        </li>
        <li>
          <Link :href="route('rooms.index')" class="m-4 flex pl-2" :class="isUrl('rooms') ? 'font-bold' : 'font-normal'">
            {{ __('Rooms') }}
          </Link>
        </li>
        <li>
          <Link :href="route('teams.index')" class="m-4 flex pl-2" :class="isUrl('teams') ? 'font-bold' : 'font-normal'">
            {{ __('Teams') }}
          </Link>
        </li>
        <li>
          <Link v-if="user.admin" :href="route('admin.dashboard')" class="m-4 flex pl-2" :class="isUrl('teams') ? 'font-bold' : 'font-normal'">
            {{ __('Administration') }}
          </Link>
        </li>
        <li>
          <Link href="/logout" method="post" as="button" class="m-4 flex pl-2 text-red-500">
            {{ __('Logout') }}
          </Link>
        </li>
      </ul>
    </template>
  </dropdown>
</template>
