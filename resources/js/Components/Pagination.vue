<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

defineProps({
  links: Array,
})

const visit = (url) => {
  router.visit(url, { preserveState: true, preserveScroll: true }, { only: ['categories'] })
}
</script>
<template>
  <div v-if="links.length > 3">
    <div class="m-6 flex flex-wrap justify-end">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="mb-1 mr-1 rounded bg-neutral-700 px-4 py-3 text-sm leading-4 text-neutral-300" v-html="link.label" />
        <button v-else :key="`link-${key}`" class="mb-1 mr-1 rounded px-4 py-3 text-sm leading-4 hover:bg-neutral-500" :class="link.active ? 'bg-neutral-500' : 'bg-neutral-700'" @click="visit(link.url)" v-html="link.label" />
      </template>
    </div>
  </div>
</template>
