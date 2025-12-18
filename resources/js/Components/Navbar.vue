<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Logo from '@/Components/Logo.vue'
import Icon from '@/Components/Icon.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LevelDisplay from '@/Components/LevelDisplay.vue'
import ChristmasDecorations from '@/Components/ChristmasDecorations.vue'

const user = usePage().props.auth?.user
</script>
<template>
  <div class="hidden md:flex relative items-center justify-between py-2 px-4 sm:px-6 md:px-12 border-b border-neutral-800/50 bg-gradient-to-b from-neutral-900/95 to-neutral-900/80 backdrop-blur-md">
    <ChristmasDecorations />
    
    <!-- Desktop: Logo complet -->
    <div class="relative z-10 w-full lg:w-1/4">
      <Link :href="route('home')" title="Blinest" class="group transition-all duration-200 hover:scale-[102%]">
        <Logo class="w-24 fill-inherit lg:w-36 mt-2 transition-all duration-200 group-hover:drop-shadow-[0_0_8px_rgba(239,68,68,0.4)]" />
        <p class="mt-1 hidden text-sm text-neutral-400 lg:block tracking-widest group-hover:text-neutral-300 transition-colors duration-200">{{ __('Tune In, Test Out!') }}</p>
      </Link>
    </div>

    <!-- Desktop: Section droite -->
    <div class="relative z-10 flex w-full lg:w-3/4 items-center justify-between pl-2 pr-8 md:flex-shrink-0">
      <div class="mt-1 flex items-center gap-3">
        <SearchRooms class="transition hover:scale-[104%] focus:scale-[104%]" />
        <Link :href="route('faq')" title="FAQ" class="text-neutral-400 hover:text-blue-400 transition-all duration-200 hover:scale-110">
          <Icon name="faq" class="h-6 w-6 drop-shadow-[0_0_4px_currentColor]" />
        </Link>
      </div>

      <div class="flex items-center justify-end gap-3">
        <Link v-if="user" :href="route('rankings.index')" :title="__('Rankings')" class="group flex h-10 w-10 items-center justify-center rounded-full bg-neutral-800/50 border border-neutral-700/50 text-yellow-500 hover:text-yellow-400 hover:bg-neutral-700/50 hover:border-yellow-500/50 transition-all duration-200 hover:scale-110">
          <Icon name="trophy" class="h-5 w-5 drop-shadow-[0_0_6px_rgba(234,179,8,0.6)]" />
        </Link>
        <Notifications v-if="user" />
        <LevelDisplay
          v-if="user"
          :level="user.level || 1"
          :current-xp="user.current_xp || 0"
          :xp-for-next-level="user.xp_for_next_level || 100"
        />
        <UserDropdown v-if="user" />
        <div v-if="!user" class="flex gap-4">
          <Link :href="route('login')" :title="__('Login')" class="rounded-lg px-3 py-1.5 font-medium text-white hover:bg-slate-700 transition-colors duration-200">
            {{ __('Login') }}
          </Link>
          <Link :href="route('register')" class="hidden lg:block rounded-lg bg-red-500 px-3 py-1.5 font-medium text-white hover:bg-red-600 transition-colors duration-200 shadow-sm hover:shadow-md" :title="__('Register')">
            {{ __('Register') }}
          </Link>
        </div>
      </div>
    </div>

  </div>
</template>

