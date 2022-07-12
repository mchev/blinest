<script setup>
import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import Pagination from '@/Components/Pagination.vue'
import TrackAnswerForm from './TrackAnswerForm.vue'
import MiniPlayer from '@/Components/MiniPlayer.vue'
import Dropdown from '@/Components/Dropdown.vue'
import pickBy from 'lodash/pickBy'
import debounce from 'lodash/debounce'
import throttle from 'lodash/throttle'

const props = defineProps({
  playlist: Object,
  answer_types: Object,
  tracks: Object,
  filters: {
    type: Object,
    default: {
      search: '',
    },
  },
})

const form = useForm({
  search: props.filters.search,
})
const selectedAnswer = ref(null)
const creatingAnswer = ref(false)
const editingAnswer = ref(false)
const search_online = ref('')
const search = ref('')
const loading = ref(false)
const results = ref([])

watch(
  search_online,
  debounce(() => {
    fecthMusicProviders()
  }, 300),
)

watch(
  form,
  throttle(() => {
    Inertia.get(route('playlists.edit', props.playlist), pickBy(form), {
      preserveScroll: true,
      preserveState: true,
    })
  }, 150),
  { deep: true },
)

const fecthMusicProviders = () => {
  if (search_online.value.length > 1) {
    loading.value = true
    axios.get(route('tracks.search', props.playlist.id) + '?term=' + search_online.value).then((response) => {
      results.value = response.data.tracks
      loading.value = false
    })
  } else {
    results.value = []
  }
}

const createAnswer = (track) => {
  creatingAnswer.value = track.id
}

const editAnswer = (track, answer = null) => {
  selectedAnswer.value = answer
  editingAnswer.value = track.id
}

const closeModal = () => {
  creatingAnswer.value = false
  editingAnswer.value = false
}

const addTrack = (track) => {
  Inertia.post(route('playlists.tracks.store', props.playlist.id), track, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      fecthMusicProviders()
    },
  })
}

const removeTrack = (track) => {
  let id = track.added ? track.added.id : track.id

  Inertia.delete(route('tracks.delete', id), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      fecthMusicProviders()
    },
  })
}
</script>
<template>
  <Card>
    <template #header>
      <div class="flex w-full items-center justify-between">
        <h3 class="text-xl font-bold">{{ __('Tracks manager') }} ({{ tracks.total }})</h3>
        <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search in playlist') + '...'" />
      </div>
    </template>
    <div>
      <div class="mb-4 flex flex-wrap gap-2 p-2">
        <!-- Search on streaming platforms -->
        <dropdown placement="bottom-start" :auto-close="false" @closed="search_online = ''">
          <template #default>
            <text-input v-model="search_online" prepend-icon="plus" append-icon="cheveron-down" :loading="loading" :placeholder="__('Search on Deezer, Spotify and Apple music...')" />
          </template>
          <template v-show="results.length" #dropdown>
            <ul v-if="results.length" class="max-w-50 max-h-80 overflow-y-auto">
              <li v-for="result in results" class="relative cursor-pointer border-b border-neutral-200 px-1 py-2 hover:bg-neutral-100 hover:text-neutral-900">
                <div class="flex items-center">
                  <div><Icon :name="result.provider" :title="result.provider" class="mr-2 h-6 w-6 flex-shrink-0" /></div>
                  <div>
                    <mini-player :key="`mini-player-results-${result.id}`" :track="result" />
                  </div>
                  <div class="mr-2 flex w-80 flex-1 flex-col">
                    <span class="max-w-[16rem] truncate break-normal font-bold">{{ result.artist_name }}</span>
                    <span class="max-w-[16rem] truncate break-normal text-sm">{{ result.track_name }}</span>
                  </div>
                  <div v-if="!result.added" class="ml-auto">
                    <button :disabled="loading" class="btn-primary" type="button" @click="addTrack(result)">{{ __('Add') }}</button>
                  </div>
                  <div v-else class="ml-auto">
                    <button :disabled="loading" class="btn-danger" type="button" @click="removeTrack(result)">{{ __('Remove') }}</button>
                  </div>
                </div>
              </li>
            </ul>
          </template>
        </dropdown>
      </div>

      <div class="mx-4 overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6" colspan="2"></th>
            <th class="px-6 pb-4 pt-6">{{ __('Answers') }}</th>
            <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Votes') }}</th>
            <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Created at') }}</th>
          </tr>
          <tr v-for="track in tracks.data" :key="track.id">
            <td class="border-t">
              <a target="_blank" :href="track.provider_url" class="flex items-center justify-center px-2 py-4 focus:text-blinest-500">
                <Icon :name="track.provider" :title="track.provider" class="mr-2 h-6 w-6 flex-shrink-0" />
              </a>
            </td>
            <td class="border-t">
              <div class="flex items-center justify-center px-2 py-4 focus:text-blinest-500">
                <mini-player :key="`mini-player-list-${track.id}`" :track="track" />
              </div>
            </td>
            <td class="border-t">
              <div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500">
                <div v-for="answer in track.answers" :key="answer.id" class="cursor-pointer whitespace-normal break-words" @click="editAnswer(track, answer)">
                  <span class="font-bold">{{ __(answer.type.name) }}:</span> {{ answer.value }} ({{ answer.score }}pts)
                </div>
                <button class="text-neutral-400" @click="createAnswer(track)"><Icon name="plus" class="inline-block h-4 w-4 flex-shrink-0 fill-neutral-400" />{{ __('Add an answer') }}</button>
              </div>
            </td>
            <td class="border-t">
              <div class="flex items-start px-6 py-4 text-center text-sm"><Icon name="thumb-up" class="mr-2 h-6 w-6 flex-shrink-0 fill-teal-600" /> {{ track.up_votes }}</div>
            </td>
            <td class="border-t">
              <div class="flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500"><Icon name="thumb-down" class="mr-2 h-6 w-6 flex-shrink-0 fill-neutral-400" /> {{ track.down_votes }}</div>
            </td>
            <td class="border-t">
              <div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500">
                {{ track.created_at }}
              </div>
            </td>
            <td class="border-t">
              <Icon name="delete" class="mr-2 h-6 w-6 flex-shrink-0 cursor-pointer fill-red-400" @click="removeTrack(track)" />
            </td>
          </tr>
          <tr v-if="tracks.length === 0">
            <td class="border-t px-6 py-4" colspan="4">{{ __('No tracks found') }}.</td>
          </tr>
        </table>
      </div>

      <pagination class="p-8" :links="tracks.links" />

      <track-answer-form v-if="creatingAnswer || (editingAnswer && selectedAnswer)" :answer="selectedAnswer" :answer_types="answer_types" :show="editingAnswer || creatingAnswer" max-width="md" @close="closeModal" />
    </div>
  </Card>
</template>
