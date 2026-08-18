<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Room from '@/Pages/Home/partials/Room.vue'

defineProps({
  room: {
    type: Object,
    required: true,
  },
  seo: {
    type: Object,
    required: true,
  },
})

const page = usePage()

function t(key, replace = {}) {
  let translation = page.props.language?.[key] ?? key

  Object.entries(replace).forEach(([placeholder, value]) => {
    translation = translation.replace(`:${placeholder}`, String(value))
  })

  return translation
}
</script>

<template>
  <Card id="room-seo" class="room-seo-panel mt-8">
    <div class="space-y-6">
      <nav class="text-sm text-white/50" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-2">
          <li v-for="(crumb, index) in seo.breadcrumbs" :key="`${crumb.label}-${index}`" class="flex items-center gap-2">
            <Link v-if="crumb.href" :href="crumb.href" class="transition-colors hover:text-brand-secondary">{{ crumb.label }}</Link>
            <span v-else class="text-white/80">{{ crumb.label }}</span>
            <span v-if="index < seo.breadcrumbs.length - 1" aria-hidden="true">/</span>
          </li>
        </ol>
      </nav>

      <header class="space-y-3">
        <h2 class="text-lg font-bold text-white md:text-xl">{{ t('About this room', { room: room.name }) }}</h2>
        <p class="text-sm leading-relaxed text-white/80 md:text-base">{{ seo.content.intro }}</p>
        <p class="text-sm leading-relaxed text-white/60">{{ seo.content.intro_secondary }}</p>
      </header>

      <dl class="grid grid-cols-2 gap-3 sm:grid-cols-3">
        <div class="rounded-lg border border-white/10 bg-brand-midnight/80 px-3 py-2.5">
          <dt class="text-xs uppercase tracking-wide text-white/50">{{ t('Tracks') }}</dt>
          <dd class="text-lg font-bold text-white">{{ seo.stats.tracks }}</dd>
        </div>
        <div class="rounded-lg border border-white/10 bg-brand-midnight/80 px-3 py-2.5">
          <dt class="text-xs uppercase tracking-wide text-white/50">{{ t('Rounds played') }}</dt>
          <dd class="text-lg font-bold text-white">{{ seo.stats.rounds }}</dd>
        </div>
        <div class="col-span-2 rounded-lg border border-white/10 bg-brand-midnight/80 px-3 py-2.5 sm:col-span-1">
          <dt class="text-xs uppercase tracking-wide text-white/50">{{ t('Players online') }}</dt>
          <dd class="text-lg font-bold text-white">{{ seo.stats.players_online }}</dd>
        </div>
      </dl>

      <section v-if="seo.similar_rooms?.length" class="space-y-3" aria-labelledby="similar-rooms-heading">
        <h3 id="similar-rooms-heading" class="text-sm font-medium uppercase tracking-wider text-brand-secondary">{{ t('Similar official rooms') }}</h3>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
          <Room v-for="similar in seo.similar_rooms" :key="similar.id" :room="similar" variant="catalog" />
        </div>
      </section>

      <section class="rounded-xl border border-white/10 bg-brand-midnight/40 p-4" aria-labelledby="room-faq-heading">
        <h3 id="room-faq-heading" class="mb-3 text-base font-bold text-white">{{ t('Room page FAQ title', { room: room.name }) }}</h3>
        <dl class="space-y-3">
          <div v-for="(item, index) in seo.content.faq" :key="index">
            <dt class="text-sm font-semibold text-white">{{ item.question }}</dt>
            <dd class="mt-1 text-sm leading-relaxed text-white/60">{{ item.answer }}</dd>
          </div>
        </dl>
      </section>

      <div class="flex flex-wrap gap-3">
        <Link :href="route('rankings.index')" class="game-btn-secondary inline-flex">{{ t('View rankings') }}</Link>
      </div>
    </div>
  </Card>
</template>
