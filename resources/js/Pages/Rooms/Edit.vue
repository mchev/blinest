<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, usePage, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import ModeratorsManager from '@/Components/Rooms/ModeratorsManager.vue'
import PlaylistsManager from '@/Components/Rooms/PlaylistsManager.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import EditOptionsForm from './partials/EditOptionsForm.vue'

const props = defineProps({
  room: Object,
  categories: Array,
  available_playlists: Array,
})

const user = usePage().props.auth.user

const form = useForm({
  _method: 'put',
  name: props.room.name,
  description: props.room.description,
  category_id: props.room.category_id,
  is_public: props.room.is_public,
  is_pro: props.room.is_pro,
  is_random: props.room.is_random,
  is_active: props.room.is_active,
  is_chat_active: props.room.is_chat_active,
  is_autostart: props.room.is_autostart,
  discord_webhook_url: props.room.discord_webhook_url,
  color: props.room.color,
  has_password: props.room.password ? true : false,
  password: props.room.password,
  tracks_by_round: props.room.tracks_by_round,
  track_duration: props.room.track_duration,
  pause_between_tracks: props.room.pause_between_tracks,
  pause_between_rounds: props.room.pause_between_rounds,
  photo: null,
  playlists: props.room_playlists,
})

const update = () => {
  form.post(route('rooms.update', props.room.id))
}

const deleteRoom = () => {
  if (confirm('Voules-vous vraiment supprimer cette room ?')) {
    router.delete(route('rooms.destroy', props.room.id))
  }
}

onUnmounted(() => {
  Echo.leave(`rooms.${props.room.id}`)
})
</script>
<template>
  <Head :title="__('Edit Room')" />

  <AppLayout>
    <h1 class="mb-8 flex items-center gap-2 text-3xl font-bold">
      <Link :href="route('rooms.index')">{{ __('Rooms') }}</Link> / {{ room.name }}
      <Link class="btn-primary ml-auto" :href="route('rooms.show', room.slug)">{{ __('Play') }}</Link>
    </h1>

    <div class="flex flex-wrap gap-4">
      <div class="flex w-full flex-col xl:w-1/4">
        <Card class="mb-4">
          <template #header>
            <h3 class="text-xl font-bold">{{ __('Room') }}</h3>
          </template>
          <form @submit.prevent="update" id="roomForm">
            <div class="flex">
              <div class="flex flex-wrap gap-4">
                <text-input v-model="form.name" :error="form.errors.name" class="w-full" :label="__('Name')" />
                <textarea-input v-model="form.description" :error="form.errors.description" class="w-full" :label="__('Description')" />
                <select-input v-model="form.category_id" :error="form.errors.category_id" class="w-full" :label="__('Category')">
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select-input>
                <file-input v-if="room.is_pro || room.is_public || user.can.changeRoomPicture" v-model="form.photo" :error="form.errors.photo" class="w-full" type="file" accept="image/*" :label="__('Photo')" />
                <Link as="button" v-if="room.photo_path" :href="route('rooms.picture.delete', room.id)" method="delete" class="text-sm text-red-500 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                  {{ __('Remove image') }}
                </Link>
                <img v-if="room.photo" :src="room.photo" class="rounded max-h-30 max-w-full" :alt="room.name">
                <Tip v-if="!room.is_pro && !room.is_public && !user.can.changeRoomPicture" class="bg-red-800 text-white"> {{ __('In order to change room picture, you need to have a minimum of three months of seniority and a total score above two thousand') }} </Tip>
              </div>
            </div>
          </form>
          <template #footer>
            <button v-if="!room.deleted_at" class="text-sm text-red-500 hover:underline" tabindex="-1" type="button" @click="deleteRoom">{{ __('Delete') }}</button>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="roomForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>
        <ModeratorsManager :room="room" />
      </div>

      <div class="flex-1">
        <Card class="mb-4">
          <template #header>
            <h2 class="text-xl font-bold">{{ __('Options') }}</h2>
          </template>
          <EditOptionsForm :room="room" />
        </Card>
        <PlaylistsManager v-if="user.id === room.user_id" :room="room" :playlists="available_playlists" />
      </div>
    </div>
  </AppLayout>
</template>
