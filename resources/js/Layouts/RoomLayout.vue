<script setup>
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import StructuredData from '@/Components/StructuredData.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'
import MobileBottomNav from '@/Components/MobileBottomNav.vue'

const page = usePage()
const room = computed(() => page.props.room)
const structuredData = computed(() => page.props.structured_data)
const appUrl = 'https://blinest.com'
const roomUrl = computed(() => `${appUrl}/rooms/${room.value.slug}`)
const metaDescription = computed(() => {
  if (room.value.description) {
    return room.value.description.length > 160 
      ? room.value.description.substring(0, 157) + '...'
      : room.value.description
  }
  return `Jouez à ${room.value.name}, un quiz musical multijoueur gratuit sur Blinest. Rejoignez des milliers de joueurs pour tester vos connaissances musicales !`
})
</script>
<template>
  <StructuredData/>
  
  <!-- Server-side generated structured data for SEO -->
  <teleport to="head" v-if="structuredData">
    <component :is="'script'" type="application/ld+json" v-bind="{}">
      {{ JSON.stringify(structuredData) }}
    </component>
  </teleport>
  <Head>
    <title>{{ room.name }} - Quiz musical multijoueur gratuit | Blinest</title>
    <meta name="description" :content="metaDescription" />
    <meta name="keywords" :content="`quiz musical, blind test, ${room.name}, ${room.category?.name || 'musique'}, multijoueur, gratuit`" />
    <meta name="author" content="Blinest" />
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    
    <!-- Canonical URL -->
    <link rel="canonical" :href="roomUrl" />

    <!-- Facebook Meta Tags -->
    <meta property="og:url" :content="roomUrl" />
    <meta property="og:type" content="website" />
    <meta property="og:image" :content="room.photo || `${appUrl}/images/statics/screenshot.png`" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" :content="`Illustration de la room ${room.name}`" />
    <meta property="og:title" :content="`${room.name} - Quiz musical multijoueur gratuit | Blinest`" />
    <meta property="og:description" :content="metaDescription" />
    <meta property="og:site_name" content="Blinest" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="article:author" content="Blinest" />
    <meta property="article:section" :content="room.category?.name || 'Musique'" />

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@blinest" />
    <meta name="twitter:creator" content="@blinest" />
    <meta property="twitter:domain" content="blinest.com" />
    <meta property="twitter:url" :content="roomUrl" />
    <meta name="twitter:title" :content="`${room.name} - Quiz musical multijoueur gratuit | Blinest`" />
    <meta name="twitter:description" :content="metaDescription" />
    <meta name="twitter:image" :content="room.photo || `${appUrl}/images/statics/screenshot.png`" />
    <meta name="twitter:image:alt" :content="`Illustration de la room ${room.name}`" />
  </Head>
  <div class="text-white">
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <Navbar />

        <div class="md:flex md:flex-grow md:overflow-hidden">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="pb-20 md:pb-0 md:flex-1">
              <FlashMessages />
              <slot />
            </div>
          </Transition>
        </div>
      </div>
    </div>
    <MobileBottomNav />
  </div>
</template>
