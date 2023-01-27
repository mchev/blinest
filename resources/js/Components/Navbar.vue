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
      <Link class="" :href="route('home')" title="Blinest">
        <logo class="w-24 fill-inherit lg:w-32" />
      </Link>

      <SearchRooms class="mt-1 hidden md:block" />

      <div class="flex items-center justify-end gap-4">
        <Link v-if="user" :href="route('rankings.index')" :title="__('Rankings')"><Icon name="podium" class="h-5 w-5" /></Link>
        <a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" :title="__('Join the Blinest community on Discord')" class="umami--click--discord-button">
          <SocialIcon name="discord" class="h-5 w-5" />
        </a>
        <Notifications v-if="user" />
        <LanguageSwitcher />
        <Link :href="route('faq')" title="FAQ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
          </svg>
        </Link>
        <UserDropdown v-if="user" />
        <div v-if="!user" class="flex gap-4 uppercase">
          <Link :href="route('login')" :title="__('Login')">{{ __('Login') }}</Link>
          <Link :href="route('register')" class="hidden lg:block" :title="__('Register')">{{ __('Register') }}</Link>
        </div>
      </div>
    </div>
  </div>
</template>
