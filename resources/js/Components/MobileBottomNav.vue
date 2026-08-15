<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LevelDisplay from '@/Components/LevelDisplay.vue'
import UserDropdown from '@/Components/UserDropdown.vue'

const user = usePage().props.auth?.user
</script>

<template>
  <nav class="surface-nav safe-area-inset-bottom fixed bottom-0 left-0 right-0 z-50 border-t md:hidden">
    <div class="flex items-center justify-evenly px-2 py-3">
      <!-- Home (gauche) -->
      <Link 
        :href="route('home')" 
        :title="__('Home')"
        class="flex min-w-0 flex-1 items-center justify-center rounded-lg px-2 py-2 transition-colors duration-200 hover:bg-surface-hover/50"
      >
        <div class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-surface-raised/60">
          <img src="/favicon.svg" alt="Blinest" class="h-6 w-6" />
        </div>
      </Link>

      <!-- Rankings (si connecté non-guest) -->
      <Link 
        v-if="user && !user.is_guest"
        :href="route('rankings.index')" 
        :title="__('Rankings')"
        class="flex min-w-0 flex-1 items-center justify-center rounded-lg px-2 py-2 transition-colors duration-200 hover:bg-surface-hover/50"
      >
        <div class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-surface-raised/60 text-yellow-500">
          <Icon name="trophy" class="h-5 w-5 drop-shadow-[0_0_6px_rgba(234,179,8,0.6)]" />
        </div>
      </Link>

      <!-- LevelDisplay (milieu - le plus important) -->
      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <LevelDisplay
          :level="user.level || 1"
          :current-xp="user.current_xp || 0"
          :xp-for-next-level="user.xp_for_next_level || 100"
        />
      </div>

      <!-- Notifications (si connecté non-guest) -->
      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <Notifications />
      </div>

      <!-- User (si connecté non-guest) -->
      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <UserDropdown />
      </div>

      <!-- Login/Register si non connecté OU guest -->
      <div v-if="!user || user.is_guest" class="flex items-center justify-center gap-2 flex-1 px-2">
        <Link 
          :href="user?.is_guest ? route('guest.to-login') : route('login')" 
          :title="__('Login')"
          class="rounded-lg px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-700 transition-colors duration-200"
        >
          {{ __('Login') }}
        </Link>
        <Link 
          :href="user?.is_guest ? route('guest.to-register') : route('register')" 
          :title="__('Register')"
          class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600 transition-colors duration-200"
        >
          {{ __('Register') }}
        </Link>
      </div>
    </div>
  </nav>
</template>

<style>
.safe-area-inset-bottom {
  padding-bottom: env(safe-area-inset-bottom);
}
</style>

