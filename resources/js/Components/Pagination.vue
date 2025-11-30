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
  <div v-if="links.length > 3" class="mt-6 sm:mt-8">
    <div class="flex flex-wrap justify-center gap-1.5 sm:gap-2">
      <template v-for="(link, key) in links" :key="key">
        <span
          v-if="!link.url"
          class="rounded-lg bg-neutral-800 px-2 py-1.5 text-xs font-medium text-neutral-500 sm:px-4 sm:py-2 sm:text-sm"
          v-html="link.label"
        />
        <Link
          v-else
          :href="link.url"
          class="rounded-lg px-2 py-1.5 text-xs font-medium transition-colors duration-200 sm:px-4 sm:py-2 sm:text-sm"
          :class="
            link.active
              ? 'bg-yellow-500 text-white'
              : 'bg-neutral-800 text-neutral-300 hover:bg-neutral-700 hover:text-white'
          "
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>

