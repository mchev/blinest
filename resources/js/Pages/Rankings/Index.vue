<script setup>
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import RankingTabs from './partials/RankingTabs.vue'
import EzoicAd from '@/Components/EzoicAd.vue'
import { EZOIC } from '@/ezoic'

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
  <Head :title="__('Rankings')" />
  <AppLayout>
    <section>
      <div class="mx-auto py-8 px-4">
        <div class="mx-auto mb-8 text-center">
          <h1 class="mb-4 text-5xl font-extrabold bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 bg-clip-text text-transparent">
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
