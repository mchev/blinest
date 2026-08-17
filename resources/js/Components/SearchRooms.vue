<script setup>
import { watch, ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import TextInput from '@/Components/TextInput.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Search a room',
  },
  debounceTime: {
    type: Number,
    default: 150,
  },
})

const form = useForm({
  search: usePage().props?.filters?.search || '',
})

const isSearchFocused = ref(false)
const searchInput = ref(null)

const debouncedSearch = debounce(() => {
  if (!form.search || form.search.trim() === '') {
    router.visit(route('home'))
    return
  }

  router.get(
    '/',
    { search: form.search },
    {
      remember: 'forget',
      preserveState: true,
      only: ['search_result'],
    },
  )
}, props.debounceTime)

const clearSearch = () => {
  form.search = ''
  router.visit(route('home'))
  if (searchInput.value) {
    searchInput.value.focus()
  }
}

const focusSearch = () => {
  if (searchInput.value) {
    searchInput.value.focus()
  }
}

// Use keyboard shortcut '/' to focus search
onMounted(() => {
  const handleKeyDown = (e) => {
    if (e.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
      e.preventDefault()
      focusSearch()
    }
  }

  document.addEventListener('keydown', handleKeyDown)

  return () => {
    document.removeEventListener('keydown', handleKeyDown)
  }
})

watch(
  () => form.search,
  () => debouncedSearch(),
)
</script>

<template>
  <div :class="$attrs.class">
    <form @submit.prevent class="relative">
      <div class="retro-search group" :class="{ 'border-white/25 shadow-lg': isSearchFocused }">
        <Icon name="search" class="h-5 w-5 text-white/50 transition-colors group-hover:text-white" :class="{ 'text-white': isSearchFocused }" />

        <input ref="searchInput" v-model="form.search" class="w-full bg-transparent px-3 py-1 text-sm text-white placeholder-white/60 focus:outline-none" :placeholder="__(`${props.placeholder}`) + '...'" spellcheck="false" @focus="isSearchFocused = true" @blur="isSearchFocused = false" @keydown.esc="clearSearch" />

        <button v-if="form.search" type="button" @click="clearSearch" class="flex h-6 w-6 items-center justify-center bg-brand-midnight text-white/70 transition-colors hover:text-white" title="Clear search">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
          </svg>
        </button>

        <kbd v-else class="hidden h-5 w-5 items-center justify-center border border-white/20 bg-brand-midnight text-xs text-white/50 md:flex" title="Press / to search"> / </kbd>
      </div>
      <input class="hidden" type="text" name="search_term_string" v-model="form.search" required />
    </form>
  </div>
</template>
