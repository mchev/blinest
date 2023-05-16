<script setup>
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  user: Object,
})

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  photo: null,
})

const update = () => {
  form.post(route('users.update', props.user.id), {
    onSuccess: () => {
      form.reset('photo')
    }
  })
}

const deleteUser = () => {
  if(confirm('Attention, cette action est irréversible. Voulez-vous vraiment supprimer votre compte et tous les scores associés?')) {
    router.delete(route('users.destroy', props.user.id))
  }
}
</script>
<template>
  <Head :title="user.name" />
  <AppLayout>
    <div class="flex flex-wrap">
      <div class="lg:mr-4 pr-4 text-center w-full lg:w-auto mt-4">

        <figure class="mb-6 flex justify-center">
          <img :src="user.photo" :alt="user.name" class="w-60 rounded-lg" />
        </figure>

        <h2 class="text-xl font-bold">{{ user.name }}</h2>
        <p class="text-xs text-neutral-400">{{ user.email }}</p>

        <ul class="my-8">
          <li class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Registered at') }}</span>
            <span
              >{{ user.created_at }}<br><small>{{ user.created_at_from_now }}</small></span
            >
          </li>
          <li class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Id') }}</span>
            {{ user.id}}
          </li>
          <li v-if="user.latest_round_at" class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Last round played at') }}</span>
            {{ user.latest_round_at }}
          </li>
          <li v-else class="mb-4 flex flex-col">
            {{ __('No round played yet') }}
          </li>
          <li class="mb-4 flex flex-col">
            <span class="font-bold">{{ __('Score') }}</span>
            <span>{{ user.total_score }}<sup class="ml-1">{{ __('PTS') }}</sup></span>
          </li>
        </ul>

        <Link :href="route('user.profile', user)" class="btn-primary btn-sm mx-auto mb-4">{{ __('View my public profile') }}</Link>
        <Link href="/billing" class="btn-primary btn-sm mx-auto mb-4">{{ __('Manage my subscription') }}</Link>
        <button @click="deleteUser" class="btn-danger btn-sm mx-auto">{{ __('Delete my account') }}</button>
      </div>

      <div class="flex-1">
        <Card class="my-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Information') }}</h2>
          </template>
          <form id="editUserForm" @submit.prevent="update">
            <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Name')" required />
            <text-input v-model="form.email" type="email" :error="form.errors.email" class="mb-4 w-full" :label="__('Email')" required />
            <file-input v-model="form.photo" :error="form.errors.photo" class="mb-4 w-full" type="file" accept="image/*" :label="__('Photo')" />
            <text-input v-model="form.password" type="password" :error="form.errors.password" class="mb-4 w-full" :label="__('New password') + ' (' + __('Optional') + ')'" autocomplete="new-password" name="new-password" />
          </form>
          <template #footer>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="editUserForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>

        <Card class="mb-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Bookmark') }}</h2>
          </template>
          <ul class="flex flex-wrap items-center" v-if="user.bookmarked_rooms.length">
            <li v-for="room in user.bookmarked_rooms" :key="'bookmarked_rooms-' + room.id" class="badge" :class="'bg-teal-900', room.user_id === user.id">
              <Link :href="route('rooms.show', room.slug)">{{ room.name }}</Link>
            </li>
          </ul>
        </Card>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
          <Card class="">
            <template #header>
              <h2 class="text-xl font-bold">{{ __('My Playlists') }}</h2>
            </template>
            <ul class="flex flex-wrap items-center" v-if="user.playlists.length">
              <li v-for="playlist in user.playlists" :key="'playlist-' + playlist.id" class="badge" :class="'bg-teal-900', playlist.user_id === user.id">
                <Link :href="route('playlists.edit', playlist.id)">{{ playlist.name }}</Link>
              </li>
            </ul>
          </Card>

          <Card class="">
            <template #header>
              <h2 class="text-xl font-bold">{{ __('My Rooms') }}</h2>
            </template>
            <ul class="flex flex-wrap items-center" v-if="user.rooms.length">
              <li v-for="room in user.rooms" :key="'room-' + room.id" class="badge" :class="'bg-teal-900', room.user_id === user.id">
                <Link :href="route('rooms.edit', room.id)">{{ room.name }}</Link>
              </li>
            </ul>
          </Card>
        </div>

        <Card class="my-4 hidden lg:flex">
          <template #header>
            <div class="flex w-full justify-between items-center">
              <h2 class="text-xl font-bold">{{ __('Scores') }}</h2>
              <span>{{ user.total_score }}<sup class="ml-1">{{ __('PTS') }}</sup></span>
            </div>
          </template>
          <div class="overflow-x-auto relative">
            <table class="w-full whitespace-nowrap">
              <thead>
              <tr class="text-left font-bold">
                <th class="px-6 pb-4 pt-6">{{ __('Room') }}</th>
                <th class="px-6 pb-4 pt-6">{{ __('Last played game') }}</th>
                <th class="px-6 pb-4 pt-6">{{ __('Score') }}</th>
                <th class="px-6 pb-4 pt-6">{{ __('Score') }} Max</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="score in user.scores.data" :key="score.room_id">
                <td class="border-t border-neutral-500">
                  <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('rooms.show', score.room_slug)">
                    <div class="flex flex-col">
                      {{ score.name }}
                    </div>
                  </Link>
                </td>
                <td class="border-t border-neutral-500">
                  <Link class="flex items-center px-6 py-4" :href="route('rooms.show', score.room_slug)" tabindex="-1">
                    {{ score.date }}
                  </Link>
                </td>
                <td class="border-t border-neutral-500">
                  <Link class="flex items-center px-6 py-4" :href="route('rooms.show', score.room_slug)" tabindex="-1">
                    {{ score.total }}<sup class="ml-1">{{ __('PTS') }}</sup>
                  </Link>
                </td>
                <td class="border-t border-neutral-500">
                  <Link class="flex items-center px-6 py-4" :href="route('rooms.show', score.room_slug)" tabindex="-1">
                    {{ score.max }}<sup class="ml-1">{{ __('PTS') }}</sup>
                  </Link>
                </td>
              </tr>
              <tr v-if="user.scores.length === 0">
                <td class="border-t border-neutral-500 px-6 py-4" colspan="3">{{ __('No scores') }}</td>
              </tr>
            </tbody>
            </table>

            <Pagination :links="user.scores.links" />
          </div>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>