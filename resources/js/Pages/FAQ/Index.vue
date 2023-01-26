<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'
import FAQ from './partials/FAQ.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  filters: Object,
  faqs: Object,
})

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    router.get('/faq', pickBy(form), { 
      remember: 'forget', 
      preserveState: true 
    })
  }, 150),
  { deep: true },
)

</script>
<template>
  <Head title="FAQ" />
  <AppLayout>
    <div class="w-full flex flex-col items-center justify-center">
      <h1 class="mb-8 text-3xl font-bold">{{ __('FAQ') }}</h1>
      <text-input v-model="form.search" class="mr-4 w-full max-w-md" placeholder="Une question ?" />
    </div>
    <div v-if="faqs.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <FAQ v-for="faq in faqs.data" :faq="faq" :key="faq.id"/>
    </div>
  </AppLayout>
</template>
