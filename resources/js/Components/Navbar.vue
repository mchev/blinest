<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Logo from '@/Components/Logo.vue'
import Icon from '@/Components/Icon.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LevelDisplay from '@/Components/LevelDisplay.vue'
import Dropdown from '@/Components/Dropdown.vue'

const user = usePage().props.auth?.user
</script>
<template>
  <div class="hidden md:flex relative items-center justify-between py-2 px-4 sm:px-6 md:px-12 border-b border-neutral-800/50 bg-gradient-to-b from-neutral-900/95 to-neutral-900/80 backdrop-blur-md">
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
        <Dropdown placement="bottom-end">
          <template #default>
            <div
              :title="__('Help & Documentation')"
              class="text-neutral-400 hover:text-blue-400 transition-all duration-200 hover:scale-110 cursor-pointer"
            >
              <Icon name="faq" class="h-6 w-6 drop-shadow-[0_0_4px_currentColor]" />
            </div>
          </template>
          <template #dropdown>
            <div class="min-w-[200px] py-1">
              <Link
                :href="route('docs.index')"
                class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors"
              >
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                  </svg>
                  <span>{{ __('Documentation') }}</span>
                </div>
              </Link>
              <Link
                :href="route('docs.faq')"
                class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors"
              >
                <div class="flex items-center gap-2">
                  <Icon name="faq" class="h-4 w-4" />
                  <span>{{ __('FAQ') }}</span>
                </div>
              </Link>
              <Link
                :href="route('docs.elo')"
                class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors"
              >
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236v4.069m0 0a8.97 8.97 0 01-2.916.52m2.916-.52v4.069m0 0v2.613m0 0c-1.027-.083-2.05-.213-3.062-.38m3.062.38v-2.613m0 0h2.613m-2.613 0a8.97 8.97 0 01-2.916-.52m2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v4.069m0 0h2.613m-2.613 0a8.97 8.97 0 00-2.916-.52m2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-4.069m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916-.52v4.069m0 0h2.613m-2.613 0a8.97 8.97 0 00-2.916-.52m2.916-.52v-4.069m0 0h-2.613" />
                  </svg>
                  <span>{{ __('ELO System') }}</span>
                </div>
              </Link>
              <Link
                :href="route('docs.level')"
                class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors"
              >
                <div class="flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                  </svg>
                  <span>{{ __('Levels System') }}</span>
                </div>
              </Link>
            </div>
          </template>
        </Dropdown>
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

