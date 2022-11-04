<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  teams: Object,
  filters: Object,
})

const user = usePage().props.value.auth.user

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    Inertia.get('/teams', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const sendRequest = (team) => {
  Inertia.post(`/teams/${team.id}/request`)
}

const cancelRequest = (team) => {
  Inertia.post(`/teams/${team.id}/request/cancel`)
}
</script>
<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <section>
      <div class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
          <h2 class="mb-4 text-4xl font-extrabold tracking-tight">{{ __('Teams') }}</h2>
          <p class="font-light sm:text-xl">Rejoins une team et partages tes scores avec les autres membres pour exploser les compteurs!</p>
          <div class="my-6 flex justify-center">
            <Link v-if="!user.team" href="/teams/create" class="btn-primary btn-lg">{{ __('Create a team') }}</Link>
            <Link v-else :href="route('teams.show', user.team.id)" class="btn-primary btn-lg">{{ __('Show my team') }}</Link>
          </div>
          <div class="mt-16 flex justify-center">
            <TextInput v-model="form.search" :placeholder="__('Search a team')" />
          </div>
        </div>
        <div class="flex items-center gap-8" v-if="teams.data.length">
          <Link v-show="teams.prev_page_url" :href="teams.prev_page_url">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor">
              <title>Previous team list</title>
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </Link>
          <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4 lg:gap-16">
            <div class="text-center" v-for="team in teams.data" :key="team.id">
              <div class="relative">
                <span class="absolute top-1 right-1 rounded-full bg-teal-500 py-1 px-2 font-bold text-white">
                  <sup>{{ team.members_count }}</sup
                  >&frasl;<sub>{{ team.seats }}</sub>
                </span>
                <img class="mx-auto mb-4 h-36 w-36 rounded-full" :src="team.photo" :alt="team.name" />
              </div>
              <h3 class="mb-1 truncate text-2xl font-bold tracking-tight">
                <Link :href="route('teams.show', team.id)">{{ team.name }}</Link>
              </h3>
              <p>@{{ team.owner.name }}</p>
              <button v-if="user.declined_requests.includes(team.id)" type="button" @click="cancelRequest(team)" class="btn-danger mx-auto my-6">{{ __('Declined request') }}</button>
              <button v-else-if="user.pending_requests.includes(team.id)" type="button" @click="cancelRequest(team)" class="btn-danger mx-auto my-6">{{ __('Cancel join request') }}</button>
              <button v-else type="button" @click="sendRequest(team)" class="btn-secondary mx-auto my-6">{{ __('Send a join request') }}</button>
            </div>
          </div>
          <Link v-show="teams.next_page_url" :href="teams.next_page_url">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" viewBox="0 0 20 20" fill="currentColor">
              <title>Next team list</title>
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </Link>
        </div>
        <div v-else>
          {{ __('No result') }}
        </div>
      </div>
    </section>
  </AppLayout>
</template>
