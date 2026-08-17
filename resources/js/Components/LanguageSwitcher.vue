<template>
  <div v-if="standalone" class="relative">
    <label for="language-select" class="sr-only">{{ __('Language') }}</label>
    <select
      id="language-select"
      :value="currentLocale"
      @change="handleLanguageChange"
      class="retro-select cursor-pointer pr-8"
    >
      <option
        v-for="locale in availableLocales"
        :key="locale"
        :value="locale"
        class="bg-brand-deep text-white"
      >
        {{ locale }} - {{ localeNames[locale] || locale }}
      </option>
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
      <svg class="h-4 w-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
      class="retro-select cursor-pointer bg-transparent px-2 py-1 text-sm"
    >
      <option
        v-for="locale in availableLocales"
        :key="locale"
        :value="locale"
        class="bg-brand-deep text-white"
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
      if (event.stopPropagation) {
        event.stopPropagation()
      }
      router.visit(this.route('language', selectedLocale), {
        preserveScroll: true,
      })
    },
  },
}
</script>
