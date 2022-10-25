<script setup>
import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import Share from '@/Components/Share.vue'

const props = defineProps({
  team: Object,
  score: Number,
  members: Object,
  user: Object,
})

const memberList = Object.values(props.members).sort((a, b) => b.score - a.score)

const form = useForm({
  _method: 'put',
  name: props.team.name,
  photo: null
})

const leave = () => {
  if (confirm('Are you sure?')) {
    Inertia.post(`/teams/${props.team.id}/leave`)
  }
}

const sendRequest = (team) => {
  Inertia.post(`/teams/${team.id}/request`)
}

const cancelRequest = (team) => {
  Inertia.post(`/teams/${team.id}/request/cancel`)
}

const switchOwner = (member) => {
  if (confirm('Veux-tu vraiment transferer la gestion de la team à ce membre?')) {
    Inertia.post(`/teams/${props.team.id}/owner/${member.id}`, {
      preserveState: false,
    })
  }
}

const updateTeam = () => {
  form.post(route('teams.update', props.team.id), {
    preserveScroll: true
  })
}

</script>
<template>
  <Head :title="team.name" />
  <AppLayout>
    <div class="mx-auto mt-8 max-w-xl">
      <Card class="my-8" v-if="user.id === team.user_id">
        <form @submit.prevent="updateTeam">
          <TextInput v-model="form.name" :label="__('Name')" class="mb-4" :error="form.errors.name"/>
          <FileInput v-model="form.photo" :label="__('Image')" class="mb-4" :error="form.errors.photo"/>
          <LoadingButton type="submit" @click="updateTeam" :loading="form.processing" class="btn-primary mb-4 ml-auto">{{ __('Update') }}</LoadingButton>
        </form>
      </Card>
      <div class="flex items-center justify-between text-xl">
        <div class="mb-6 flex items-center">
          <img v-if="team.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="team.photo" />
          <h1 class="font-bold">Team {{ team.name }}</h1>
        </div>
        <span>{{ score }}<sup class="ml-1">PTS</sup></span>
      </div>
      <Card>
        <template #header>
          <div class="mx-2 flex w-full items-center justify-between">
            <div class="flex items-center">
              {{ __('Members') }}
            </div>
            <span>{{ Object.entries(members).length }} / {{ team.seats }}</span>
          </div>
        </template>
        <ul>
          <li v-for="(member, index) in memberList" :key="member.id" class="m-2 flex items-center rounded bg-neutral-900 p-4">
            <div class="px-4 text-xl font-bold">
              {{ Number(index) + 1 }}
            </div>
            <div class="flex flex-grow items-center pr-4">
              <img v-if="member.photo" class="-my-2 mr-2 block h-8 w-8 rounded-full" :src="member.photo" />
              {{ member.name }}
              <span v-if="member.id === team.user_id" class="ml-2"
                ><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 fill-yellow-500">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                </svg>
              </span>
              <button @click="switchOwner(member)" v-if="user.id === team.user_id && member.id != team.user_id" class="group ml-2" :title="__('Switch owner')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 group-hover:fill-yellow-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
              </button>
            </div>
            <div>{{ member.score }}<sup>PTS</sup></div>
          </li>
        </ul>
      </Card>
      <div class="my-6 flex items-center gap-6">
        <button v-if="Object.values(members).find((x) => x.id === user.id)" type="button" class="btn-danger" @click="leave">{{ __('Leave the team') }}</button>
        <div v-else>
          <button v-if="user.declined_requests.includes(team.id)" type="button" @click="cancelRequest(team)" class="btn-danger mx-auto my-6">{{ __('Declined request') }}</button>
          <button v-else-if="user.pending_requests.includes(team.id)" type="button" @click="cancelRequest(team)" class="btn-danger mx-auto my-6">{{ __('Cancel join request') }}</button>
          <button v-else type="button" @click="sendRequest(team)" class="btn-secondary mx-auto my-6">{{ __('Send a join request') }}</button>
        </div>
        <Share :url="route('teams.show', team)" />
      </div>
    </div>
  </AppLayout>
</template>
