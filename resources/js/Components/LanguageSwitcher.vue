<template>
  <div v-if="standalone" class="relative">
    <label for="language-select" class="sr-only">{{ __('Language') }}</label>
    <select
      id="language-select"
      :value="currentLocale"
      @change="handleLanguageChange"
      class="appearance-none bg-neutral-800/50 border border-neutral-700/50 rounded-md px-3 py-1.5 pr-8 text-sm font-semibold uppercase text-neutral-300 hover:text-blue-400 hover:border-blue-500/50 hover:bg-blue-500/10 transition-colors duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900"
    >
      <option
        v-for="locale in availableLocales"
        :key="locale"
        :value="locale"
        class="bg-neutral-800 text-neutral-300"
      >
        {{ locale }} - {{ localeNames[locale] || locale }}
      </option>
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
      <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>
  </div>
  <div v-else class="flex items-center gap-2" @click.stop>
    <label for="language-select-dropdown" class="sr-only">{{ __('Language') }}</label>
    <select
      id="language-select-dropdown"
      :value="currentLocale"
      @change="handleLanguageChange"
      class="appearance-none bg-transparent border border-neutral-700/50 rounded px-2 py-1 text-sm font-semibold uppercase text-neutral-300 hover:text-blue-400 hover:border-blue-500/50 hover:bg-blue-500/10 transition-colors duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800"
    >
      <option
        v-for="locale in availableLocales"
        :key="locale"
        :value="locale"
        class="bg-neutral-800 text-neutral-300"
      >
        {{ locale }}
      </option>
    </select>
  </div>
</template>

<script>
import { router, usePage } from '@inertiajs/vue3'

export default {
  props: {
    standalone: {
      type: Boolean,
      default: true,
    },
  },

  computed: {
    currentLocale() {
      return usePage().props.locale || 'fr'
    },
    
    availableLocales() {
      return usePage().props.available_locales || ['fr', 'en']
    },
    
    localeNames() {
      return usePage().props.locale_names || {
        'fr': 'Français',
        'en': 'English',
        'es': 'Español',
      }
    },
  },

  methods: {
    handleLanguageChange(event) {
      const selectedLocale = event.target.value
      // Empêcher la propagation pour éviter que le dropdown parent se ferme
      if (event.stopPropagation) {
        event.stopPropagation()
      }
      // Naviguer vers la nouvelle langue
      router.visit(this.route('language', [selectedLocale]), {
        preserveState: true,
        preserveScroll: true,
      })
    },
  },
}
</script>