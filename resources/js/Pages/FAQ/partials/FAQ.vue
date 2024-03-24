<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue';

const props = defineProps({
  faq: Object,
})

const user = usePage().props.auth.user

const form = useForm({
  id: props.faq.id
})

const voteUp = () => {
  form.post(`/faq/${props.faq.id}/vote/up`)
}

const voteDown = () => {
  form.post(`/faq/${props.faq.id}/vote/down`)
}

// Define structured data
const schema = {
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": {
    "@type": "Question",
    "name": props.faq.question,
    "acceptedAnswer": {
      "@type": "Answer",
      "text": props.faq.answer
    }
  }
}
</script>

<template>
  <li itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <Card>
      <template #header>
        <div class="flex items-center gap-2 font-bold">
          <div class="w-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
          </div>
          <h2 itemprop="name">{{ faq.question }}</h2>
        </div>
      </template>
      <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <div itemprop="text" class="prose whitespace-pre-wrap prose-invert" v-html="faq.answer"></div>
      </div>
      <template #footer v-if="user">
        <div class="ml-auto flex gap-4">
          <button @click="voteUp()" class="flex items-center" :title="__('Like')"><Icon name="thumb-up" class="mr-1 h-5 w-5" /> {{ faq.upvotes }}</button>
          <button @click="voteDown()" class="flex items-center" :title="__('Don\'t like')"><Icon name="thumb-down" class="mr-1 h-5 w-5" /> {{ faq.downvotes }}</button>
        </div>
      </template>
    </Card>
  </li>
</template>