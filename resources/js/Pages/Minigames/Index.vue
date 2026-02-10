<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'

defineProps({
  games: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <Head :title="__('Mini-games')" />
  <AppLayout>
    <div class="mx-auto max-w-3xl px-4 py-8">
        <Link
          :href="route('home')"
          class="mb-6 inline-flex items-center gap-2 text-sm text-neutral-400 transition hover:text-white"
        >
          <Icon name="cheveron-right" class="h-4 w-4 rotate-180" />
          {{ __('Back') }}
        </Link>

        <header class="relative mb-10 overflow-hidden rounded-2xl border-2 border-teal-500/20 bg-gradient-to-br from-teal-600/25 via-neutral-800/90 to-amber-600/15 px-8 py-10 shadow-2xl shadow-teal-500/10 ring-1 ring-white/5">
          <div class="relative">
            <h1 class="text-3xl font-black uppercase tracking-widest text-white drop-shadow-lg sm:text-4xl">
              {{ __('Mini-games') }}
            </h1>
            <p class="mt-3 max-w-xl text-neutral-300">
              {{ __('Solo games to train your music knowledge.') }}
            </p>
          </div>
        </header>

        <ul class="flex flex-col gap-5">
          <li v-for="game in games" :key="game.type">
            <Link
              :href="game.play_url"
              class="group block overflow-hidden rounded-2xl border-2 border-neutral-700/60 bg-neutral-800/70 shadow-xl ring-1 ring-neutral-600/30 transition-all duration-200 hover:-translate-y-1 hover:border-teal-500/50 hover:shadow-[0_0_40px_rgba(20,184,166,0.15)] hover:ring-teal-500/30"
            >
              <div class="flex flex-row items-center gap-5 p-6">
                <div
                  class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-xl border-2 border-teal-500/30 bg-gradient-to-br from-teal-500/40 to-cyan-500/30 text-3xl shadow-[0_0_20px_rgba(20,184,166,0.2)]"
                  aria-hidden="true"
                >
                  ▶
                </div>
                <div class="min-w-0 flex-1">
                  <h2 class="text-xl font-black text-neutral-100 transition-colors group-hover:text-teal-400">
                    {{ game.name }}
                  </h2>
                  <p class="mt-1 text-sm text-neutral-400">
                    {{ game.description }}
                  </p>
                  <p
                    v-if="game.score !== undefined && game.score > 0"
                    class="mt-2 inline-flex items-center rounded-full border border-teal-500/40 bg-teal-500/20 px-3 py-1 text-sm font-bold text-teal-400"
                  >
                    {{ __('Score') }}: {{ game.score }} {{ __('points') }}
                  </p>
                </div>
                <Icon name="cheveron-right" class="h-6 w-6 flex-shrink-0 text-neutral-500 transition-colors group-hover:text-teal-400" />
              </div>
            </Link>
          </li>
        </ul>
    </div>
  </AppLayout>
</template>
