<script setup>
import { ref, watch, onMounted } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'
import FAQ from './partials/FAQ.vue'
import FAQStructuredData from '@/Components/FAQStructuredData.vue'
import Icon from '@/Components/Icon.vue'
import EzoicAd from '@/Components/EzoicAd.vue'
import { EZOIC } from '@/ezoic'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  filters: Object,
  faqs: Object,
})

const form = useForm({
  search: props.filters.search,
})

const openFaqs = ref(new Set())

const toggleFaq = (faqId) => {
  if (openFaqs.value.has(faqId)) {
    openFaqs.value.delete(faqId)
  } else {
    openFaqs.value.add(faqId)
  }
}

onMounted(() => {
  const hash = window.location.hash
  if (hash) {
    const faqId = hash.replace('#', '')
    const element = document.getElementById(faqId)
    if (element) {
      setTimeout(() => {
        const offset = 100
        const elementPosition = element.getBoundingClientRect().top
        const offsetPosition = elementPosition + window.pageYOffset - offset
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth',
        })
        const idMatch = faqId.match(/faq-(\d+)/)
        if (idMatch) {
          openFaqs.value.add(Number(idMatch[1]))
        }
      }, 300)
    }
  }
})

watch(
  form,
  throttle(() => {
    router.get('/docs/faq', pickBy(form), {
      remember: 'forget',
      preserveState: true,
    })
  }, 150),
  { deep: true },
)
</script>

<template>
  <Head title="FAQ" />
  <FAQStructuredData v-if="faqs.data.length > 0" :faqs="faqs.data" />
  <AppLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8 sm:mb-12">
        <div class="text-center mb-8">
          <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">
            {{ __('Frequently Asked Questions') }}
          </h1>
          <p class="text-lg text-neutral-400 max-w-2xl mx-auto">
            {{ __('Find answers to common questions about Blinest') }}
          </p>
          <EzoicAd :placement-id="EZOIC.underFirstParagraph" wrapper-class="mt-6 max-w-2xl mx-auto" compact />
        </div>

        <div class="max-w-2xl mx-auto mb-6">
          <label for="faq-search" class="sr-only">{{ __('Search in FAQ') }}</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none" aria-hidden="true">
              <Icon name="search" class="h-5 w-5 text-neutral-500" />
            </div>
            <TextInput
              id="faq-search"
              v-model="form.search"
              :placeholder="__('Search in FAQ...')"
              class="w-full pl-11 pr-4 py-3 bg-neutral-800/50 border-neutral-700/50 focus:border-teal-500/50 focus:ring-teal-500/20"
              :aria-label="__('Search in FAQ')"
            />
          </div>
        </div>

        <div v-if="faqs.data.length > 0" class="text-center">
          <p class="text-sm text-neutral-500">
            {{ __('Showing') }} {{ faqs.data.length }} {{ __('results') }}
          </p>
        </div>
      </div>

      <div class="max-w-4xl mx-auto">
        <TransitionGroup
          name="faq"
          tag="section"
          v-if="faqs.data.length"
          class="space-y-4"
          role="list"
          :aria-label="__('Frequently Asked Questions')"
        >
          <FAQ
            v-for="faq in faqs.data"
            :key="faq.id"
            :faq="faq"
            :is-open="openFaqs.has(faq.id)"
            @toggle="toggleFaq"
          />
        </TransitionGroup>

        <div
          v-else
          class="text-center py-16 px-4 rounded-2xl border border-neutral-700/50 bg-gradient-to-br from-neutral-800/40 to-neutral-800/20"
        >
          <div class="max-w-md mx-auto">
            <div class="mb-4 flex justify-center">
              <div class="flex h-16 w-16 items-center justify-center rounded-full bg-neutral-700/30">
                <Icon name="search" class="h-8 w-8 text-neutral-500" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">
              {{ __('No results found') }}
            </h3>
            <p class="text-neutral-400 mb-6">
              {{ __('Try adjusting your search terms') }}
            </p>
            <div class="pt-6 border-t border-neutral-700/30">
              <p class="text-sm text-neutral-400 mb-4">
                {{ __('Still have questions?') }}
              </p>
              <Link
                :href="route('contact')"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-gradient-to-r from-teal-500 to-teal-600 text-white font-medium hover:from-teal-600 hover:to-teal-700 transition-all duration-200 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="h-5 w-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                  />
                </svg>
                {{ __('Contact us') }}
              </Link>
            </div>
          </div>
        </div>

        <div v-if="faqs.links && faqs.links.length > 3" class="mt-8">
          <Pagination :links="faqs.links" class="justify-center" />
        </div>

        <div
          v-if="faqs.data.length > 0"
          class="mt-12 text-center py-8 px-6 rounded-2xl border border-neutral-700/50 bg-gradient-to-br from-neutral-800/40 to-neutral-800/20"
        >
          <div class="max-w-2xl mx-auto">
            <h3 class="text-xl font-semibold text-white mb-2">
              {{ __('Still have questions?') }}
            </h3>
            <p class="text-neutral-400 mb-6">
              {{ __('If you couldn\'t find the answer you were looking for, feel free to contact us.') }}
            </p>
            <Link
              :href="route('contact')"
              class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-gradient-to-r from-teal-500 to-teal-600 text-white font-medium hover:from-teal-600 hover:to-teal-700 transition-all duration-200 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                class="h-5 w-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                />
              </svg>
              {{ __('Contact us') }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>