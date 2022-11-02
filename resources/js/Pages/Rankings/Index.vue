<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  bestUsers: Object,
  bestTeams: Object,
})
</script>
<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <section>
      <div class="mx-auto py-8 px-4 text-center">
        <div class="mx-auto mb-8 lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold">{{ __('Best scores in public rooms') }}</h2>
          <p class="text-sm">Les scores sur cette page sont actualisés toutes les 10 minutes.</p>
        </div>
        <div>
          <section class="mb-4">
            <h3 class="mb-4 text-3xl font-bold">{{ __('Players') }}</h3>
            <ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in bestUsers.data" :key="score.user.id" class="flex flex-col items-center m-4">
                <div class="relative">
                  <span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2">{{ index + 1}}</span>
                  <img :src="score.user.photo" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <span class="mb-1 font-bold">{{ score.user.name }}</span>
                <span>{{ score.total }}<sup>PTS</sup></span>
              </li>
            </ul>
          </section>
          <section class="mb-4">
            <h3 class="mb-4 text-3xl font-bold">{{ __('Teams') }}</h3>
            <ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in bestTeams.data" :key="score.team.id" class="flex flex-col items-center m-4">
                <div class="relative">
                  <span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2">{{ index + 1}}</span>
                  <img :src="score.team.photo" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <span class="mb-1 font-bold">{{ score.team.name }}</span>
                <span>{{ score.total }}<sup>PTS</sup></span>
              </li>
            </ul>
          </section>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
