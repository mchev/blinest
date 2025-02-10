<script setup>
import { ref, watch, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
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
  playlist: {
    type: Object,
    required: true
  },
  answer_types: {
    type: Object,
    required: true
  },
  tracks: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
      paginate: 5,
      sortable: null,
    }),
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
const searchLoading = ref(false)
const results = ref([])
const importingPlaylist = ref(false)
const providerErrors = ref({})

// Loading states with TypeScript-like interface
const loadingStates = ref({
  search: false,
  addTrack: null, // Track ID
  removeTrack: null, // Track ID  
  updateDifficulty: null, // Track ID
})

// Default providers with TypeScript-like interface
const getDefaultProviders = () => [
  { id: 2, provider: 'youtube', name: 'Youtube', enabled: true },
  { id: 3, provider: 'itunes', name: 'Apple music', enabled: true },
  { id: 4, provider: 'audius', name: 'Audius', enabled: true },
  { id: 5, provider: 'spotify', name: 'Spotify', enabled: true },
  { id: 6, provider: 'deezer', name: 'Deezer', enabled: true },
  // { id: 7, provider: 'jamendo', name: 'Jamendo', enabled: true },
]

// Initialize providers from localStorage or defaults, ensuring all providers exist
const providers = ref((() => {
  const defaultProviders = getDefaultProviders()
  const storedProviders = JSON.parse(localStorage.getItem('trackManagerProviders')) || defaultProviders
  
  // Check if any new providers need to be added
  const updatedProviders = [...storedProviders]
  defaultProviders.forEach(defaultProvider => {
    if (!storedProviders.some(p => p.provider === defaultProvider.provider)) {
      updatedProviders.push(defaultProvider)
    }
  })
  
  // Update localStorage if providers were added
  if (updatedProviders.length !== storedProviders.length) {
    localStorage.setItem('trackManagerProviders', JSON.stringify(updatedProviders))
  }
  
  return updatedProviders
})())

// Computed property for active providers
const activeProviders = computed(() => 
  providers.value.filter(p => p.enabled).map(p => p.provider)
)

// Improved search with AbortController and error handling
const searchController = ref(null)
const debouncedSearch = debounce(async () => {
  // Clear results immediately when search starts
  results.value = []
  providerErrors.value = {}
  
  if (search_online.value.length < 2) {
    return
  }

  searchLoading.value = true
  
  // Cancel any pending requests
  if (searchController.value) {
    searchController.value.abort()
  }
  
  searchController.value = new AbortController()
  
  try {
    // Only use active providers instead of all providers
    const activeProvidersString = activeProviders.value.join(',')
    
    const response = await axios.get(
      route('tracks.search', props.playlist.id),
      {
        params: {
          term: search_online.value,
          providers: activeProvidersString // Use active providers only
        },
        signal: searchController.value.signal
      }
    )
    
    results.value = response.data.tracks
    
    // Update provider errors directly from the response
    providerErrors.value = response.data.errors.reduce((acc, error) => {
      if (error.provider) {
        acc[error.provider] = {
          message: error.message,
          status_code: error.status_code,
          quota_exceeded: error.quota_exceeded || false,
          reset_time: error.reset_time
        }
      }
      return acc
    }, {})

    // Handle YouTube quota exceeded
    if (providerErrors.value.youtube?.quota_exceeded) {
      const youtubeProvider = providers.value.find(p => p.provider === 'youtube')
      if (youtubeProvider) {
        youtubeProvider.enabled = false
        localStorage.setItem('youtube_quota_reset_time', providerErrors.value.youtube.reset_time)
      }
    }
  } catch (err) {
    if (err.name === 'AbortError') return
    
    console.error('Search error:', err)
    // Add a generic error if the request fails completely
    providerErrors.value = {
      general: {
        message: 'An error occurred while searching',
        status_code: err.response?.status || 500
      }
    }
  } finally {
    searchLoading.value = false 
  }
}, 500)

// Watch search term changes
watch([search_online], () => {
  debouncedSearch()
})

// Optimized form updates with error handling
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
        onError: (errors) => {
          loading.value = false
          console.error('Form update failed:', errors)
        }
      })
    }
  }, 500),
  { deep: true },
)

