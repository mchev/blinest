<script setup>
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import RankingTabs from './partials/RankingTabs.vue'

const page = usePage()
const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (key) {
    translation = translation.replace(':' + key, replace[key])
  })
  return translation
}
</script>

<template>
  <AppLayout>
    <section>
      <div class="mx-auto px-4 py-8">
        <div class="mx-auto mb-8 text-center">
          <h1 class="mb-4 bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 bg-clip-text text-5xl font-extrabold text-transparent">
            {{ __('Rankings') }}
          </h1>
          <p class="text-lg text-neutral-400">{{ __('Compete with the best players') }}</p>
        </div>

        <!-- Tabs Navigation -->
        <RankingTabs />
      </div>
    </section>
  </AppLayout>
</template>
