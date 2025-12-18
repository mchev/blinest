<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const room = computed(() => usePage().props.room)
const appUrl = 'https://blinest.com'

const structuredDataJson = computed(() => {
  if (!room.value) {
    return null
  }

  const data = {
    "@context": "https://schema.org",
    "@type": "VideoGame",
    "name": room.value.name,
    "description": room.value.description || `${room.value.name} - Quiz musical multijoueur sur Blinest`,
    "url": `${appUrl}/rooms/${room.value.slug}`,
    "image": room.value.photo || `${appUrl}/images/statics/logo_blinest.png`,
    "gamePlatform": "Web Browser",
    "applicationCategory": "Game",
    "genre": room.value.category?.name || "Music Quiz",
    "aggregateRating": room.value.rounds_count > 0 ? {
      "@type": "AggregateRating",
      "ratingValue": "4.5",
      "ratingCount": room.value.rounds_count,
      "bestRating": "5",
      "worstRating": "1"
    } : undefined,
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "EUR"
    },
    "publisher": {
      "@type": "Organization",
      "name": "Blinest",
      "url": appUrl,
      "logo": {
        "@type": "ImageObject",
        "url": `${appUrl}/images/statics/logo_blinest.png`
      }
    },
    "author": room.value.owner ? {
      "@type": "Person",
      "name": room.value.owner.name
    } : undefined,
    "datePublished": room.value.created_at,
    "dateModified": room.value.updated_at,
    "inLanguage": ["fr", "en", "es"],
    "isAccessibleForFree": true,
    "playMode": "MultiPlayer"
  }

  // Remove undefined values
  Object.keys(data).forEach(key => {
    if (data[key] === undefined) {
      delete data[key]
    }
  })

  return JSON.stringify(data)
})
</script>

<template>
  <teleport to="head" v-if="structuredDataJson">
    <component :is="'script'" type="application/ld+json" v-bind="{}">
      {{ structuredDataJson }}
    </component>
  </teleport>
</template>

