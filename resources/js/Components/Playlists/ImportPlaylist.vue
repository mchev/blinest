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
    only: ['providerPlaylist'],
    onSuccess: (response) => {
      pp.value = response.props.providerPlaylist
      if (pp.value) {
        if (pp.value.message) {
          checkForm.errors.provider_id = `[${pp.value.code}] ${pp.value.message}`
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
          <Tip>Pour importer une playlist, celle-ci doit être publique.<br />Les extraits ne pouvant pas être lus par le site ne seront pas importés.</Tip>
          <SelectInput v-model="checkForm.provider" :error="checkForm.provider.error" label="Origine de la playlist" required>
            <option v-for="provider in providers" :value="provider">{{ provider }}</option>
          </SelectInput>
          <div v-if="checkForm.provider != 'Blinest likes'">
            <TextInput v-model="checkForm.playlist_id" :error="checkForm.errors.provider_id" type="text" label="ID de la playlist" required />
            <small>Vous pouvez trouver l'ID de la playlist dans la barre d'adresse de votre navigateur</small><br />
            <small v-show="checkForm.provider === 'Spotify'">Exemple d'ID Spotify : https://open.spotify.com/playlist/<span class="font-bold underline">37i9dQZF1DXcBWIGoYBM5M</span></small>
            <small v-show="checkForm.provider === 'Deezer'">Exemple d'ID Deezer : https://www.deezer.com/fr/playlist/<span class="font-bold underline">53362031</span></small>
          </div>
          <LoadingButton class="btn-secondary btn-sm ml-auto" :loading="checkForm.processing">{{ __('Next') }}</LoadingButton>
        </form>
      </Card>
      <!-- Step 2 -->
      <Card class="shadow-none" v-if="step === 2">
        <template #header> <h3 class="uppercase">Confirmer l'importation</h3> </template>
        <div class="mb-4 flex items-center">
          <img :src="pp.image" class="mr-2 h-20 w-20 rounded-full" />
          <div class="flex flex-col">
            <h3 class="text-xl">{{ pp.name }}</h3>
            <p class="italic">{{ pp.description }}</p>
          </div>
        </div>
        <ul class="mb-4">
          <li><b>Origine :</b> {{ checkForm.provider }}</li>
          <li v-if="pp.id"><b>Playlist ID :</b> {{ pp.id }}</li>
          <li><b>Titres à importer :</b> {{ pp.tracks_count }}</li>
        </ul>
        <Tip>Les titres en doublon ne seront pas importés.</Tip>
        <div class="mt-4 flex items-center justify-end gap-2">
          <button class="btn-secondary btn-sm" @click="step = 1">{{ __('Cancel') }}</button>
          <LoadingButton class="btn-primary btn-sm" @click="importPlaylist" :loading="checkForm.processing">{{ __('Import') }}</LoadingButton>
        </div>
      </Card>
    </div>
  </Modal>
</template>