const toggleProvider = (provider) => {
  provider.enabled = !provider.enabled
  // Save updated providers to localStorage
  localStorage.setItem('trackManagerProviders', JSON.stringify(providers.value))
  // Trigger new search if there's a search term
  if (search_online.value.length >= 2) {
    debouncedSearch()
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

// Improved track operations with error handling
const addTrack = async (track) => {
  if (loadingStates.value.addTrack) return
  
  loadingStates.value.addTrack = track.id
  loading.value = true
  
  try {
    await router.post(route('playlists.tracks.store', props.playlist.id), track, {
      preserveScroll: true,
      preserveState: true,
      only: ['tracks'],
      onSuccess: () => debouncedSearch(),
    })
  } catch (error) {
    console.error('Error adding track:', error)
  } finally {
    loadingStates.value.addTrack = null
    loading.value = false
  }
}

const removeTrack = async (track) => {
  if (loadingStates.value.removeTrack) return
  
  if (!confirm('Voulez-vous vraiment supprimer cette piste ?')) {
    return
  }

  loadingStates.value.removeTrack = track.id
  loading.value = true
  const id = track.id ?? track.added
  props.tracks.data = props.tracks.data.filter(t => t.id !== id);
  results.value = results.value.filter(t => t.id !== id);

  try {
    await router.delete(route('playlists.tracks.delete', [props.playlist.id, id]), {
      preserveScroll: true,
      onSuccess: () => {
        debouncedSearch();
        props.tracks.data = props.tracks.data.filter(t => t.id !== id);
        results.value = results.value.filter(t => t.id !== id);
      },
      only: ['tracks'],
    })
  } catch (error) {
    console.error('Error removing track:', error)
  } finally {
    loadingStates.value.removeTrack = null
    loading.value = false
  }
}

const updateDificulty = async (e, track) => {
  if (loadingStates.value.updateDifficulty) return
  
  loadingStates.value.updateDifficulty = track.id
  const oldValue = e.target._oldValue
  
  try {
    await router.put(
      route('playlists.tracks.update', [props.playlist.id, track]),
      { dificulty: e.target.value },
      { 
        preserveScroll: true,
        preserveState: true,
        only: ['tracks'],
        onError: () => {
          track.dificulty = oldValue
        }
      }
    )
  } catch (err) {
    console.error('Error updating difficulty:', err)
    track.dificulty = oldValue
  } finally {
    loadingStates.value.updateDifficulty = null
  }
}

// Add this event listener setup in your script
onMounted(() => {
  router.on('start', () => {
    loading.value = true
  })
  
  router.on('finish', () => {
    loading.value = false
  })

  // Check YouTube quota status
  const resetTime = localStorage.getItem('youtube_quota_reset_time')
  if (resetTime && new Date(resetTime) > new Date()) {
    const youtubeProvider = providers.value.find(p => p.provider === 'youtube')
    if (youtubeProvider) {
      youtubeProvider.enabled = false
      providerErrors.value.youtube = {
        message: 'La recherche YouTube est temporairement indisponible - Le quota quotidien est dépassé',
        reset_time: resetTime,
        quota_exceeded: true
      }
    }
  } else {
    localStorage.removeItem('youtube_quota_reset_time')
    localStorage.removeItem('provider_errors')
  }

  // Cleanup function
  return () => {
    if (searchController.value) {
      searchController.value.abort()
    }
  }
})

// Keep your existing loading ref
const loading = ref(false)
</script>

<!-- Template remains the same -->
<template>
  <!-- Global loading overlay -->
  <div v-if="loading" 
       class="absolute inset-0 bg-neutral-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="flex flex-col items-center gap-3 text-white">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-8 animate-spin">
        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
      </svg>
      <span class="text-sm">{{ __('Chargement...') }}</span>
    </div>
  </div>

  <!-- Make sure Card has relative positioning -->
  <Card class="relative">
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
              :loading="searchLoading" 
              placeholder="Rechercher sur les plateformes pour ajouter des pistes" 
            />
          </template>

          <template #dropdown>
            <template v-if="search_online.length > 1">
              <div class="flex flex-wrap gap-2 lg:gap-3 border-b-2 border-neutral-700/50 bg-neutral-800 p-3 lg:p-4">
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

              <div class="relative">
                <!-- Show provider errors section -->
                <template v-if="Object.keys(providerErrors).length > 0">
                  <div 
                    v-for="(error, provider) in providerErrors" 
                    :key="provider"
                    class="flex items-center gap-2 lg:gap-3 p-3 lg:p-4 text-xs lg:text-sm"
                    :class="{
                      'bg-red-400/10 text-red-400': error.quota_exceeded,
                      'bg-yellow-400/10 text-yellow-400': error.status_code === 503,
                      'bg-orange-400/10 text-orange-400': error.status_code === 400,
                      'bg-orange-400/10 text-orange-400': error.status_code === 404
                    }"
                  >
                    <Icon :name="provider" class="h-4 lg:h-5 w-4 lg:w-5" />
                    <div class="flex flex-col">
                      <span class="font-medium max-w-[400px]">{{ error.message }}</span>
                      <span v-if="error.reset_time" class="text-[10px] lg:text-xs opacity-75">
                        Le service sera disponible à nouveau le {{ new Date(error.reset_time).toLocaleString() }}
                      </span>
                      <span v-else-if="error.status_code === 503" class="text-[10px] lg:text-xs opacity-75">
                        Veuillez réessayer dans quelques minutes
                      </span>
                    </div>
                  </div>
                </template>

                <!-- Results section -->
                <ul v-if="results.filter(x => activeProviders.includes(x.provider)).length > 0" 
                    class="max-h-[50vh] lg:max-h-[480px] overflow-y-auto bg-neutral-800 divide-y divide-neutral-700/50 w-[600px]">
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
                        <span class="font-medium truncate max-w-[200px]">{{ result.artist_name }}</span>
                        <span class="text-xs lg:text-sm text-neutral-400 truncate max-w-[200px]">{{ result.track_name }}</span>
                      </div>

                      <div class="flex items-center">
                        <template v-if="loading">
                          <div class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 animate-spin">
                              <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <span>{{ __('Loading...') }}</span>
                          </div>
                        </template>
                        
                        <template v-else>
                          <button 
                            v-if="!result.added"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-800 bg-white hover:bg-neutral-100 rounded-full shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            type="button"
                            @click="addTrack(result)"
                            :disabled="loadingStates.addTrack === result.id"
                          >
                            <svg v-if="loadingStates.addTrack === result.id" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 animate-spin">
                              <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <Icon v-else name="plus" class="size-4" />
                            <span>{{ loadingStates.addTrack === result.id ? __('Adding...') : __('Add') }}</span>
                          </button>
                          
                          <button 
                            v-else
                            class="flex items-center gap-2 text-neutral-400 hover:text-red-500 px-3 py-1.5 rounded-md transition-all duration-200" 
                            type="button" 
                            @click="removeTrack(result)"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Déjà dans la playlist
                          </button>
                        </template>
                      </div>
                    </div>
                  </li>
                </ul>

                <!-- No results message -->
                <div v-else-if="!searchLoading" class="p-4 text-center text-neutral-400">
                  {{ __('Aucun résultat trouvé') }}
                </div>

                <!-- Loading message -->
                <div v-if="searchLoading" class="p-4 text-center text-neutral-400">
                  <div class="flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 animate-spin">
                      <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    {{ __('Recherche en cours...') }}
                  </div>
                </div>
              </div>
            </template>
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

      <div v-if="tracks.data.length" class="w-full overflow-x-auto rounded-lg border border-neutral-700/50" :class="{ 'opacity-50 pointer-events-none': loading }">
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
                  <Icon :name="track.provider" :title="track.provider" class="size-6" />
                </a>
              </td>
              <td class="px-2 lg:px-3 py-2">
                <MiniPlayer :key="`mini-player-list-${track.id}`" :track="track" />
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
                  :disabled="loadingStates.updateDifficulty === track.id"
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
                  class="fill-red-400 disabled:opacity-50 disabled:cursor-not-allowed"
                  type="button"
                  title="Supprimer de la playlist"
                  @click="removeTrack(track)"
                  :disabled="loadingStates.removeTrack === track.id"
                >
                  <svg v-if="loadingStates.removeTrack === track.id" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 animate-spin">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                  </svg>
                  <Icon v-else name="delete" class="size-5" />
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