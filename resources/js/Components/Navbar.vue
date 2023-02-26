<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Logo from '@/Components/Logo.vue'
import Icon from '@/Components/Icon.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import SocialIcon from '@/Components/SocialIcon.vue'

const user = usePage().props.auth?.user

const showingSearchbar = ref(false)
</script>
<template>
  <div class="md:flex md:flex-shrink-0">
    <div class="flex w-full items-center justify-between px-4 py-2 md:flex-shrink-0 md:px-12">
      <Link :href="route('home')" title="Blinest">
        <h1 class="hidden">Blinest</h1>
        <logo class="w-24 fill-inherit lg:w-32" />
      </Link>

      <div class="mt-1 items-center gap-2 hidden md:flex">
        <SearchRooms class="hover:scale-[104%] focus:scale-[104%] transition" />
        <Link :href="route('faq')" title="FAQ" class="text-neutral-500 hover:text-inherit transition">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
          </svg>
        </Link>
      </div>

      <div class="flex items-center justify-end gap-4">
        <a href="https://shop.blinest.com" :title="__('Blinest Shop')" class="text-green-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
          </svg>
        </a>
        <Link v-if="user" :href="route('rankings.index')" :title="__('Rankings')"><Icon name="podium" class="h-5 w-5" /></Link>
        <a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" rel="external nofollow" :title="__('Join the Blinest community on Discord')" class="umami--click--discord-button">
          <SocialIcon name="discord" class="h-5 w-5" />
        </a>
        <Notifications v-if="user" />
        <LanguageSwitcher />
        <UserDropdown v-if="user" />
        <div v-if="!user" class="flex gap-4 uppercase">
          <Link :href="route('login')" :title="__('Login')">{{ __('Login') }}</Link>
          <Link :href="route('register')" class="hidden lg:block" :title="__('Register')">{{ __('Register') }}</Link>
        </div>
      </div>
    </div>
  </div>
</template>
