<script setup>
import { ref, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import Modal from '@/Components/Modal.vue'
import Tip from '@/Components/Tip.vue'
import Card from '@/Components/Card.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  playlist: Object,
  providerPlaylist: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['close'])

const show = ref(true)
const providers = ref(['Spotify', 'Deezer', 'Blinest likes'])
const step = ref(1)
const pp = ref(null)

const checkForm = useForm({
  provider: providers.value[0],
  playlist_id: '',
})

const checkPlaylist = () => {
  console.log('Checking')
  checkForm.post(route('playlists.providers.find', props.playlist.id), {
    only: ['providerPlaylist', 'errors'],
    onSuccess: (response) => {
      pp.value = response.props.providerPlaylist
      if (pp.value) {
        if (pp.value.message) {
          checkForm.errors.playlist_id = `[${pp.value.code}] ${pp.value.message}`
        } else {
          step.value = 2
        }
      }
    },
  })
}

const importPlaylist = () => {
  checkForm
    .transform((data) => ({
      ...data,
      tracks_count: pp.value.tracks_count,
    }))
    .post(route('playlists.providers.import', props.playlist.id), {
      onSuccess: () => {
        emit('close')
      },
    })
}
</script>
<template>
  <Modal :show="show" @close="emit('close')">
    <div class="p-4">
      <!-- Step 1 -->
      <Card class="shadow-none" v-if="step === 1">
        <form @submit.prevent="checkPlaylist" class="flex flex-col gap-4">
          <Tip>{{ __('Only public playlists can be imported') }}<br />{{ __('Tracks that cannot be read by the site will not be imported.') }}</Tip>
          <SelectInput v-model="checkForm.provider" :error="checkForm.provider.error" :label="__('Playlist source')" required>
            <option v-for="provider in providers" :value="provider">{{ provider }}</option>
          </SelectInput>
          <div v-if="checkForm.provider != 'Blinest likes'">
            <TextInput v-model="checkForm.playlist_id" :error="checkForm.errors.playlist_id" type="text" :label="__('Playlist ID')" required />
            <small>{{ __('You can find the ID of the playlist in the address bar of your browser.') }}</small><br />
            <small v-show="checkForm.provider === 'Spotify'">{{ __('Spotify ID example: https://open.spotify.com/playlist/') }}<span class="font-bold underline">37i9dQZF1DXcBWIGoYBM5M</span></small>
            <small v-show="checkForm.provider === 'Deezer'">{{ __('Deezer ID example: https://www.deezer.com/fr/playlist/') }}<span class="font-bold underline">53362031</span></small>
          </div>
          <div class="mt-4 flex items-center justify-end gap-2">
            <Link class="btn-secondary btn-sm" :href="route('playlists.edit', playlist.id)">{{ __('Cancel') }}</Link>
            <LoadingButton class="btn-secondary btn-sm" :loading="checkForm.processing">{{ __('Next') }}</LoadingButton>
          </div>
        </form>
      </Card>
      <!-- Step 2 -->
      <Card class="shadow-none" v-if="step === 2">
        <template #header> <h3 class="uppercase">{{ __('Confirm import') }}</h3> </template>
        <div class="mb-4 flex items-center">
          <img :src="pp.image" class="mr-2 h-20 w-20 rounded-full" />
          <div class="flex flex-col">
            <h3 class="text-xl">{{ pp.name }}</h3>
            <p class="italic">{{ pp.description }}</p>
          </div>
        </div>
        <ul class="mb-4">
          <li><b>{{ __('Source:') }}</b> {{ checkForm.provider }}</li>
          <li v-if="pp.id"><b>{{ __('Playlist ID:') }}</b> {{ pp.id }}</li>
          <li><b>{{ __('Tracks to be imported:') }}</b> {{ pp.tracks_count }}</li>
        </ul>
        <Tip>{{ __('Duplicated tracks will not be imported') }}.</Tip>
        <div class="mt-4 flex items-center justify-end gap-2">
          <Link class="btn-secondary btn-sm" :href="route('playlists.edit', playlist.id)">{{ __('Cancel') }}</Link>
          <LoadingButton class="btn-primary btn-sm" @click="importPlaylist" :loading="checkForm.processing">{{ __('Import') }}</LoadingButton>
        </div>
      </Card>
    </div>
  </Modal>
</template>
