<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'

const props = defineProps({
  filters: Object,
})

const form = useForm({
  search: usePage().props?.filters?.search,
})

watch(
  form,
  throttle(() => {
    router.get('/', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)
</script>
<template>
  <div :class="$attrs.class">
    <form>
      <text-input class="text-sm" v-model="form.search" spellcheck="false" prepend-icon="search" :placeholder="__('Search a room') + '...'" />
      <input class="hidden" type="text" name="search_term_string" v-model="form.search" required />
    </form>
  </div>
</template>
