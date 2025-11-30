<template>
  <dropdown placement="bottom-end">
    <template #default>
      <div class="group flex cursor-pointer select-none items-center transition-all duration-200 hover:scale-110" :title="__('Language')">
        <div class="relative whitespace-nowrap">
          <span class="uppercase font-bold text-neutral-400 group-hover:text-blue-400 transition-colors duration-200 px-2 py-1 rounded-md border border-transparent group-hover:border-blue-500/30 group-hover:bg-blue-500/10">
            {{ $page.props.locale }}
          </span>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute -top-1 -right-1 h-3 w-3 text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
            <path d="M2 12h20" />
          </svg>
        </div>
      </div>
    </template>
    <template #dropdown>
      <Link :href="route('language', [selectable_locale])" class="p-3 uppercase leading-8">
        {{ selectable_locale }}
      </Link>
    </template>
  </dropdown>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Dropdown from '@/Components/Dropdown.vue'

export default {
  components: {
    Link,
    Icon,
    Dropdown,
  },

  computed: {
    selectable_locale() {
      if (this.$page.props.locale == 'fr') {
        return 'en'
      }
      return 'fr'
    },
  },
}
</script>