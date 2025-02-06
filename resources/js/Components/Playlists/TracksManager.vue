<script setup>
import { ref, watch, computed, onMounted } from 'vue'
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
  { id: 1, provider: 'deezer', name: 'Deezer', enabled: true },
  // { id: 2, provider: 'spotify', name: 'Spotify', enabled: true },
  // { id: 2, provider: 'youtube', name: 'Youtube', enabled: true },
  { id: 3, provider: 'itunes', name: 'Apple music', enabled: true },
  { id: 4, provider: 'audius', name: 'Audius', enabled: true },
])
const activeProviders = computed(() => 
  providers.value.filter(p => p.enabled).map(p => p.provider)
)
const search = ref('')
const importingPlaylist = ref(false)
const loading = ref(false)
const results = ref([])

// Debounced search with cancelation
const searchController = ref(null)
const debouncedSearch = debounce(async () => {
  if (search_online.value.length > 1) {
    loading.value = true
    
    // Cancel previous request if exists
    if (searchController.value) {
      searchController.value.abort()
    }
    
    searchController.value = new AbortController()
    
    try {
      const response = await axios.get(
        route('tracks.search', props.playlist.id) + '?term=' + search_online.value,
        { signal: searchController.value.signal }
      )
      results.value = response.data.tracks
    } catch (err) {
      if (err.name === 'AbortError') return
      console.error(err)
    } finally {
      loading.value = false 
    }
  } else {
    results.value = []
  }
}, 300)

watch(search_online, debouncedSearch)

// Optimized form updates
watch(
  form,
  throttle(() => {
    const data = pickBy(form)
    if (Object.keys(data).length) {
      loading.value = true
      router.get(route('playlists.edit', props.playlist), data, {
        preserveScroll: true,
        preserveState: true,
        only: ['tracks'],
        onSuccess: () => loading.value = false,
      })
    }
  }, 150),
  { deep: true },
)

const toggleProvider = (provider) => {
  provider.enabled = !provider.enabled
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

const addTrack = async (track) => {
  loading.value = true
  try {
    await router.post(route('playlists.tracks.store', props.playlist.id), track, {
      preserveScroll: true,
      preserveState: true,
      only: ['tracks'],
      onSuccess: () => debouncedSearch(),
    })
  } finally {
    loading.value = false
  }
}

const removeTrack = async (track) => {
  const id = track.added ? track.added.id : track.id
  if (confirm('Voulez-vous vraiment supprimer cette piste ?')) {
    loading.value = true
    try {
      await router.delete(route('playlists.tracks.delete', [props.playlist.id, id]), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => debouncedSearch(),
      })
    } finally {
      loading.value = false
    }
  }
}

const updateDificulty = async (e, track) => {
  try {
    await router.put(
      route('playlists.tracks.update', [props.playlist.id, track]),
      { dificulty: e.target.value },
      { preserveScroll: true }
    )
  } catch (err) {
    console.error(err)
  }
}

