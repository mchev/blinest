<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'

defineProps({
  pageTitle: { type: String, required: true },
  /** Optional meta description for SEO (translated string). */
  metaDescription: { type: String, default: '' },
  backUrl: { type: String, required: true },
  homeUrl: { type: String, required: true },
  questionsPerRound: { type: Number, default: 5 },
  sessionScore: { type: Number, default: 0 },
  showSummary: { type: Boolean, default: false },
  currentQuestionIndex: { type: Number, default: 0 },
  roundResults: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  error: { type: [String, null], default: null },
  /** Fallback label in summary when correctValue is missing (e.g. "Track", "Artist") */
  resultItemLabel: { type: String, default: 'Track' },
  /** Whether to show the progress bar (when in game, not summary) */
  showProgress: { type: Boolean, default: false },
})

const emit = defineEmits(['retry'])
</script>

<template>
  <Head>
    <title>{{ pageTitle }} | Blinest</title>
    <meta v-if="metaDescription" head-key="minigame-description" name="description" :content="metaDescription" />
  </Head>
  <AppLayout>
    <div class="mx-auto max-w-xl px-4 py-8">
      <header class="mb-6">
        <div class="flex items-center justify-between">
          <Link
            :href="backUrl"
            class="inline-flex items-center gap-2 text-sm text-neutral-400 transition hover:text-white"
          >
            <Icon name="cheveron-right" class="h-4 w-4 rotate-180" />
            {{ __('Mini-games') }}
          </Link>
          <span
            v-if="sessionScore >= 0 && !showSummary && showProgress"
            class="rounded-full border-2 border-teal-500/50 bg-teal-500/20 px-4 py-1.5 text-sm font-bold text-teal-400"
          >
            {{ sessionScore }} {{ __('points') }}
          </span>
        </div>
        <div v-if="showProgress && !showSummary" class="mt-4">
          <div class="flex justify-between text-xs font-medium uppercase tracking-wider text-neutral-500">
            <span>{{ __('Question') }} {{ currentQuestionIndex + 1 }} / {{ questionsPerRound }}</span>
          </div>
          <div class="mt-2 flex gap-1">
            <div
              v-for="i in questionsPerRound"
              :key="i"
              class="h-2 flex-1 overflow-hidden rounded-full bg-neutral-800"
            >
              <div
                class="h-full rounded-full transition-all duration-300"
                :class="i <= currentQuestionIndex + 1 ? 'bg-gradient-to-r from-teal-500 to-cyan-400' : ''"
                :style="i <= currentQuestionIndex + 1 ? { width: '100%' } : { width: '0%' }"
              />
            </div>
          </div>
        </div>
      </header>

      <div v-if="showSummary" class="space-y-6">
        <div class="overflow-hidden rounded-2xl border-2 border-amber-500/30 bg-neutral-900/90">
          <div class="bg-gradient-to-br from-amber-600/30 via-neutral-800 to-teal-600/20 px-6 py-8 text-center">
            <h2 class="text-2xl font-black uppercase tracking-widest text-white">
              {{ __('Round over!') }}
            </h2>
            <p class="mt-3 text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-amber-400">
              {{ sessionScore }} <span class="text-xl font-bold text-neutral-400">{{ __('points') }}</span>
            </p>
          </div>
          <div class="divide-y divide-neutral-700/50">
            <div
              v-for="(r, index) in roundResults"
              :key="index"
              class="flex items-center gap-4 px-6 py-4"
            >
              <img
                v-if="r.track?.artwork_url"
                :src="r.track.artwork_url"
                alt=""
                class="h-14 w-14 flex-shrink-0 rounded-lg border-2 border-neutral-600 object-cover"
              />
              <div v-else class="h-14 w-14 flex-shrink-0 rounded-lg border-2 border-neutral-700 bg-neutral-800" />
              <div class="min-w-0 flex-1">
                <p class="font-bold text-neutral-200">
                  {{ r.correctValue || __(resultItemLabel) }} #{{ index + 1 }}
                </p>
                <p v-if="!r.correct && r.correctValue" class="text-sm text-neutral-400">
                  {{ __('Your answer') }}: {{ r.chosenValue || '—' }}
                </p>
              </div>
              <span
                v-if="r.correct"
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-teal-500/50 bg-teal-500/20 text-lg font-bold text-teal-400"
              >
                ✓
              </span>
              <span
                v-else
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-amber-500/50 bg-amber-500/20 text-lg font-bold text-amber-500"
              >
                ✗
              </span>
            </div>
          </div>
          <div class="flex flex-col gap-3 border-t-2 border-neutral-700/50 px-6 py-5 sm:flex-row">
            <Link
              :href="backUrl"
              class="flex-1 rounded-xl border-2 border-neutral-600 bg-neutral-800 px-4 py-3.5 text-center font-bold text-white transition hover:border-neutral-500 hover:bg-neutral-700"
            >
              {{ __('Back to mini-games') }}
            </Link>
            <Link
              :href="homeUrl"
              class="flex-1 rounded-xl border-2 border-teal-500 bg-gradient-to-r from-teal-600 to-teal-500 px-4 py-3.5 text-center font-bold text-white transition hover:from-teal-500 hover:to-teal-400"
            >
              {{ __('Back to home') }}
            </Link>
          </div>
        </div>
      </div>

      <div v-else-if="loading" class="flex flex-col items-center gap-6 py-16">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-teal-500/30 border-t-teal-500" />
        <p class="text-lg font-medium text-neutral-400">{{ __('Loading...') }}</p>
        <p class="text-sm text-neutral-500">{{ __('Preparing your 5 questions...') }}</p>
      </div>

      <div v-else-if="error" class="space-y-4 rounded-2xl border-2 border-amber-500/30 bg-neutral-900/80 p-8">
        <p class="text-amber-400">{{ error }}</p>
        <button
          type="button"
          class="rounded-xl bg-neutral-600 px-4 py-2 font-medium text-white hover:bg-neutral-500"
          @click="emit('retry')"
        >
          {{ __('Retry') }}
        </button>
      </div>

      <slot v-else />
    </div>
  </AppLayout>
</template>
