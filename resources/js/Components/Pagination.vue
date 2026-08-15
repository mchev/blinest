<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: {
    type: Array,
    required: true,
  },
})

const visit = (url) => {
  if (url) {
    router.visit(url, { preserveState: true, preserveScroll: true })
  }
}
</script>

<template>
  <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1.5 sm:gap-2">
    <template v-for="(link, key) in links" :key="key">
      <span
        v-if="!link.url"
        class="retro-pagination-link cursor-default text-white/40"
        v-html="link.label"
      />
      <Link
        v-else
        :href="link.url"
        class="retro-pagination-link"
        :class="link.active ? 'retro-pagination-link--active' : ''"
        v-html="link.label"
      />
    </template>
  </div>
</template>
