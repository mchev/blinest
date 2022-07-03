<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import Volume from '@/Components/Volume.vue'

</script>
<template>
  <div class="text-neutral-200">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <div class="md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between px-12 py-2 md:flex-shrink-0">
            <Link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 rounded px-8 py-4 shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md items-center grid w-full grid-cols-2 p-4 text-sm md:px-12 md:py-0">

            <main-menu class="hidden w-auto md:flex" />

<!--             <div class="flex items-center justify-end">
              <search-rooms />
            </div>
 -->            <div class="flex items-center justify-end">
              <UserDropdown class="mr-4" />
              <LanguageSwitcher class="mr-4" />
              <Volume />
            </div>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="md:flex-1">
              <flash-messages />
              <slot />
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>