<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
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

const mosaicForm = useForm({})
const generatingMosaic = ref(false)

const generateMosaic = () => {
  mosaicForm.post(route('rooms.generate.mosaic', props.room.id), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      generatingMosaic.value = true
    },
  })
}

onMounted(() => {
  Echo.private(`rooms.${props.room.id}`)
    .listen('MosaicGenerated', (e) => {
      console.log(e.room)
      generatingMosaic.value = false
    })
})

onUnmounted(() => {
  Echo.leave(`rooms.${props.room.id}`)
})
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
                <file-input v-if="room.is_pro || room.is_public" v-model="form.photo" :error="form.errors.photo" class="mb-4 w-full" type="file" accept="image/*" :label="__('Photo')" />
                <div v-else>
                  <button type="button" class="btn-secondary" v-if="!generatingMosaic" @click="generateMosaic">{{ __('Generate a new thumbnail') }}</button>
                  <LoadingButton v-else :loading="true" class="text-xs text-yellow-600">{{ __('Generating new thumbnail') }}</LoadingButton>
                </div>
              </div>
            </div>
          </form>
          <template #footer>
            <button v-if="!room.deleted_at" class="text-sm text-red-500 hover:underline" tabindex="-1" type="button" @click="destroy">{{ __('Delete') }}</button>
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
              <label for="tracks_by_round-range" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300"
                >{{ __('Tracks by round') }} : <span class="font-bold">{{ form.tracks_by_round }}</span></label
              >
              <input id="tracks_by_round-range" type="range" min="1" max="30" v-model="form.tracks_by_round" :error="form.errors.tracks_by_round" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

              <label for="track_duration-range" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300"
                >{{ __('Track duration') }} : <span class="font-bold">{{ form.track_duration }} {{ __('seconds') }}</span></label
              >
              <input id="track_duration-range" type="range" min="5" max="30" v-model="form.track_duration" :error="form.errors.track_duration" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

              <label for="pause_between_tracks-range" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300"
                >{{ __('Pause between tracks') }} : <span class="font-bold">{{ form.pause_between_tracks }} {{ __('seconds') }}</span></label
              >
              <input id="pause_between_tracks-range" type="range" min="0" max="30" v-model="form.pause_between_tracks" :error="form.errors.pause_between_tracks" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />

              <label for="pause_between_rounds-range" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-300"
                >{{ __('Pause between rounds') }} : <span class="font-bold">{{ form.pause_between_rounds }} {{ __('seconds') }}</span></label
              >
              <input id="pause_between_rounds-range" type="range" min="0" max="60" v-model="form.pause_between_rounds" :error="form.errors.pause_between_rounds" step="1" class="mb-6 h-2 w-full cursor-pointer appearance-none rounded-lg bg-neutral-700" />
            </div>

            <div class="flex w-full flex-wrap">
              <checkbox-input v-model="form.is_chat_active" :error="form.errors.is_chat_active" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Chatbox')" />
              <checkbox-input v-model="form.has_password" class="w-full pr-4 pb-4 md:w-1/2" :label="__('Password')" />
            </div>

            <text-input v-show="form.has_password" v-model="form.password" :error="form.errors.password" class="pb-6" type="password" autocomplete="new-password" :label="__('Password')" />
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
