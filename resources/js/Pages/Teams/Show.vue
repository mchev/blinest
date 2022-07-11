<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  team: Object,
  score: Number,
  members: Object,
})

const leave = () => {
  if(confirm('Are you sure?')) {
    Inertia.post(`/teams/${props.team.id}/leave`);
  }
}

</script>
<template>
  <Head :title="team.name" />
  <AppLayout>
    <Card class="mx-auto max-w-xl">
      <template #header>
        <div class="flex justify-between items-center w-full">
          <div class="flex items-center">
            <img v-if="team.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="team.photo" />
            <h1 class="text-xl font-bold">{{ team.name }} ({{ score }}<sup>PTS</sup>)</h1>
          </div>
          <span>{{ members.length }} / {{ team.seats }}</span>
        </div>
      </template>
      <ul>
        <li v-for="member in members" :key="member.id" class="flex items-center m-2 p-2 border-b">
          <img v-if="member.photo" class="-my-2 mr-2 block h-8 w-8 rounded-full" :src="member.photo" />
          {{ member.name }} ({{ member.score }}<sup>PTS</sup>) 
          <span v-if="member.id === team.user_id" class="ml-2 rounded bg-neutral-200 px-2 py-1">{{ __('Owner') }}</span>
        </li>
      </ul>
      <template #footer>
        <button type="button" class="text-red-600" @click="leave">{{ __('Leave the team') }}</button>
      </template>
    </Card>
  </AppLayout>
</template>
