<script setup>
import { computed } from 'vue'
import { Link, Head, usePage } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import Navbar from '@/Components/Navbar.vue'

const room = computed(() => usePage().props.value.room)
</script>
<template>
  <Head>
    <title>{{ room.name }}</title>
    <meta name="description" :content="room.description" />

    <!-- Facebook Meta Tags -->
    <meta property="og:url" :content="room.url" />
    <meta property="og:type" content="website" />
    <meta property="og:image" :content="room.photo_src ? room.photo_src : room.photo" />
    <meta property="og:title" :content="room.name + ' - Blinest.com'" />
    <meta property="og:description" :content="room.description" />

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta property="twitter:domain" content="blinest.com" />
    <meta property="twitter:url" :content="room.url" />
    <meta name="twitter:description" :content="room.description" />
    <meta name="twitter:title" :content="room.name + ' - Blinest'" />
    <meta name="twitter:image" :content="room.photo_src ? room.photo_src : room.photo" />
  </Head>
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
