<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'

const user = usePage().props.auth.user

const isUrl = (...urls) => {
  let currentUrl = usePage().url.substr(1)
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
          <img :src="user.photo" class="h-10 w-10 rounded-full border-2 border-white/20 group-hover:border-brand-accent transition-colors" :alt="user.name" />
        </div>
      </div>
    </template>
    <template #dropdown>
      <ul class="py-1">
        <li>
          <Link :href="route('me')" class="retro-menu-link" :class="isUrl('me') ? 'font-bold text-brand-secondary' : ''">
            {{ __('My account') }}
          </Link>
        </li>
        <li>
          <Link :href="route('teams.index')" class="retro-menu-link" :class="isUrl('teams') ? 'font-bold text-brand-secondary' : ''">
            {{ __('Teams') }}
          </Link>
        </li>
        <li>
          <Link :href="route('rooms.index')" class="retro-menu-link" :class="isUrl('rooms') ? 'font-bold text-brand-secondary' : ''">
            {{ __('Rooms') }}
          </Link>
        </li>
        <li>
          <Link :href="route('playlists')" class="retro-menu-link" :class="isUrl('playlists') ? 'font-bold text-brand-secondary' : ''">
            {{ __('Playlists') }}
          </Link>
        </li>
        <li v-if="user.admin">
          <Link :href="route('admin.dashboard')" class="retro-menu-link" :class="isUrl('admin') ? 'font-bold text-brand-secondary' : ''">
            {{ __('Administration') }}
          </Link>
        </li>
        <li v-if="user.is_public_moderator">
          <Link :href="route('moderation.dashboard')" class="retro-menu-link" :class="isUrl('moderation') ? 'font-bold text-brand-secondary' : ''">
            {{ __('Modération') }}
          </Link>
        </li>
        <li class="border-t border-white/10 my-1">
          <div class="flex items-center justify-between px-4 py-3">
            <span class="text-sm text-white/70">{{ __('Language') }}</span>
            <LanguageSwitcher :standalone="false" />
          </div>
        </li>
        <li>
          <Link href="/logout" method="post" as="button" class="retro-menu-link text-brand-primary">
            {{ __('Logout') }}
          </Link>
        </li>
      </ul>
    </template>
  </dropdown>
</template>
