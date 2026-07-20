<script setup>
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import StructuredData from '@/Components/StructuredData.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import Navbar from '@/Components/Navbar.vue'
import MobileBottomNav from '@/Components/MobileBottomNav.vue'
import Footer from '@/Components/Footer.vue'
import EzoicAd from '@/Components/EzoicAd.vue'
import { EZOIC } from '@/ezoic'

const page = usePage()
const url = computed(() => 'https://blinest.com' + page.url)
const appUrl = 'https://blinest.com'

</script>
<template>
  <StructuredData/>
  <Head>
    <!-- Basic SEO -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <meta name="author" content="Blinest" />
    <meta name="language" content="French" />
    <meta name="revisit-after" content="7 days" />
    <meta name="distribution" content="global" />
    <meta name="rating" content="general" />
    
    <!-- Canonical URL -->
    <link rel="canonical" :href="url" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:url" :content="url" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
    <meta property="og:description" content="Jouez à des quiz musicaux multijoueurs gratuits ! Blind-tests en ligne pour tous les goûts : Années 2000, Disney, Chanson française, Années 80, Rock, Pop, et bien plus encore." />
    <meta property="og:image" content="https://blinest.com/images/statics/screenshot.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="Blinest - Quiz musicaux multijoueurs" />
    <meta property="og:site_name" content="Blinest" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:locale:alternate" content="en_US" />
    <meta property="og:locale:alternate" content="es_ES" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@blinest" />
    <meta name="twitter:creator" content="@blinest" />
    <meta property="twitter:domain" content="blinest.com" />
    <meta property="twitter:url" :content="url" />
    <meta name="twitter:title" content="Blinest - Quiz musicaux gratuits et multijoueurs" />
    <meta name="twitter:description" content="Jouez à des quiz musicaux multijoueurs gratuits ! Blind-tests en ligne pour tous les goûts : Années 2000, Disney, Chanson française, Années 80, Rock, Pop, et bien plus encore." />
    <meta name="twitter:image" content="https://blinest.com/images/statics/screenshot.png" />
    <meta name="twitter:image:alt" content="Blinest - Quiz musicaux multijoueurs" />
    
    <!-- SEO for AI -->
    <meta name="keywords" content="quiz musical, blind test, quiz musique, test musical, quiz multijoueur, jeu musical en ligne, quiz gratuit, blind test gratuit, quiz années 2000, quiz disney, quiz chanson française" />
    <meta name="topic" content="Jeux musicaux en ligne" />
    <meta name="subject" content="Quiz musicaux multijoueurs gratuits" />
    <meta name="classification" content="Entertainment, Games, Music" />
    <meta name="category" content="Music Quiz Games" />
    <meta name="coverage" content="Worldwide" />
    <meta name="target" content="all" />
    <meta name="audience" content="all" />
    <meta name="application-name" content="Blinest" />
  </Head>
  <div class="text-white min-h-screen">
    <div id="dropdown" />
    <div class="md:flex md:flex-col h-full">
      <div class="md:flex md:h-full md:flex-col">
        <Navbar />
        <div class="md:flex md:flex-grow md:overflow-hidden md:min-h-0">
          <Transition name="slide-right" appear>
            <div v-if="$slots.default" class="flex flex-col justify-between px-4 py-4 pb-20 md:pb-4 md:flex-1 md:overflow-y-auto md:px-12 md:pt-6 md:min-h-0" scroll-region>
              <flash-messages />
              <EzoicAd :placement-id="EZOIC.topOfPage" wrapper-class="mb-6" />
              <slot />
              <EzoicAd :placement-id="EZOIC.bottomOfPage" wrapper-class="mt-8 mb-4" />
              <Footer />
            </div>
          </Transition>
        </div>
      </div>
    </div>
    <MobileBottomNav />
  </div>
</template>
