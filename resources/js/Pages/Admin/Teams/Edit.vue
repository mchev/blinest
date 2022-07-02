<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TrashedMessage from '@/Components/TrashedMessage.vue'

const props = defineProps({
  team: Object,
})

const form = useForm({
  _method: 'put', // required to post files
  name: props.team.name,
  user_id: props.team.user_id,
  photo: null,
})

const update = () => {
  form.post(`/admin/teams/${props.team.id}`)
}

const destroy = () => {
  if (confirm('Are you sure you want to delete this team?')) {
    Inertia.delete(`/admin/teams/${props.team.id}`)
  }
}

const restore = () => {
  if (confirm('Are you sure you want to restore this team?')) {
    Inertia.put(`/admin/teams/${props.team.id}/restore`)
  }
}
</script>
<template>
  <Head :title="form.name" />
  <AdminLayout>
    <div class="mb-8 flex items-center">
      <img v-if="team.photo" class="mr-4 block h-8 w-8 rounded-full" :src="team.photo" />
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/admin/teams">Teams</Link>
        <span class="font-medium text-indigo-400">/</span>
        {{ form.name }}
      </h1>
    </div>
    <trashed-message v-if="team.deleted_at" class="mb-6" @restore="restore"> This team has been deleted. </trashed-message>
    <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
      <form @submit.prevent="update">
        <div class="-mb-8 -mr-6 flex flex-wrap p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
          <select-input v-model="form.user_id" :error="form.errors.user_id" class="w-full pb-8 pr-6 lg:w-1/2" label="Owner">
            <option v-for="member in team.members" :key="member.id" :value="member.id">{{ member.name }}</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6" type="file" accept="image/*" :label="__('Photo')" />
        </div>
        <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
          <button v-if="!team.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Team</button>
          <loading-button :loading="form.processing" class="btn-primary ml-auto" type="submit">Update Team</loading-button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
