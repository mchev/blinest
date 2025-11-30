<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Icon from '@/Components/Icon.vue'

const page = usePage()
const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })
  return translation
}

const currentPath = computed(() => {
  // Get the pathname without query params
  return page.url.split('?')[0]
})

const tabs = [
  { id: 'level', label: __('Level'), icon: 'shield', route: route('rankings.level') },
  { id: 'score', label: __('Score'), icon: 'trophy', route: route('rankings.score') },
  { id: 'week', label: __('Top Week'), icon: 'calendar', route: route('rankings.week') },
  { id: 'teams', label: __('Teams'), icon: 'users', route: route('rankings.teams') },
]

const isActive = (tab) => {
  // Extract path from full URL (route() returns full URL with domain)
  const tabUrl = new URL(tab.route)
  const tabPath = tabUrl.pathname
  return currentPath.value === tabPath
}
</script>

<template>
  <div class="mb-6 border-b border-neutral-800 sm:mb-8">
    <nav class="flex gap-2 overflow-x-auto -mb-px [-webkit-scrollbar]:hidden [-ms-overflow-style]:none [scrollbar-width]:none" aria-label="Tabs">
      <Link
        v-for="tab in tabs"
        :key="tab.id"
        :href="tab.route"
        :class="[
          'group inline-flex flex-shrink-0 items-center gap-2 px-3 py-2 text-xs font-medium transition-all duration-200 sm:px-4 sm:py-3 sm:text-sm',
          isActive(tab)
            ? 'border-b-2 border-yellow-500 text-yellow-500 bg-yellow-500/5'
            : 'border-b-2 border-transparent text-neutral-400 hover:text-neutral-300 hover:border-neutral-600',
        ]"
      >
        <svg
          v-if="tab.icon === 'shield'"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-4 w-4 sm:h-5 sm:w-5"
        >
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        </svg>
        <Icon
          v-else-if="tab.icon === 'trophy'"
          name="trophy"
          class="h-4 w-4 sm:h-5 sm:w-5"
        />
        <svg
          v-else-if="tab.icon === 'calendar'"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-4 w-4 sm:h-5 sm:w-5"
        >
          <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
          <line x1="16" x2="16" y1="2" y2="6" />
          <line x1="8" x2="8" y1="2" y2="6" />
          <line x1="3" x2="21" y1="10" y2="10" />
        </svg>
        <svg
          v-else-if="tab.icon === 'users'"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="h-4 w-4 sm:h-5 sm:w-5"
        >
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        {{ tab.label }}
      </Link>
    </nav>
  </div>
</template>
