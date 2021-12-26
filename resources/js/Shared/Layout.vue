<template>
  <div class="text-gray-700 dark:text-gray-100">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between px-6 py-3 bg-white dark:bg-gray-900 md:flex-shrink-0 md:justify-center md:w-56">
            <Link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="w-6 h-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md grid grid-cols-2 items center p-4 w-full text-sm bg-white dark:bg-gray-900 dark:border-gray-600 md:px-12 md:py-0">
            <div class="flex items-center justify-end">
              <search-rooms/>
            </div>
            <div class="flex items-center justify-end">
              <user-dropdown/>
              <language-switcher/>
              <darkmode-switcher/>
            </div>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <main-menu class="hidden flex-shrink-0 p-12 w-56 bg-indigo-800 overflow-y-auto md:block" />
          <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto dark:bg-gray-600 dark:text-gray-100" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Logo from '@/Shared/Logo'
import Dropdown from '@/Shared/Dropdown'
import MainMenu from '@/Shared/MainMenu'
import FlashMessages from '@/Shared/FlashMessages'
import SearchRooms from '@/Shared/SearchRooms'
import UserDropdown from '@/Shared/UserDropdown'
import LanguageSwitcher from '@/Shared/LanguageSwitcher'
import DarkmodeSwitcher from '@/Shared/DarkmodeSwitcher'

export default {
  components: {
    Dropdown,
    FlashMessages,
    SearchRooms,
    UserDropdown,
    LanguageSwitcher,
    DarkmodeSwitcher,
    Icon,
    Link,
    Logo,
    MainMenu,
  },
  props: {
    auth: Object,
  },
}
</script>
