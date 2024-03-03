<script setup>
import { watch, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import TextInput from '@/Components/TextInput.vue'

const form = useForm({
  search: usePage().props?.filters?.search,
})

const debouncedSearch = ref(
  debounce(() => {
    if (form.search === '') {
      router.visit(route('home'))
      return
    }
    router.get('/', { search: form.search }, { 
      remember: 'forget', 
      preserveState: true,
      only: ['search_result']
    })
  }, 150),
)

watch(() => form.search, debouncedSearch.value)
</script>
<template>
  <div :class="$attrs.class">
    <form>
      <TextInput class="text-sm" v-model="form.search" spellcheck="false" prepend-icon="search" :placeholder="__('Search a room') + '...'" />
      <input class="hidden" type="text" name="search_term_string" v-model="form.search" required />
    </form>
  </div>
</template>
