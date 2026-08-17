<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  faq: Object,
  isOpen: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['toggle'])

const user = usePage().props.auth.user
const isExpanded = ref(props.isOpen)
const copySuccess = ref(false)

const form = useForm({
  id: props.faq.id,
})

const voteUp = () => {
  form.post(`/faq/${props.faq.id}/vote/up`)
}

const voteDown = () => {
  form.post(`/faq/${props.faq.id}/vote/down`)
}

const toggle = () => {
  isExpanded.value = !isExpanded.value
  emit('toggle', props.faq.id)
}

const faqId = computed(() => `faq-${props.faq.id}`)

const faqUrl = computed(() => {
  return `${window.location.origin}${window.location.pathname}#${faqId.value}`
})

const copyLink = async (event) => {
  event.stopPropagation()

  try {
    if (navigator.clipboard && window.isSecureContext) {
      await navigator.clipboard.writeText(faqUrl.value)
      copySuccess.value = true
      setTimeout(() => {
        copySuccess.value = false
      }, 2000)
    } else {
      const textArea = document.createElement('textarea')
      textArea.value = faqUrl.value
      textArea.style.position = 'fixed'
      textArea.style.opacity = '0'
      document.body.appendChild(textArea)
      textArea.select()
      document.execCommand('copy')
      document.body.removeChild(textArea)
      copySuccess.value = true
      setTimeout(() => {
        copySuccess.value = false
      }, 2000)
    }
  } catch {
    // Silently fail if clipboard access is denied
  }
}
</script>

<template>
  <article
    :id="faqId"
    class="group relative overflow-hidden rounded-2xl border border-neutral-700/50 bg-gradient-to-br from-neutral-800/40 to-neutral-800/20 backdrop-blur-sm transition-all duration-300"
    :class="{
      'border-teal-500/30 shadow-lg shadow-teal-500/5': isExpanded,
      'hover:border-neutral-600/50 hover:shadow-lg': !isExpanded,
    }"
    role="article"
    :aria-labelledby="`${faqId}-question`"
  >
    <button @click="toggle" @keydown.enter.prevent="toggle" @keydown.space.prevent="toggle" class="w-full rounded-2xl text-left focus:outline-none" :aria-expanded="isExpanded" :aria-controls="`${faqId}-answer`" :aria-label="`${__('Toggle')}: ${faq.question}`" type="button">
      <div class="flex items-start gap-4 p-5 sm:p-6">
        <div class="flex-shrink-0">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl border transition-all duration-300"
            :class="{
              'border-teal-500/40 bg-gradient-to-br from-teal-500/30 to-teal-600/20': isExpanded,
              'border-teal-500/20 bg-gradient-to-br from-teal-500/20 to-teal-600/10 group-hover:from-teal-500/30 group-hover:to-teal-600/20': !isExpanded,
            }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-teal-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
          </div>
        </div>

        <div class="min-w-0 flex-1">
          <h3
            :id="`${faqId}-question`"
            class="pr-8 text-lg font-semibold leading-relaxed transition-colors"
            :class="{
              'text-teal-50': isExpanded,
              'text-white group-hover:text-teal-50': !isExpanded,
            }"
          >
            {{ faq.question }}
          </h3>
        </div>

        <div class="flex flex-shrink-0 items-center gap-2">
          <button
            @click.stop="copyLink"
            @keydown.enter.stop.prevent="copyLink"
            @keydown.space.stop.prevent="copyLink"
            class="relative flex h-8 w-8 items-center justify-center rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900"
            :class="{
              'bg-teal-500/20 text-teal-400': copySuccess,
              'bg-neutral-700/30 text-neutral-400 hover:bg-neutral-700/50 hover:text-teal-400': !copySuccess,
            }"
            :aria-label="__('Copy link to this FAQ')"
            :title="copySuccess ? __('Link copied!') : __('Copy link to this FAQ')"
            type="button"
          >
            <svg v-if="!copySuccess" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            <span class="sr-only">{{ copySuccess ? __('Link copied!') : __('Copy link') }}</span>
          </button>

          <div
            class="pointer-events-none flex h-8 w-8 items-center justify-center rounded-lg transition-all duration-300"
            :class="{
              'rotate-180 bg-teal-500/20 text-teal-400': isExpanded,
              'bg-neutral-700/30 text-neutral-400 group-hover:bg-neutral-700/50 group-hover:text-teal-400': !isExpanded,
            }"
            aria-hidden="true"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-5 w-5 transition-transform duration-300">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
          </div>
        </div>
      </div>
    </button>

    <div :id="`${faqId}-answer`" class="overflow-hidden transition-all duration-300 ease-in-out" :class="isExpanded ? 'max-h-[5000px] opacity-100' : 'max-h-0 opacity-0'" role="region" :aria-labelledby="`${faqId}-question`" :aria-hidden="!isExpanded">
      <div class="px-5 pb-6 pt-0 sm:px-6 sm:pb-6">
        <div class="ml-14 border-l-2 border-teal-500/30 pl-6">
          <div class="prose prose-sm prose-invert max-w-none leading-relaxed text-neutral-300 prose-headings:text-white prose-p:text-neutral-300 prose-a:text-teal-400 prose-a:no-underline hover:prose-a:underline prose-strong:text-white prose-code:text-teal-300 prose-pre:bg-neutral-900/50" v-html="faq.answer"></div>

          <div v-if="user" class="mt-6 flex items-center gap-4 border-t border-neutral-700/40 pt-5" role="group" :aria-label="__('Rate this answer')">
            <button
              @click.stop="voteUp"
              @keydown.enter.stop.prevent="voteUp"
              @keydown.space.stop.prevent="voteUp"
              class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800"
              :class="{
                'bg-teal-500/10 text-teal-400 hover:bg-teal-500/20': true,
              }"
              :aria-label="__('Like this answer')"
              :title="__('Like')"
              type="button"
            >
              <Icon name="thumb-up" class="h-4 w-4" aria-hidden="true" />
              <span :aria-label="`${__('Upvotes')}: ${faq.upvotes}`">{{ faq.upvotes }}</span>
            </button>
            <button
              @click.stop="voteDown"
              @keydown.enter.stop.prevent="voteDown"
              @keydown.space.stop.prevent="voteDown"
              class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800"
              :class="{
                'text-neutral-400 hover:bg-red-500/10 hover:text-red-400': true,
              }"
              :aria-label="__('Dislike this answer')"
              :title="__('Don\'t like')"
              type="button"
            >
              <Icon name="thumb-down" class="h-4 w-4" aria-hidden="true" />
              <span :aria-label="`${__('Downvotes')}: ${faq.downvotes}`">{{ faq.downvotes }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </article>
</template>
