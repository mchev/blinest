<script setup>
import { computed } from 'vue'

const props = defineProps({
  faqs: {
    type: Array,
    required: true,
  },
})

const structuredDataJson = computed(() => {
  const data = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": props.faqs.map(faq => ({
      "@type": "Question",
      "name": faq.question,
      "acceptedAnswer": {
        "@type": "Answer",
        "text": faq.answer.replace(/<[^>]*>/g, ''),
      },
    })),
  }
  return JSON.stringify(data)
})
</script>

<template>
  <teleport to="head">
    <component :is="'script'" type="application/ld+json" v-bind="{}">
      {{ structuredDataJson }}
    </component>
  </teleport>
</template>