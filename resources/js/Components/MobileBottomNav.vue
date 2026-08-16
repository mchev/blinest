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
      <Link
        :href="route('home')"
        :title="__('Home')"
        class="flex min-w-0 flex-1 items-center justify-center px-2 py-2"
      >
        <div class="retro-icon-btn">
          <img src="/favicon.svg" alt="Blinest" class="h-6 w-6" />
        </div>
      </Link>

      <Link
        v-if="user && !user.is_guest"
        :href="route('rankings.index')"
        :title="__('Rankings')"
        class="flex min-w-0 flex-1 items-center justify-center px-2 py-2"
      >
        <div class="retro-icon-btn text-brand-secondary">
          <Icon name="trophy" class="h-5 w-5 drop-shadow-[0_0_8px_rgb(249_237_105/0.5)]" />
        </div>
      </Link>

      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <LevelDisplay
          :level="user.level || 1"
          :current-xp="user.current_xp || 0"
          :xp-for-next-level="user.xp_for_next_level || 100"
        />
      </div>

      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <Notifications placement="top-end" />
      </div>

      <div v-if="user && !user.is_guest" class="flex items-center justify-center min-w-0 flex-1 px-2 py-2">
        <UserDropdown />
      </div>

      <div v-if="!user || user.is_guest" class="flex items-center justify-center gap-2 flex-1 px-2">
        <Link
          :href="user?.is_guest ? route('guest.to-login') : route('login')"
          :title="__('Login')"
          class="retro-nav-btn text-xs"
        >
          {{ __('Login') }}
        </Link>
        <Link
          :href="user?.is_guest ? route('guest.to-register') : route('register')"
          :title="__('Register')"
          class="retro-nav-btn--primary text-xs"
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
