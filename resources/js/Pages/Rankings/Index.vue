<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  bestUsers: Object,
  bestTeams: Object,
})
</script>
<template>
  <Head :title="__('Rankings') + ' - Top 50'" />
  <AppLayout>
    <section>
      <div class="mx-auto py-8 px-4 text-center">
        <div class="mx-auto mb-8 lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold">Top 50</h2>
          <p>public rooms</p>
        </div>
        <div>
          <section class="mb-8">
            <ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in bestUsers" :key="score.user.id" class="m-4 flex flex-col items-center">
                <div class="relative">
                  <span class="absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700">{{ index + 1 }}</span>
                  <img :src="score.user.photo" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <span class="mb-1 font-bold">{{ score.user.name }}</span>
                <span>{{ score.total_score }}<sup>PTS</sup></span>
              </li>
            </ul>
          </section>
          <section class="mb-4">
            <h3 class="mb-4 text-3xl font-bold">{{ __('Teams') }}</h3>
            <ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in bestTeams" :key="score.team.id">
                <Link :href="route('teams.show', score.team.id)" class="m-4 flex flex-col items-center">
                  <div class="relative">
                    <span class="absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700">{{ index + 1 }}</span>
                    <img :src="score.team.photo" class="mb-2 h-20 w-20 rounded-full" />
                  </div>
                  <span class="mb-1 font-bold">{{ score.team.name }}</span>
                  <span>{{ score.total_score }}<sup>PTS</sup></span>
                </Link>
              </li>
            </ul>
          </section>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
