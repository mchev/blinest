<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import CheckboxInput from '@/Components/CheckboxInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import ModeratorsManager from '@/Components/Rooms/ModeratorsManager.vue'
import PlaylistsManager from '@/Components/Rooms/PlaylistsManager.vue'
import Card from '@/Components/Card.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

const props = defineProps({
  room: Object,
  categories: Array,
  available_playlists: Array,
})

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
  discord_webhook_url: props.room.discord_webhook_url,
  color: props.room.color,
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
</script>
<template>
  <Head :title="__('Edit Room')" />

  <AppLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link :href="route('rooms.index')">{{ __('Rooms') }}</Link> / {{ room.name }}
    </h1>

    <div class="flex flex-wrap gap-4">
      <div class="flex w-full flex-col xl:w-1/4">
        <Card class="mb-4">
          <template #header>
            <h3 class="text-xl font-bold">{{ __('Room') }}</h3>
          </template>
          <form @submit.prevent="update" id="roomForm">
            <div class="flex">
              <div class="flex flex-wrap">
                <text-input v-model="form.name" :error="form.errors.name" class="mb-4 w-full" :label="__('Name')" />
                <textarea-input v-model="form.description" :error="form.errors.description" class="mb-4 w-full" :label="__('Description')" />
                <select-input v-model="form.category_id" :error="form.errors.category_id" class="mb-4 w-full" :label="__('Category')">
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select-input>
                <file-input v-model="form.photo" :error="form.errors.photo" class="mb-4 w-full" type="file" accept="image/*" :label="__('Photo')" />
              </div>
            </div>
          </form>
          <template #footer>
            <button v-if="!room.deleted_at" class="text-sm text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
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
          <form @submit.prevent="update" id="optionsForm" class="flex flex-wrap">
            <div class="flex w-full flex-wrap">
              <text-input v-model="form.tracks_by_round" :error="form.errors.tracks_by_round" type="number" step="1" min="1" max="100" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Tracks by round')" />

              <text-input v-model="form.track_duration" :error="form.errors.track_duration" type="number" step="1" min="5" max="30" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Track duration')" />

              <text-input v-model="form.pause_between_tracks" :error="form.errors.pause_between_tracks" type="number" step="1" min="0" max="60" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Pause between tracks')" />

              <text-input v-model="form.pause_between_rounds" :error="form.errors.pause_between_rounds" type="number" step="1" min="0" max="60" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Pause between rounds')" />
            </div>

            <div class="flex w-full flex-wrap">
              <checkbox-input v-model="form.is_chat_active" :error="form.errors.is_chat_active" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Chatbox')" />
              <checkbox-input v-model="form.password" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Password')" />
            </div>

            <text-input v-show="form.password" v-model="form.password" :error="form.errors.password" class="pb-6" type="password" autocomplete="new-password" :label="__('Password')" />
          </form>

          <template #footer>
            <loading-button :loading="form.processing" class="btn-primary ml-auto" form="optionsForm" type="submit">{{ __('Update') }}</loading-button>
          </template>
        </Card>
        <PlaylistsManager :room="room" :playlists="available_playlists" />
      </div>
    </div>
  </AppLayout>
</template>
