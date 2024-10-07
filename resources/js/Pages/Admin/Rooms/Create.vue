<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FileInput from '@/Components/FileInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import CheckboxInput from '@/Components/CheckboxInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

const form = useForm({
  name: '',
  description: '',
  is_public: false,
  is_pro: false,
  is_random: true,
  is_active: true,
  is_chat_active: true,
  discord_webhook_url: '',
  color: '',
  password: '',
  tracks_by_round: 15,
  track_duration: 30,
  pause_between_tracks: 0,
  pause_between_rounds: 10,
  photo: null,
})

const store = () => {
  form.post(route('admin.rooms.store'))
}
</script>
<template>
  <Head title="Create Room" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-gray-400 hover:text-blinest-600" :href="route('admin.rooms')">{{ __('Rooms') }}</Link>
      <span class="font-medium text-gray-400">/</span> {{ __('Create') }}
    </h1>

    <card>
      <form @submit.prevent="store">
        <div class="flex">
          <div class="flex w-1/3 flex-col border-r p-8">
            <p class="mb-6 font-bold">{{ __('Options') }}</p>

            <text-input v-model="form.tracks_by_round" :error="form.errors.tracks_by_round" type="number" step="1" min="1" max="50" class="pb-6" :label="__('Tracks by round')" />

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
            <file-input v-model="form.photo" :error="form.errors.photo" class="w-full pb-8 pr-6" type="file" accept="image/*" :label="__('Photo')" />
            <text-input v-model="form.discord_webhook_url" type="url" :error="form.errors.discord_webhook_url" class="w-full pb-8 pr-6" :label="__('Discord Webhook')" />
          </div>
        </div>

        <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
          <loading-button :loading="form.processing" class="btn-primary" type="submit">{{ __('Create') }}</loading-button>
        </div>
      </form>
    </card>
  </AdminLayout>
</template>
