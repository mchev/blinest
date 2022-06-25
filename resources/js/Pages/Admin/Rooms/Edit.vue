<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import FileInput from '@/Components/FileInput'
import TextInput from '@/Components/TextInput'
import TextareaInput from '@/Components/TextareaInput'
import SelectInput from '@/Components/SelectInput'
import CheckboxInput from '@/Components/CheckboxInput'
import LoadingButton from '@/Components/LoadingButton'
import Card from '@/Components/Card'

import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

const props = defineProps({
  room: Object,
  room_playlists: Array,
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
  password: props.room.assword,
  tracks_by_round: props.room.tracks_by_round,
  track_duration: props.room.track_duration,
  pause_between_tracks: props.room.pause_between_tracks,
  pause_between_rounds: props.room.pause_between_rounds,
  photo: null,
  playlists: props.room_playlists,
})

const update = () => {
  form.post(route('admin.rooms.update', props.room.id))
}
</script>
<template>
  <Head :title="__('Edit Room')" />

  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blinest-400 hover:text-blinest-600" :href="route('rooms')">{{ __('Rooms') }}</Link>
      <span class="font-medium text-blinest-400">/</span> {{ __('Edit') }}
    </h1>

    <card>
      <form @submit.prevent="update">
        <div class="flex">
          <div class="flex w-1/3 flex-col border-r p-8">
            <p class="mb-6 font-bold">{{ __('Options') }}</p>

            <text-input v-model="form.tracks_by_round" :error="form.errors.tracks_by_round" type="number" step="1" min="1" max="100" class="pb-6" :label="__('Tracks by round')" />

            <text-input v-model="form.track_duration" :error="form.errors.track_duration" type="number" step="1" min="5" max="30" class="pb-6" :label="__('Track duration')" />

            <text-input v-model="form.pause_between_tracks" :error="form.errors.pause_between_tracks" type="number" step="1" min="0" max="60" class="pb-6" :label="__('Pause between tracks')" />

            <text-input v-model="form.pause_between_rounds" :error="form.errors.pause_between_rounds" type="number" step="1" min="0" max="60" class="pb-6" :label="__('Pause between rounds')" />

            <text-input v-model="form.color" type="color" :error="form.errors.color" class="pb-6" :label="__('Color')" />

            <checkbox-input v-model="form.is_public" :error="form.errors.is_public" class="pb-6" :label="__('Public')" />
            <checkbox-input v-model="form.is_pro" :error="form.errors.is_pro" class="pb-6" :label="__('Pro')" />
            <checkbox-input v-model="form.is_random" :error="form.errors.is_random" class="pb-6" :label="__('Random')" />
            <checkbox-input v-model="form.is_active" :error="form.errors.is_active" class="pb-6" :label="__('Active')" />
            <checkbox-input v-model="form.is_chat_active" :error="form.errors.is_chat_active" class="pb-6" :label="__('Chatbox')" />
            <checkbox-input v-model="form.is_password" class="pb-6" :label="__('Password')" />

            <text-input v-show="form.is_password" v-model="form.password" :error="form.errors.password" class="pb-6" type="password" autocomplete="new-password" :label="__('Password')" />
          </div>

          <div class="flex flex-wrap p-8">
            <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6" :label="__('Name')" />
            <textarea-input v-model="form.description" :error="form.errors.description" class="w-full pb-8 pr-6" :label="__('Description')" />
            <select-input v-model="form.category_id" :error="form.errors.category_id" class="w-full pb-8 pr-6" :label="__('Category')">
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select-input>
            <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6" type="file" accept="image/*" :label="__('Photo')" />
            <text-input v-model="form.discord_webhook_url" type="url" :error="form.errors.discord_webhook_url" class="w-full pb-8 pr-6" :label="__('Discord Webhook')" />
            <select-input v-model="form.playlists" multiple :error="form.errors.playlists" class="w-full pb-8 pr-6" :label="__('Playlists')">
              <option v-for="playlist in available_playlists" :key="playlist.id" :value="playlist.id">
                {{ playlist.name }}
              </option>
            </select-input>
          </div>
        </div>

        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Update') }}</loading-button>
        </div>
      </form>
    </card>
  </AdminLayout>
</template>
