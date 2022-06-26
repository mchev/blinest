<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo'
import Dropdown from '@/Components/Dropdown'
import MainMenu from '@/Components/MainMenu'
import FlashMessages from '@/Components/FlashMessages'
import SearchRooms from '@/Components/SearchRooms'
import UserDropdown from '@/Components/UserDropdown'
import LanguageSwitcher from '@/Components/LanguageSwitcher'
import Volume from '@/Components/Volume'

defineProps({
  auth: Object,
})
</script>
<template>
  <div class="text-gray-700">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <div class="md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between px-12 py-3 md:w-56 md:flex-shrink-0">
            <Link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 rounded bg-blinest-800 px-8 py-4 shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md items center grid w-full grid-cols-2 p-4 text-sm md:px-12 md:py-0">
            <div class="flex items-center justify-end">
              <search-rooms />
            </div>
            <div class="flex items-center justify-end">
              <UserDropdown class="mr-4" />
              <LanguageSwitcher class="mr-4" />
              <Volume/>
            </div>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <main-menu class="hidden w-auto flex-shrink-0 overflow-y-auto p-12 md:w-56 md:block" />
          <div class="px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
