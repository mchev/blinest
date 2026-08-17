<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  items: {
    type: Array,
    required: true,
    validator: (items) => {
      return items.every((item) => item.label && item.url)
    },
  },
})

const appUrl = 'https://blinest.com'

const structuredDataJson = computed(() => {
  const data = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: props.items.map((item, index) => ({
      '@type': 'ListItem',
      position: index + 1,
      name: item.label,
      item: item.url.startsWith('http') ? item.url : `${appUrl}${item.url}`,
    })),
  }
  return JSON.stringify(data)
})
</script>

<template>
  <nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex flex-wrap items-center gap-2 text-sm text-white/60">
      <li v-for="(item, index) in items" :key="index" class="flex items-center">
        <Link v-if="index < items.length - 1" :href="item.url" class="transition-colors hover:text-brand-accent">
          {{ item.label }}
        </Link>
        <span v-else class="font-medium text-brand-secondary">{{ item.label }}</span>
        <span v-if="index < items.length - 1" class="mx-2 text-white/30">/</span>
      </li>
    </ol>
  </nav>

  <teleport to="head">
    <component :is="'script'" type="application/ld+json" v-bind="{}">
      {{ structuredDataJson }}
    </component>
  </teleport>
</template>
