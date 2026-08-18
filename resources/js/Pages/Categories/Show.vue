<script setup>
import { onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Room from '@/Pages/Home/partials/Room.vue'

defineProps({
  category: {
    type: Object,
    required: true,
  },
  content: {
    type: Object,
    required: true,
  },
  rooms: {
    type: Array,
    default: () => [],
  },
  roomsCount: {
    type: Number,
    default: 0,
  },
})

onMounted(() => {
  document.getElementById('seo-landing-server')?.remove()
})
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-6xl space-y-8 px-4 py-6 sm:py-10">
      <header class="space-y-4">
        <nav class="text-sm text-neutral-500" aria-label="Breadcrumb">
          <ol class="flex flex-wrap items-center gap-2">
            <li>
              <Link :href="route('home')" class="transition-colors hover:text-yellow-400">{{ __('Home') }}</Link>
            </li>
            <li aria-hidden="true">/</li>
            <li class="text-neutral-300">{{ __(category.name) }}</li>
          </ol>
        </nav>

        <div class="space-y-3">
          <h1 class="text-3xl font-extrabold text-white sm:text-4xl">
            {{ content.heading }}
          </h1>
          <p class="max-w-3xl text-base leading-relaxed text-neutral-300 sm:text-lg">
            {{ content.intro }}
          </p>
          <p class="max-w-3xl text-sm leading-relaxed text-neutral-400 sm:text-base">
            {{ content.intro_secondary }}
          </p>
        </div>
      </header>

      <section class="space-y-4" aria-labelledby="category-rooms-heading">
        <div class="flex flex-wrap items-end justify-between gap-3">
          <h2 id="category-rooms-heading" class="text-xl font-bold text-white sm:text-2xl">
            {{ content.rooms_heading }}
          </h2>
          <p class="text-sm text-neutral-500">
            {{ __(':count rooms', { count: roomsCount }) }}
          </p>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 xl:grid-cols-4">
          <Room v-for="room in rooms" :key="room.id" :room="room" variant="catalog" />
        </div>
      </section>

      <section class="rounded-2xl border border-neutral-800 bg-neutral-900/50 p-5 sm:p-6" aria-labelledby="category-faq-heading">
        <h2 id="category-faq-heading" class="mb-4 text-xl font-bold text-white">
          {{ __('Category page FAQ title', { category: __(category.name) }) }}
        </h2>
        <dl class="space-y-4">
          <div v-for="(item, index) in content.faq" :key="index" class="border-b border-neutral-800 pb-4 last:border-b-0 last:pb-0">
            <dt class="font-semibold text-white">{{ item.question }}</dt>
            <dd class="mt-2 text-sm leading-relaxed text-neutral-400 sm:text-base">{{ item.answer }}</dd>
          </div>
        </dl>
      </section>

      <section class="flex flex-col gap-3 rounded-2xl border border-yellow-500/20 bg-yellow-500/5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
        <div class="space-y-1">
          <h2 class="text-lg font-bold text-white">{{ __('Category page rankings CTA title') }}</h2>
          <p class="text-sm text-neutral-400">{{ __('Category page rankings CTA body', { category: __(category.name) }) }}</p>
        </div>
        <Link :href="route('rankings.index')" class="game-btn-secondary inline-flex shrink-0 justify-center">
          {{ __('View rankings') }}
        </Link>
      </section>
    </div>
  </AppLayout>
</template>
