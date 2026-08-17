<script setup>
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'
import SkipLink from '@/Components/SkipLink.vue'
import MobileBottomNav from '@/Components/MobileBottomNav.vue'
</script>
<template>
  <div class="text-white">
    <SkipLink />
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar />

        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right">
            <main
              v-if="$slots.default"
              id="main-content"
              tabindex="-1"
              class="min-w-0 overflow-x-hidden pb-20 focus:outline-none md:pb-0 md:flex-1"
            >
              <FlashMessages />
              <slot />
            </main>
          </Transition>
        </div>
      </div>
    </div>
    <MobileBottomNav />
  </div>
</template>
