<script setup>
import { ref, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import StructuredData from '@/Components/StructuredData.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const props = defineProps({
  session: Object,
})

const authModalIsOpen = ref(false)
const url = window.location.href

watch(
  () => props.session,
  (session) => {
    authModalIsOpen.value = !!sessions?.requireAuth
  },
)
</script>
<template>
  <StructuredData/>
  <Head>
    <meta itemprop="url" :content="url" />
    <!-- Facebook Meta Tags -->
    <meta property="og:url" :content="url" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
    <meta property="og:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
    <meta property="og:image" content="https://blinest.com/images/statics/screenshot.png" />

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta property="twitter:domain" content="blinest.com" />
    <meta property="twitter:url" :content="url" />
    <meta name="twitter:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
    <meta name="twitter:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
    <meta name="twitter:image" content="https://blinest.com/images/statics/screenshot.png" />
  </Head>
  <div class="text-neutral-200">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar />
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="flex flex-col justify-between px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4" scroll-region>
              <flash-messages />
              <slot />
              <Footer />
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>
