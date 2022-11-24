<script setup>
import { ref, watch } from 'vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import AppHead from '@/Layouts/AppHead.vue'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const props = defineProps({
  session: Object,
})

const authModalIsOpen = ref(false)

watch(
  () => props.session,
  (session) => {
    authModalIsOpen.value = !!sessions?.requireAuth;
  }
)

</script>
<template>
  <AppHead>
      <meta property="og:image" content="/images/screenshot.png" />
      <meta property="og:title" content="Blind-Tests multijoueurs" />
      <meta property="og:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
      <meta property="og:type" content="website" />
      <meta name="twitter:card" content="summary">
      <meta name="twitter:description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc.">
      <meta name="twitter:title" content="Blind-Tests multijoueurs">
      <meta name="twitter:site" content="@PegaseMartin">
  </AppHead>
  <div class="text-neutral-200">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar/>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="flex flex-col px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4 justify-between" scroll-region>
              <flash-messages />
              <slot />
              <Footer/>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>
