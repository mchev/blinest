<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  teams: Object,
  filters: Object,
})

const user = usePage().props.auth.user

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    router.get('/teams', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const sendRequest = (team) => {
  router.post(`/teams/${team.id}/request`)
}

const cancelRequest = (team) => {
  router.post(`/teams/${team.id}/request/cancel`)
}
</script>
<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <section class="">
      <div class="mx-auto max-w-screen-xl py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <!-- Hero Section -->
        <div class="mx-auto mb-12 max-w-3xl text-center lg:mb-16">
          <h1 class="mb-4 text-5xl font-extrabold tracking-tight sm:text-6xl">
            {{ __('Teams') }}
          </h1>
          <p class="text-xl text-gray-600">
            {{ __('Join a team and share your scores with other members to skyrocket the scores!') }}
          </p>
          <div class="mt-8 flex justify-center gap-4">
            <Link 
              v-if="!user.team" 
              href="/teams/create" 
              class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              {{ __('Create a team') }}
            </Link>
            <Link 
              v-else 
              :href="route('teams.show', user.team.id)" 
              class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-lg font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
              </svg>
              {{ __('Show my team') }}
            </Link>
          </div>
        </div>

        <!-- Search Section -->
        <div class="mx-auto mb-12 max-w-xl">
          <div class="relative">
            <TextInput 
              v-model="form.search" 
              :placeholder="__('Search a team')"
              class="w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
            />
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Teams Grid -->
        <div v-if="teams.data.length" class="relative">
          <div class="flex items-center justify-between gap-8">
            <Link 
              v-if="teams.prev_page_url" 
              :href="teams.prev_page_url"
              class="rounded-full bg-white p-2 text-gray-400 shadow-lg hover:text-indigo-600 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </Link>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
              <div 
                v-for="team in teams.data" 
                :key="team.id"
                class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:shadow-xl"
              >
                <div class="relative mb-6">
                  <div class="absolute -right-2 -top-2 rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-600">
                    {{ team.members_count }}/{{ team.seats }}
                  </div>
                  <img 
                    class="mx-auto h-32 w-32 rounded-full object-cover ring-4 ring-indigo-100 transition-all duration-300 group-hover:ring-indigo-200" 
                    :src="team.photo" 
                    :alt="team.name" 
                  />
                </div>
                
                <h3 class="mb-2 text-center text-2xl font-bold text-gray-900">
                  <Link 
                    :href="route('teams.show', team.id)"
                    class="hover:text-indigo-600 transition-colors duration-200"
                  >
                    {{ team.name }}
                  </Link>
                </h3>
                
                <p class="mb-6 text-center text-gray-600">
                  @{{ team.owner.name }}
                </p>

                <div class="flex justify-center">
                  <button 
                    v-if="user.declined_requests.includes(team.id)" 
                    type="button" 
                    @click="cancelRequest(team)" 
                    class="w-full rounded-lg bg-red-100 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-200 transition-colors duration-200"
                  >
                    {{ __('Declined request') }}
                  </button>
                  <button 
                    v-else-if="user.pending_requests.includes(team.id)" 
                    type="button" 
                    @click="cancelRequest(team)" 
                    class="w-full rounded-lg bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-600 hover:bg-yellow-200 transition-colors duration-200"
                  >
                    {{ __('Cancel join request') }}
                  </button>
                  <button 
                    v-else 
                    type="button" 
                    @click="sendRequest(team)" 
                    class="w-full rounded-lg bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-200 transition-colors duration-200"
                  >
                    {{ __('Send a join request') }}
                  </button>
                </div>
              </div>
            </div>

            <Link 
              v-if="teams.next_page_url" 
              :href="teams.next_page_url"
              class="rounded-full bg-white p-2 text-gray-400 shadow-lg hover:text-indigo-600 transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
              </svg>
            </Link>
          </div>
        </div>

        <!-- No Results -->
        <div v-else class="text-center">
          <div class="mx-auto max-w-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('No results found') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('Try adjusting your search to find what you\'re looking for.') }}</p>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>