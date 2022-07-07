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
      <div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-white">{{ __('Scores') }}</h2>
        </div>
        <div>
          <section class="mb-4">
            <h3 class="mb-4 text-3xl font-bold text-white">{{ __('Best players all categories') }}</h3>
            <ul class="flex items-center justify-center">
              <li v-for="(user, index) in bestUsers" :key="user.id" class="flex flex-col items-center m-4">
                <div class="relative">
                  <span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2">{{ index + 1}}</span>
                  <img :src="user.photo" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <span class="mb-1 font-bold">{{ user.name }}</span>
                <span>{{ user.score }}<sup>PTS</sup></span>
              </li>
            </ul>
          </section>
          <section class="mb-4">
            <h3 class="mb-4 text-3xl font-bold text-white">{{ __('Best teams all categories') }}</h3>
            <ul class="flex items-center justify-center">
              <li v-for="(team, index) in bestTeams" :key="team.id" class="flex flex-col items-center m-4">
                <div class="relative">
                  <span class="p-1 bg-neutral-100 rounded-full h-8 w-8 flex items-center justify-center text-neutral-700 absolute -left-2 -top-2">{{ index + 1}}</span>
                  <img :src="team.photo" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <span class="mb-1 font-bold">{{ team.name }}</span>
                <span>{{ team.score }}<sup>PTS</sup></span>
              </li>
            </ul>
          </section>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
