<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
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
import Sortable from '@/Components/Sortable.vue'
import ImportPlaylist from './ImportPlaylist.vue'

const props = defineProps({
  playlist: Object,
  answer_types: Object,
  tracks: Object,
  filters: {
    type: Object,
    default: {
      search: '',
      paginate: 5,
      sortable: null,
    },
  },
})

const form = useForm({
  search: props.filters.search,
  paginate: props.filters.paginate ?? 5,
  sortable: props.filters.sortable,
})
const selectedAnswer = ref(null)
const creatingAnswer = ref(false)
const editingAnswer = ref(false)
const search_online = ref('')
const providers = ref([
  { id: 1, provider: 'deezer', name: 'Deezer', selected: true },
  { id: 2, provider: 'spotify', name: 'Spotify', selected: true },
  { id: 3, provider: 'itunes', name: 'Apple music', selected: true },
])
const selectedProviders = ref(['deezer', 'spotify', 'itunes'])
const search = ref('')
const importingPlaylist = ref(false)
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
    router.get(route('playlists.edit', props.playlist), pickBy(form), {
      preserveScroll: true,
      preserveState: true,
    })
  }, 150),
  { deep: true },
)

const check = (provider) => {
  if (selectedProviders.value.includes(provider.provider)) {
    selectedProviders.value = selectedProviders.value.filter((x) => x != provider.provider)
  } else {
    selectedProviders.value.push(provider.provider)
  }
}

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
  selectedAnswer.value = null
  creatingAnswer.value = false
  editingAnswer.value = false
}

const addTrack = (track) => {
  router.post(route('playlists.tracks.store', props.playlist.id), track, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      fecthMusicProviders()
    },
  })
}

const removeTrack = (track) => {
  let id = track.added ? track.added.id : track.id
  if (confirm('Voulez-vous vraiment supprimer cet extrait?')) {
    router.delete(route('playlists.tracks.delete', [props.playlist.id, id]), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        fecthMusicProviders()
      },
    })
  }
}