// Cleanup on unmount
onMounted(() => {
  return () => {
    if (searchController.value) {
      searchController.value.abort()
    }
  }
})
</script>
<template>
  <Card>
    <template #header>
      <div class="flex w-full flex-col lg:flex-row items-start lg:items-center justify-between gap-4 p-4">
        <div>
          <h3 class="text-xl lg:text-2xl font-bold mb-2">{{ __('Tracks manager') }} <span class="text-blinest-500">({{ tracks.total }})</span></h3>
          <div class="flex flex-wrap items-center gap-2 lg:gap-3 text-xs lg:text-sm">
            <div class="flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 rounded-full bg-teal-400/10">
              <div class="w-1.5 lg:w-2 h-1.5 lg:h-2 rounded-full bg-teal-400"></div>
              <span>{{ __('Easy') }} {{ Math.round(playlist.difficulties.Easy / tracks.total * 100) }}%</span>
            </div>
            <div class="flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 rounded-full bg-yellow-400/10">
              <div class="w-1.5 lg:w-2 h-1.5 lg:h-2 rounded-full bg-yellow-400"></div>
              <span>{{ __('Medium') }} {{ Math.round(playlist.difficulties.Medium / tracks.total * 100) }}%</span>
            </div>
            <div class="flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 rounded-full bg-orange-400/10">
              <div class="w-1.5 lg:w-2 h-1.5 lg:h-2 rounded-full bg-orange-400"></div>
              <span>{{ __('Difficult') }} {{ Math.round(playlist.difficulties.Difficult / tracks.total * 100) }}%</span>
            </div>
            <div class="flex items-center gap-2 px-2 lg:px-3 py-1 lg:py-1.5 rounded-full bg-red-400/10">
              <div class="w-1.5 lg:w-2 h-1.5 lg:h-2 rounded-full bg-red-400"></div>
              <span>{{ __('Expert') }} {{ Math.round(playlist.difficulties.Expert / tracks.total * 100) }}%</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col lg:flex-row w-full lg:w-auto items-stretch lg:items-center gap-4">
          <text-input 
            v-model="form.search" 
            prepend-icon="search" 
            :placeholder="__('Search in playlist') + '...'"
            class="w-full lg:min-w-[300px]"
          />
          
          <div class="flex items-center gap-2">
            <button 
              class="flex-1 lg:flex-none btn-secondary hover:bg-blinest-500 hover:text-white transition-colors duration-200" 
              @click="importingPlaylist = true"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="mr-2 w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
              </svg>
              {{ __('Import') }}
            </button>

            <a 
              :href="route('playlists.export', playlist)" 
              target="_blank" 
              class="flex-1 lg:flex-none btn-secondary hover:bg-blinest-500 hover:text-white transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="mr-2 h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
              {{ __('Export') }}
            </a>
          </div>
        </div>
      </div>
    </template>

    <div class="p-4">
      <div class="mb-6 flex flex-col lg:flex-row justify-between gap-4">
        <!-- Search on streaming platforms -->
        <Dropdown placement="bottom-start" :auto-close="false" class="flex-grow">
          <template #default>
            <TextInput 
              class="w-full" 
              v-model="search_online" 
              prepend-icon="plus" 
              append-icon="cheveron-down"
              :loading="loading" 
              :placeholder="__('Search on Deezer, Spotify and Apple music...')" 
            />
          </template>

          <template v-show="results.length" #dropdown>
            <div v-if="results.length" class="flex flex-wrap gap-2 lg:gap-3 border-b-2 border-neutral-700/50 bg-neutral-800 p-3 lg:p-4">
              <button
                v-for="provider in providers"
                :key="provider.id"
                @click="toggleProvider(provider)"
                class="flex items-center gap-2 rounded-full px-3 lg:px-4 py-1.5 lg:py-2 text-xs lg:text-sm font-medium transition-all duration-200"
                :class="[
                  provider.enabled 
                    ? 'bg-blinest-500 text-white shadow-lg shadow-blinest-500/20' 
                    : 'bg-neutral-700 text-neutral-400 hover:bg-neutral-600'
                ]"
              >
                <Icon 
                  :name="provider.provider" 
                  class="h-3 lg:h-4 w-3 lg:w-4" 
                  :class="{ 'opacity-50': !provider.enabled }"
                />
                {{ provider.name }}
              </button>
            </div>

            <ul v-if="results.length" class="max-h-[50vh] lg:max-h-[480px] overflow-y-auto bg-neutral-800 divide-y divide-neutral-700/50">
              <li
                v-for="result in results.filter(x => activeProviders.includes(x.provider))"
                :key="result.id"
                class="group p-2 lg:p-3 hover:bg-neutral-700/30 transition-colors duration-200"
              >
                <div class="flex items-center gap-2 lg:gap-4">
                  <Icon :name="result.provider" :title="result.provider" class="h-5 lg:h-6 w-5 lg:w-6 flex-shrink-0" />
                  
                  <div class="flex-shrink-0">
                    <MiniPlayer :key="`mini-player-results-${result.id}`" :track="result" />
                  </div>

                  <div class="mr-2 lg:mr-4 flex flex-grow flex-col min-w-0">
                    <span class="font-medium truncate">{{ result.artist_name }}</span>
                    <span class="text-xs lg:text-sm text-neutral-400 truncate">{{ result.track_name }}</span>
                  </div>

                  <div v-if="!result.added" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button 
                      :disabled="loading" 
                      class="btn-primary btn-sm hover:bg-blinest-600 transition-colors duration-200" 
                      type="button" 
                      @click="addTrack(result)"
                    >
                      {{ __('Add') }}
                    </button>
                  </div>
                  <div v-else class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button 
                      :disabled="loading" 
                      class="btn-danger btn-sm hover:bg-red-600 transition-colors duration-200" 
                      type="button" 
                      @click="removeTrack(result)"
                    >
                      {{ __('Remove') }}
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </template>
        </Dropdown>

        <SelectInput 
          v-model="form.paginate"
          class="w-full lg:w-32"
        >
          <option :value="5">5 / {{ tracks.total }}</option>
          <option :value="10">10 / {{ tracks.total }}</option>
          <option :value="15">15 / {{ tracks.total }}</option>
          <option :value="20">20 / {{ tracks.total }}</option>
        </SelectInput>
      </div>

      <div v-if="tracks.data.length" class="rounded-lg border border-neutral-700/50 overflow-x-auto" :class="{ 'opacity-50 pointer-events-none': loading }">
        <table class="w-full min-w-[800px]">
          <thead>
            <tr class="bg-neutral-800">
              <th class="px-3 lg:px-4 py-2 lg:py-3" colspan="2"></th>
              <th class="px-3 lg:px-4 py-2 lg:py-3 text-left">{{ __('Answers') }}</th>
              <th class="px-3 lg:px-4 py-2 lg:py-3 text-left">
                <Sortable field="dificulty" v-model="form.sortable">{{ __('Difficulty') }}</Sortable>
              </th>
              <th class="px-3 lg:px-4 py-2 lg:py-3" colspan="2">
                <Sortable field="votes" v-model="form.sortable">{{ __('Votes') }}</Sortable>
              </th>
              <th class="px-3 lg:px-4 py-2 lg:py-3">
                <Sortable field="created_at" v-model="form.sortable">{{ __('Created at') }}</Sortable>
              </th>
              <th class="px-3 lg:px-4 py-2 lg:py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-700/50">
            <tr 
              v-for="track in tracks.data" 
              :key="track.id"
              class="group hover:bg-neutral-800/50 transition-colors duration-200"
            >
              <td class="px-2 lg:px-3 py-2">
                <a 
                  target="_blank" 
                  :href="track.provider_url"
                  class="flex items-center justify-center hover:text-blinest-500 transition-colors duration-200"
                >
                  <Icon :name="track.provider" :title="track.provider" class="h-4 lg:h-5 w-4 lg:w-5" />
                </a>
              </td>
              <td class="px-2 lg:px-3 py-2">
                <mini-player :key="`mini-player-list-${track.id}`" :track="track" />
              </td>
              <td class="px-3 lg:px-4 py-2">
                <div class="space-y-1">
                  <div 
                    v-for="answer in track.answers" 
                    :key="answer.id"
                    class="group/answer cursor-pointer hover:bg-neutral-700/30 rounded px-2 py-1 transition-colors duration-200" 
                    @click="editAnswer(track, answer)"
                  >
                    <div class="flex items-center gap-2">
                      <span class="text-xs lg:text-sm font-medium">{{ __(answer.type.name) }}:</span>
                      <span class="text-xs lg:text-sm text-neutral-400">{{ answer.value }}</span>
                      <span class="text-[10px] lg:text-xs text-blinest-500 opacity-0 group-hover/answer:opacity-100 transition-opacity duration-200 ml-auto">
                        {{ answer.score }}pts
                      </span>
                    </div>
                  </div>
                  <button 
                    class="flex items-center gap-1 text-[10px] lg:text-xs text-neutral-400 hover:text-white transition-colors duration-200" 
                    @click="createAnswer(track)"
                  >
                    <Icon name="plus" class="h-2.5 lg:h-3 w-2.5 lg:w-3" />
                    {{ __('Add an answer') }}
                  </button>
                </div>
              </td>
              <td class="px-3 lg:px-4 py-2">
                <SelectInput 
                  v-model="track.dificulty" 
                  :error="$page.props.errors.dificulty"
                  @change="updateDificulty($event, track)"
                  class="w-24 lg:w-28 text-xs lg:text-sm"
                >
                  <option :value="0">{{ __('Easy') }}</option>
                  <option :value="1">{{ __('Medium') }}</option>
                  <option :value="2">{{ __('Difficult') }}</option>
                  <option :value="3">{{ __('Expert') }}</option>
                </SelectInput>
              </td>
              <td class="px-3 lg:px-4 py-2">
                <div class="flex items-center gap-1 text-teal-400 text-xs lg:text-sm">
                  <Icon name="thumb-up" class="h-3 lg:h-4 w-3 lg:w-4" />
                  {{ track.up_votes }}
                </div>
              </td>
              <td class="px-3 lg:px-4 py-2">
                <div class="flex items-center gap-1 text-red-400 text-xs lg:text-sm">
                  <Icon name="thumb-down" class="h-3 lg:h-4 w-3 lg:w-4" />
                  {{ track.down_votes }}
                </div>
              </td>
              <td class="px-3 lg:px-4 py-2 text-[10px] lg:text-xs text-neutral-400">
                {{ track.created_at }}
              </td>
              <td class="px-3 lg:px-4 py-2">
                <button
                  class="opacity-0 group-hover:opacity-100 fill-red-400 transition-all duration-200"
                  @click="removeTrack(track)"
                >
                  <Icon name="delete" class="h-4 lg:h-5 w-4 lg:w-5" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div 
        v-else 
        class="flex flex-col items-center justify-center py-8 lg:py-12 text-sm lg:text-base text-neutral-400"
      >
        <Icon name="music-note" class="h-8 lg:h-12 w-8 lg:w-12 mb-3 lg:mb-4" />
        {{ __('No tracks found') }}
      </div>

      <Pagination class="mt-4 lg:mt-6" :links="tracks.links" />

      <TrackAnswerForm 
        v-if="creatingAnswer || (editingAnswer && selectedAnswer)" 
        :answer="selectedAnswer"
        :answer_types="answer_types" 
        :show="editingAnswer || creatingAnswer" 
        max-width="md" 
        @close="closeModal" 
      />
    </div>
  </Card>

  <ImportPlaylist 
    v-if="importingPlaylist" 
    @close="importingPlaylist = false" 
    :playlist="playlist" 
  />
</template>