<template>
  <dropdown placement="bottom-end">
    <template #default>
      <div class="group flex cursor-pointer select-none items-center">
        <div class="whitespace-nowrap" title="Langue">
          <span class="uppercase">{{ $page.props.locale }}</span>
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
