<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
  links: Array,
})
</script>
<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap justify-end m-6">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="mb-1 mr-1 bg-neutral-700 rounded px-4 py-3 text-sm leading-4 text-neutral-300" v-html="link.label" />
        <Link v-else :key="`link-${key}`" class="mb-1 mr-1 rounded px-4 py-3 text-sm leading-4 bg-neutral-700 hover:bg-neutral-500" :class="{ 'bg-neutral-500': link.active }" :href="link.url" v-html="link.label" />
      </template>
    </div>
  </div>
</template>
