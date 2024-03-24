<script setup>
import { watch, onMounted } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
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

onMounted(() => {
  // Select the HTML element to which you want to add attributes
  const htmlElement = document.querySelector('html');
  htmlElement.setAttribute('itemscope', true);
  htmlElement.setAttribute('itemtype', 'https://schema.org/FAQPage');
});

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
      <text-input v-model="form.search" class="mr-4 w-full max-w-md" :placeholder="__('Search in FAQ')" />
      <Pagination :links="faqs.links" class="mt-4 mb-0 mx-0 justify-center"/>
    </div>
    <TransitionGroup name="faq" tag="ul" v-if="faqs.data.length" class="flex flex-col gap-4 max-w-6xl mx-auto my-8">
      <FAQ v-for="faq in faqs.data" :faq="faq" :key="faq.id"/>
    </TransitionGroup>
    <div v-else class="max-w-6xl mx-auto text-center my-8">
      Aucun resultat
    </div>
    <div class="max-w-6xl mx-auto text-center my-4">
      <Pagination :links="faqs.links" class="my-0 mx-0 justify-center"/>
    </div>
  </AppLayout>
</template>