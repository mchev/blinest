<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import Icon from '@/Components/Icon.vue'

const page = usePage()

const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (replaceKey) {
    translation = translation.replace(':' + replaceKey, replace[replaceKey])
  })
  return translation
}

const currentPath = computed(() => page.url.split('?')[0])

const tabs = [
  { id: 'players', label: __('Players'), icon: 'trophy', route: route('rankings.index') },
  { id: 'minigames', label: __('Mini-games'), icon: 'gamepad', route: route('rankings.minigames') },
  { id: 'teams', label: __('Teams'), icon: 'users', route: route('rankings.teams') },
]

const isActive = (tab) => {
  const tabUrl = new URL(tab.route)
  const tabPath = tabUrl.pathname

  if (tab.id === 'players') {
    return currentPath.value === tabPath || currentPath.value.startsWith('/rankings/level') || currentPath.value.startsWith('/rankings/score') || currentPath.value.startsWith('/rankings/elo') || currentPath.value.startsWith('/rankings/week')
  }

  return currentPath.value === tabPath
}
</script>

<template>
  <div class="mb-6 border-b border-neutral-800 sm:mb-8">
    <nav class="[-webkit-scrollbar]:hidden [-ms-overflow-style]:none [scrollbar-width]:none -mb-px flex gap-2 overflow-x-auto" aria-label="Tabs">
      <Link
        v-for="tab in tabs"
        :key="tab.id"
        :href="tab.route"
        :class="['group inline-flex flex-shrink-0 items-center gap-2 px-3 py-2 text-xs font-medium transition-all duration-200 sm:px-4 sm:py-3 sm:text-sm', isActive(tab) ? 'border-b-2 border-yellow-500 bg-yellow-500/5 text-yellow-500' : 'border-b-2 border-transparent text-neutral-400 hover:border-neutral-600 hover:text-neutral-300']"
      >
        <Icon v-if="tab.icon === 'trophy'" name="trophy" class="h-4 w-4 sm:h-5 sm:w-5" />
        <svg v-else-if="tab.icon === 'gamepad'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 sm:h-5 sm:w-5">
          <line x1="6" x2="10" y1="12" y2="12" />
          <line x1="8" x2="8" y1="10" y2="14" />
          <line x1="15" x2="15.01" y1="13" y2="13" />
          <line x1="18" x2="18.01" y1="11" y2="11" />
          <rect width="20" height="12" x="2" y="6" rx="2" />
        </svg>
        <svg v-else-if="tab.icon === 'users'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 sm:h-5 sm:w-5">
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
