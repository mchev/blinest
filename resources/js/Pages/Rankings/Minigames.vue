<script setup>
import { Deferred } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import RankingTabs from './partials/RankingTabs.vue'
import RankingList from './partials/RankingList.vue'
import UserPosition from './partials/UserPosition.vue'
import Pagination from '@/Components/Pagination.vue'

defineProps({
  topByMinigames: Object,
  userContext: {
    type: Object,
    default: null,
  },
})
</script>

<template>
  <AppLayout>
    <section>
      <div class="mx-auto max-w-5xl px-4 py-4 sm:py-8">
        <div class="mx-auto mb-6 text-center sm:mb-8">
          <h1 class="mb-2 bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 bg-clip-text text-3xl font-extrabold text-transparent sm:mb-4 sm:text-5xl">
            {{ __('Rankings') }}
          </h1>
          <p class="text-sm text-neutral-400 sm:text-lg">{{ __('Compete with the best players') }}</p>
        </div>

        <RankingTabs />

        <Deferred data="userContext">
          <template #fallback>
            <div class="mt-6 h-16 animate-pulse rounded-xl border border-white/10 bg-white/5" />
          </template>
          <UserPosition v-if="userContext?.position" :position="userContext.position" :score="userContext.score" type="minigames" />
        </Deferred>

        <div v-if="topByMinigames && topByMinigames.data && topByMinigames.data.length > 0" class="mt-6">
          <RankingList :items="topByMinigames" type="minigames" />
          <Pagination :links="topByMinigames.links" />
        </div>
        <div v-else class="py-16 text-center">
          <p class="text-neutral-400">{{ __('No rankings yet') }}</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
