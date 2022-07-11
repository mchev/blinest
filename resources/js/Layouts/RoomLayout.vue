<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import Navbar from '@/Components/Navbar.vue'
</script>
<template>
  <div class="text-neutral-200">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar />

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
