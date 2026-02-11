<script setup>
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import RankingTabs from './partials/RankingTabs.vue'
import RankingList from './partials/RankingList.vue'
import UserPosition from './partials/UserPosition.vue'
import Pagination from '@/Components/Pagination.vue'

const page = usePage()
const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })
  return translation
}

const props = defineProps({
  topByMinigames: Object,
  userPosition: Number,
  userScore: {
    type: Number,
    default: 0,
  },
})
</script>

<template>
  <Head :title="__('Rankings') + ' - ' + __('Mini-games')" />
  <AppLayout>
    <section>
      <div class="mx-auto max-w-5xl py-4 px-4 sm:py-8">
        <div class="mx-auto mb-6 text-center sm:mb-8">
          <h1 class="mb-2 text-3xl font-extrabold bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 bg-clip-text text-transparent sm:mb-4 sm:text-5xl">
            {{ __('Rankings') }}
          </h1>
          <p class="text-sm text-neutral-400 sm:text-lg">{{ __('Compete with the best players') }}</p>
        </div>

        <!-- Tabs Navigation -->
        <RankingTabs />

        <!-- User Position -->
        <UserPosition
          v-if="userPosition"
          :position="userPosition"
          :score="userScore"
          type="minigames"
        />

        <!-- Ranking List -->
        <div v-if="topByMinigames && topByMinigames.data && topByMinigames.data.length > 0" class="mt-6">
          <RankingList :items="topByMinigames" type="minigames" />
          <Pagination :links="topByMinigames.links" />
        </div>
        <div v-else class="py-16 text-center">
          <p class="text-neutral-400">{{ __('No rankings available') }}</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
