<script setup>
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'
import MobileBottomNav from '@/Components/MobileBottomNav.vue'
import Footer from '@/Components/Footer.vue'
import SkipLink from '@/Components/SkipLink.vue'
import EzoicAd from '@/Components/EzoicAd.vue'
import { EZOIC } from '@/ezoic'
</script>
<template>
  <div class="text-white min-h-screen">
    <SkipLink />
    <div id="dropdown" />
    <div class="md:flex md:flex-col h-full">
      <div class="md:flex md:h-full md:flex-col">
        <Navbar />
        <div class="md:flex md:flex-grow md:overflow-hidden md:min-h-0">
          <Transition name="slide-right" appear>
            <main
              v-if="$slots.default"
              id="main-content"
              tabindex="-1"
              class="flex flex-col justify-between px-4 py-4 pb-20 md:pb-4 md:flex-1 md:overflow-y-auto md:px-12 md:pt-6 md:min-h-0 focus:outline-none"
              scroll-region
            >
              <flash-messages />
              <slot />
              <EzoicAd :placement-id="EZOIC.bottomOfPage" wrapper-class="mt-8 mb-4" />
              <Footer />
            </main>
          </Transition>
        </div>
      </div>
    </div>
    <MobileBottomNav />
  </div>
</template>
