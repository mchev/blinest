<script setup>
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'

</script>
<template>
  <div class="text-neutral-200">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar/>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="px-4 py-4 md:flex-1 md:overflow-y-auto md:px-12 md:py-4" scroll-region>
              <flash-messages />
              <slot />
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>
