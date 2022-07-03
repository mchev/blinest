<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  teams: Object,
  user: Object,
})
</script>
<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <section>
      <div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ __('Teams') }}</h2>
          <p class="font-light text-gray-500 dark:text-gray-400 sm:text-xl">Rejoins une team et partages tes scores avec les autres membres pour exploser les compteurs!</p>
          <div class="flex justify-center my-6">
            <Link href="/teams/create" class="btn-primary btn-lg ">{{ __('Create a team') }}</Link>
          </div>
          <div class="flex justify-center mt-16">
            <TextInput v-model="search" :placeholder="__('Search a team')"/>
          </div>
        </div>
        <div class="flex items-center gap-8">
          <Link v-show="teams.prev_page_url" :href="teams.prev_page_url">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor">
              <title>Previous team list</title>
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </Link>
          <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 lg:gap-16">
            <div class="text-center text-gray-500 dark:text-gray-400" v-for="team in teams.data" :key="team.id">
              <img class="mx-auto mb-4 h-36 w-36 rounded-full" :src="team.photo" :alt="team.name" />
              <h3 class="mb-1 truncate text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                <a href="#">{{ team.name }}</a>
              </h3>
              <p>@{{ team.owner.name }}</p>
              {{ team.members_count }} / {{ team.seats }}
              <Link href="/teams/create" class="btn-secondary my-6">{{ __('Send a join request') }}</Link>
            </div>
          </div>
          <Link v-show="teams.next_page_url" :href="teams.next_page_url">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor">
              <title>Next team list</title>
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
