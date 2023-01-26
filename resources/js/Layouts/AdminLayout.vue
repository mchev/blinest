<script setup>
import { Link } from '@inertiajs/vue3'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import AdminMenu from '@/Components/AdminMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'

defineProps({
  auth: Object,
})
</script>
<template>
  <div class="text-neutral-700">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <div class="text-white md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between px-6 py-4 md:w-56 md:flex-shrink-0 md:justify-center">
            <Link class="mt-1" href="/">
              <logo class="fill-white" width="120" height="28" />
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 rounded px-4 py-4 shadow-lg">
                  <admin-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md items center grid w-full grid-cols-2 p-4 text-sm md:px-12 md:py-0">
            <div class="text-xl flex items-center justify-end uppercase text-yellow-500 gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
              {{ __('Administration') }}
            </div>
            <div class="flex items-center justify-end">
              <UserDropdown class="mr-4" />
              <LanguageSwitcher />
            </div>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <admin-menu class="hidden w-56 flex-shrink-0 overflow-y-auto py-12 px-4 md:block" />
          <div class="px-4 py-8 text-neutral-100 md:flex-1 md:overflow-y-auto md:p-12" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