const updateDificulty = (e, track) => {
  router.put(
    route('playlists.tracks.update', [props.playlist.id, track]),
    { dificulty: e.target.value },
    {
      preserveScroll: true,
    },
  )
}
</script>
<template>
  <Card>
    <template #header>
      <div class="flex w-full items-center justify-between flex-wrap">
        <div>
          <h3 class="text-xl font-bold">{{ __('Tracks manager') }} ({{ tracks.total }})</h3>
          <ul class="flex items-center gap-2 text-xs font-normal flex-wrap">
            <li class="text-teal-400">{{ __('Easy') }} {{ Math.round(playlist.difficulties.Easy / tracks.total * 100) }}%,</li>
            <li class="text-yellow-400">{{ __('Medium') }} {{ Math.round(playlist.difficulties.Medium / tracks.total * 100) }}%,</li>
            <li class="text-orange-400">{{ __('Difficult') }} {{ Math.round(playlist.difficulties.Difficult / tracks.total * 100) }}%,</li>
            <li class="text-red-400">{{ __('Expert') }} {{ Math.round(playlist.difficulties.Expert / tracks.total * 100) }}%</li>
          </ul>
        </div>
        <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search in playlist') + '...'" />
        <div class="flex items-center gap-2 flex-wrap">
          <button class="btn-secondary" @click="importingPlaylist = true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
            </svg>
            {{ __('Import') }}
          </button>
          <a :href="route('playlists.export', playlist)" target="_blank" class="btn-secondary"
            ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
             {{ __('Export') }}
          </a>
        </div>
      </div>
    </template>
    <div>
      <div class="mb-4 flex justify-between gap-2 p-2">
        <!-- Search on streaming platforms -->
        <dropdown placement="bottom-start" :auto-close="false" class="flex-grow">
          <template #default>
            <text-input class="w-full" v-model="search_online" prepend-icon="plus" append-icon="cheveron-down" :loading="loading" :placeholder="__('Search on Deezer, Spotify and Apple music...')" />
          </template>
          <template v-show="results.length" #dropdown>
            <div v-if="results.length" class="flex gap-2 border-b-2 border-neutral-600 bg-neutral-900 p-2 text-sm">
              <label v-for="provider in providers"> <input type="checkbox" v-model="provider.selected" @click="check(provider)" /> {{ provider.name }} </label>
            </div>
            <ul v-if="results.length" class="max-h-80 overflow-y-auto bg-neutral-900">
              <li v-for="result in results.filter((x) => selectedProviders.includes(x.provider))" class="relative border-b border-neutral-600 px-2 py-3" :key="result.id">
                <div class="flex items-center gap-2">
                  <div><Icon :name="result.provider" :title="result.provider" class="h-6 w-6 flex-shrink-0" /></div>
                  <div>
                    <mini-player :key="`mini-player-results-${result.id}`" :track="result" />
                  </div>
                  <div class="mr-2 flex w-80 flex-grow flex-col">
                    <span class="max-w-[16rem] truncate break-normal font-bold">{{ result.artist_name }}</span>
                    <span class="max-w-[16rem] truncate break-normal text-sm">{{ result.track_name }}</span>
                  </div>
                  <div v-if="!result.added" class="ml-auto">
                    <button :disabled="loading" class="btn-primary btn-sm" type="button" @click="addTrack(result)">{{ __('Add') }}</button>
                  </div>
                  <div v-else class="ml-auto">
                    <button :disabled="loading" class="btn-danger btn-sm" type="button" @click="removeTrack(result)">{{ __('Remove') }}</button>
                  </div>
                </div>
              </li>
            </ul>
          </template>
        </dropdown>

        <SelectInput v-model="form.paginate">
          <option :value="5">5 / {{tracks.total}}</option>
          <option :value="10">10 / {{tracks.total}}</option>
          <option :value="15">15 / {{tracks.total}}</option>
          <option :value="20">20 / {{tracks.total}}</option>
        </SelectInput>

      </div>

      <div v-if="tracks.data.length" class="mx-4 overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6" colspan="2"></th>
            <th class="px-6 pb-4 pt-6">{{ __('Answers') }}</th>
            <th class="px-6 pb-4 pt-6">
              <Sortable field="dificulty" v-model="form.sortable">{{ __('Difficulty') }}</Sortable>
            </th>
            <th class="px-6 pb-4 pt-6" colspan="2">
              <Sortable field="votes" v-model="form.sortable">{{ __('Votes') }}</Sortable>
            </th>
            <th class="px-6 pb-4 pt-6" colspan="2">
              <Sortable field="created_at" v-model="form.sortable">{{ __('Created at') }}</Sortable>
            </th>
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
              <div class="flex items-start px-6 py-4 text-center text-sm">
                <SelectInput v-model="track.dificulty" :error="$page.props.errors.dificulty" @change="updateDificulty($event, track)">
                  <option :value="0">{{ __('Easy') }}</option>
                  <option :value="1">{{ __('Medium') }}</option>
                  <option :value="2">{{ __('Difficult') }}</option>
                  <option :value="3">{{ __('Expert') }}</option>
                </SelectInput>
              </div>
            </td>
            <td class="border-t">
              <div class="flex items-start px-6 py-4 text-center text-sm"><Icon name="thumb-up" class="mr-2 h-6 w-6 flex-shrink-0" /> {{ track.up_votes }}</div>
            </td>
            <td class="border-t">
              <div class="flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500"><Icon name="thumb-down" class="mr-2 h-6 w-6 flex-shrink-0" /> {{ track.down_votes }}</div>
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
            <td class="border-t px-6 py-4" colspan="4">{{ __('No tracks found') }}</td>
          </tr>
        </table>
      </div>

      <div v-else class="p-2">
        {{ __('Aucun extrait') }}
      </div>

      <pagination class="p-8" :links="tracks.links" />

      <track-answer-form v-if="creatingAnswer || (editingAnswer && selectedAnswer)" :answer="selectedAnswer" :answer_types="answer_types" :show="editingAnswer || creatingAnswer" max-width="md" @close="closeModal" />
    </div>
  </Card>

  <ImportPlaylist v-if="importingPlaylist" @close="importingPlaylist = false" :playlist="playlist" />
</template>