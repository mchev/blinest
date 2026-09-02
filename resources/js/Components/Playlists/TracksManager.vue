<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import Pagination from '@/Components/Pagination.vue'
import TrackAnswerForm from './TrackAnswerForm.vue'
import TrackHintForm from './TrackHintForm.vue'
import TrackCard from './TrackCard.vue'
import MiniPlayer from '@/Components/MiniPlayer.vue'
import Dropdown from '@/Components/Dropdown.vue'
import pickBy from 'lodash/pickBy'
import debounce from 'lodash/debounce'
import throttle from 'lodash/throttle'
import Sortable from '@/Components/Sortable.vue'
import ImportPlaylist from './ImportPlaylist.vue'
import UploadTrack from './UploadTrack.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'

const props = defineProps({
  playlist: {
    type: Object,
    required: true,
  },
  answer_types: {
    type: Object,
    required: true,
  },
  tracks: {
    type: Object,
    required: true,
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

const isModerator = computed(() => usePage().props.auth.user.is_public_moderator)

const form = useForm({
  search: props.filters.search,
  paginate: props.filters.paginate ?? 10,
  sortable: props.filters.sortable,
  difficulty: props.filters.difficulty || '',
  provider: props.filters.provider || '',
  minUpvotes: props.filters.minUpvotes || '',
  minDownvotes: props.filters.minDownvotes || '',
  downvoteReason: props.filters.downvoteReason || '',
})

const selectedAnswer = ref(null)
const creatingAnswer = ref(false)
const editingAnswer = ref(false)
const editingHint = ref(null)
const search_online = ref('')
const searchLoading = ref(false)
const results = ref([])
const importingPlaylist = ref(false)
const providerErrors = ref({})
const showDeleteTrackModal = ref(false)
const trackToDelete = ref(null)
const showClearPlaylistModal = ref(false)
const clearingPlaylist = ref(false)

const DOWNVOTE_REASON_LABELS = {
  sound_quality: 'Poor sound quality',
  difficulty: 'Too difficult',
  passage_choice: 'Bad passage choice',
  personal_taste: 'Not my taste',
  controversial_artist: 'Controversial artist',
  other: 'Other reason',
}

const downvoteReasonLabel = (reason) => __(DOWNVOTE_REASON_LABELS[reason] ?? reason)

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
  { id: 5, provider: 'local', name: 'Blinest', enabled: true },
  { id: 6, provider: 'deezer', name: 'Deezer', enabled: true },
  // Commented providers are excluded:
  // { id: 4, provider: 'audius', name: 'Audius', enabled: true },
  // { id: 5, provider: 'spotify', name: 'Spotify', enabled: true },
  // { id: 7, provider: 'jamendo', name: 'Jamendo', enabled: true },
]

// Initialize providers from localStorage or defaults, ensuring all providers exist
const providers = ref(
  (() => {
    const defaultProviders = getDefaultProviders()
    const storedProviders = JSON.parse(localStorage.getItem('trackManagerProviders')) || defaultProviders

    // Filter stored providers to only include those that exist in defaultProviders
    const validStoredProviders = storedProviders.filter((storedProvider) => defaultProviders.some((defaultProvider) => defaultProvider.provider === storedProvider.provider))

    // Add any missing default providers
    const updatedProviders = [...validStoredProviders]
    defaultProviders.forEach((defaultProvider) => {
      if (!validStoredProviders.some((p) => p.provider === defaultProvider.provider)) {
        updatedProviders.push(defaultProvider)
      }
    })

    // Update localStorage if providers were modified
    if (JSON.stringify(updatedProviders) !== JSON.stringify(storedProviders)) {
      localStorage.setItem('trackManagerProviders', JSON.stringify(updatedProviders))
    }

    return updatedProviders
  })(),
)

// Computed property for active providers
const activeProviders = computed(() => providers.value.filter((p) => p.enabled).map((p) => p.provider))

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

    const response = await axios.get(route('tracks.search', props.playlist.id), {
      params: {
        term: search_online.value,
        providers: activeProvidersString, // Use active providers only
      },
      signal: searchController.value.signal,
    })

    results.value = response.data.tracks

    // Update provider errors directly from the response
    providerErrors.value = response.data.errors.reduce((acc, error) => {
      if (error.provider) {
        acc[error.provider] = {
          message: error.message,
          status_code: error.status_code,
          quota_exceeded: error.quota_exceeded || false,
          reset_time: error.reset_time,
        }
      }
      return acc
    }, {})

    // Handle YouTube quota exceeded
    if (providerErrors.value.youtube?.quota_exceeded) {
      const youtubeProvider = providers.value.find((p) => p.provider === 'youtube')
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
        status_code: err.response?.status || 500,
      },
    }
  } finally {
    searchLoading.value = false
  }
}, 500)

// Watch search term changes
watch([search_online], () => {
  debouncedSearch()
})

// Separate loading state for search/filter operations
const isSearching = ref(false)
const trackMutationInFlight = ref(false)
const loading = ref(false)

const reloadTracksFromFilters = throttle(() => {
  if (trackMutationInFlight.value) {
    return
  }

  const data = pickBy({
    search: form.search,
    paginate: form.paginate,
    sortable: form.sortable,
    difficulty: form.difficulty,
    provider: form.provider,
    minUpvotes: form.minUpvotes,
    minDownvotes: form.minDownvotes,
    downvoteReason: form.downvoteReason,
  })

  const isSearchOrFilter = Boolean(form.search || form.difficulty || form.provider || form.minUpvotes || form.minDownvotes || form.downvoteReason || form.sortable)

  if (isSearchOrFilter) {
    isSearching.value = true
  } else {
    loading.value = true
  }

  router.get(route('playlists.edit', props.playlist.id), data, {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks', 'playlist'],
    onFinish: () => {
      loading.value = false
      isSearching.value = false
    },
    onError: () => {
      loading.value = false
      isSearching.value = false
    },
  })
}, 500)

watch(
  () => [form.search, form.paginate, form.sortable, form.difficulty, form.provider, form.minUpvotes, form.minDownvotes, form.downvoteReason],
  () => {
    reloadTracksFromFilters()
  },
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
  editingHint.value = null
}

// Improved track operations with error handling
const addTrack = (track) => {
  if (loadingStates.value.addTrack || trackMutationInFlight.value) {
    return
  }

  loadingStates.value.addTrack = track.id
  trackMutationInFlight.value = true

  router.post(route('playlists.tracks.store', props.playlist.id), track, {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks', 'playlist'],
    onSuccess: () => debouncedSearch(),
    onFinish: () => {
      loadingStates.value.addTrack = null
      trackMutationInFlight.value = false
    },
    onError: () => {
      loadingStates.value.addTrack = null
      trackMutationInFlight.value = false
    },
  })
}

const removeTrack = (track) => {
  trackToDelete.value = track
  showDeleteTrackModal.value = true
}

const closeDeleteTrackModal = () => {
  showDeleteTrackModal.value = false
  trackToDelete.value = null
}

const clearFilters = () => {
  form.difficulty = ''
  form.provider = ''
  form.minUpvotes = ''
  form.minDownvotes = ''
  form.downvoteReason = ''
}

const confirmDeleteTrack = () => {
  if (!trackToDelete.value || loadingStates.value.removeTrack) {
    return
  }

  const track = trackToDelete.value
  loadingStates.value.removeTrack = track.id
  trackMutationInFlight.value = true
  const id = track.id ?? track.added

  router.delete(route('playlists.tracks.delete', [props.playlist.id, id]), {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks', 'playlist'],
    onSuccess: () => {
      debouncedSearch()
      showDeleteTrackModal.value = false
      trackToDelete.value = null
    },
    onFinish: () => {
      loadingStates.value.removeTrack = null
      trackMutationInFlight.value = false
    },
    onError: () => {
      loadingStates.value.removeTrack = null
      trackMutationInFlight.value = false
    },
  })
}

const confirmClearPlaylist = () => {
  if (clearingPlaylist.value || trackMutationInFlight.value) {
    return
  }

  clearingPlaylist.value = true
  trackMutationInFlight.value = true

  router.delete(route('playlists.tracks.clear', props.playlist.id), {
    preserveScroll: true,
    preserveState: true,
    only: ['tracks', 'playlist'],
    onFinish: () => {
      clearingPlaylist.value = false
      trackMutationInFlight.value = false
      showClearPlaylistModal.value = false
    },
    onError: () => {
      clearingPlaylist.value = false
      trackMutationInFlight.value = false
    },
  })
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
        },
      },
    )
  } catch (err) {
    console.error('Error updating difficulty:', err)
    track.dificulty = oldValue
  } finally {
    loadingStates.value.updateDifficulty = null
  }
}

const editHint = (track) => {
  editingHint.value = track
}

// Add this event listener setup in your script
let removeRouterStartListener
let removeRouterFinishListener

onMounted(() => {
  removeRouterStartListener = router.on('start', (event) => {
    const only = event.detail?.visit?.only

    if (!only || only.length === 0) {
      loading.value = true
    }
  })

  removeRouterFinishListener = router.on('finish', () => {
    loading.value = false
  })

  // Check YouTube quota status
  const resetTime = localStorage.getItem('youtube_quota_reset_time')
  if (resetTime && new Date(resetTime) > new Date()) {
    const youtubeProvider = providers.value.find((p) => p.provider === 'youtube')
    if (youtubeProvider) {
      youtubeProvider.enabled = false
      providerErrors.value.youtube = {
        message: 'La recherche YouTube est temporairement indisponible - Le quota quotidien est dépassé',
        reset_time: resetTime,
        quota_exceeded: true,
      }
    }
  } else {
    localStorage.removeItem('youtube_quota_reset_time')
    localStorage.removeItem('provider_errors')
  }
})

onUnmounted(() => {
  removeRouterStartListener?.()
  removeRouterFinishListener?.()

  if (searchController.value) {
    searchController.value.abort()
  }
})

// Keep your existing loading ref - declared above with isSearching
</script>

<!-- Template remains the same -->
<template>
  <!-- Global loading overlay (only for non-search operations) -->
  <div v-if="loading && !isSearching" class="absolute inset-0 z-50 flex items-center justify-center bg-neutral-900/50 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-3 text-white">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-8 animate-spin">
        <path d="M21 12a9 9 0 1 1-6.219-8.56" />
      </svg>
      <span class="text-sm">{{ __('Loading') }}...</span>
    </div>
  </div>

  <!-- Make sure Card has relative positioning -->
  <Card class="relative">
    <template #header>
      <!-- Compact Title & Actions -->
      <div class="flex w-full items-center justify-between gap-2 py-2">
        <div class="flex items-center gap-3 pl-4 lg:pl-5">
          <h3 class="text-lg font-bold lg:text-xl">
            {{ __('Tracks manager') }}
          </h3>
        </div>
        <!-- Actions - Right-aligned, flush with edge -->
        <div class="flex items-center gap-1.5 pr-4 lg:pr-5">
          <button class="group flex items-center gap-1.5 rounded-lg border border-neutral-700/50 bg-neutral-800/50 px-3 py-1.5 text-sm font-medium text-neutral-200 transition-all duration-200 hover:border-teal-500/50 hover:bg-neutral-700 hover:text-white" @click="importingPlaylist = true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:scale-110">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
            </svg>
            <span class="hidden sm:inline">{{ __('Import') }}</span>
          </button>

          <a :href="route('playlists.export', playlist)" target="_blank" class="group flex items-center gap-1.5 rounded-lg border border-neutral-700/50 bg-neutral-800/50 px-3 py-1.5 text-sm font-medium text-neutral-200 transition-all duration-200 hover:border-teal-500/50 hover:bg-neutral-700 hover:text-white" :title="__('Export playlist to CSV file')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 transition-transform duration-200 group-hover:scale-110">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span class="hidden sm:inline">{{ __('Export to CSV') }}</span>
          </a>

          <button v-if="(playlist.total_tracks ?? 0) > 0" type="button" class="group flex items-center gap-1.5 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-1.5 text-sm font-medium text-red-300 transition-all duration-200 hover:border-red-400/50 hover:bg-red-500/20 disabled:pointer-events-none disabled:opacity-50" :disabled="clearingPlaylist" :title="__('Remove all tracks from this playlist')" @click="showClearPlaylistModal = true">
            <Icon name="delete" class="h-4 w-4" />
            <span class="hidden sm:inline">{{ __('Clear playlist') }}</span>
          </button>
        </div>
      </div>
    </template>

    <div class="p-4 lg:p-6">
      <!-- Search & Filters for Tracks List (moved here, visually distinct) -->
      <div class="mb-4 rounded-lg border border-neutral-700/30 bg-neutral-800/20 p-3 lg:p-4">
        <div class="space-y-3">
          <!-- Search Row -->
          <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="relative flex-1">
              <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search in playlist') + '...'" class="w-full" />
              <!-- Subtle loading indicator for search -->
              <div v-if="isSearching" class="pointer-events-none absolute right-3.5 top-1/2 z-20 -translate-y-1/2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="animate-spin text-teal-400">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
              </div>
            </div>

            <div class="flex items-center gap-3">
              <div class="whitespace-nowrap text-sm text-neutral-200">
                <template v-if="form.search || form.difficulty || form.provider || form.minUpvotes || form.minDownvotes || form.downvoteReason">
                  {{ tracks.total }} {{ __('results') }}
                  <span class="text-neutral-400">/ {{ playlist.total_tracks || tracks.total }} {{ __('tracks total') }}</span>
                </template>
                <template v-else> {{ tracks.total }} {{ __('tracks') }} </template>
              </div>

              <SelectInput v-model="form.paginate" class="w-24 text-xs">
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="15">15</option>
                <option :value="20">20</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </SelectInput>
            </div>
          </div>

          <!-- Filters Row -->
          <div class="flex flex-wrap items-center gap-2 border-t border-neutral-700/30 pt-3">
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-neutral-200">{{ __('Filter by') }}:</span>
            </div>

            <SelectInput v-model="form.difficulty" class="w-32 text-xs">
              <option value="">{{ __('All difficulties') }}</option>
              <option value="0">{{ __('Easy') }}</option>
              <option value="1">{{ __('Medium') }}</option>
              <option value="2">{{ __('Difficult') }}</option>
              <option value="3">{{ __('Expert') }}</option>
            </SelectInput>

            <SelectInput v-model="form.provider" class="w-32 text-xs">
              <option value="">{{ __('All providers') }}</option>
              <option value="youtube">YouTube</option>
              <option value="itunes">Apple Music</option>
              <option value="deezer">Deezer</option>
              <option value="local">Blinest</option>
              <!-- <option value="audius">Audius</option> -->
            </SelectInput>

            <SelectInput v-model="form.minUpvotes" class="w-40 text-xs">
              <option value="">{{ __('All positive votes') }}</option>
              <option value="10">{{ __('More than') }} 10 {{ __('positive votes') }}</option>
              <option value="20">{{ __('More than') }} 20 {{ __('positive votes') }}</option>
              <option value="30">{{ __('More than') }} 30 {{ __('positive votes') }}</option>
              <option value="50">{{ __('More than') }} 50 {{ __('positive votes') }}</option>
              <option value="100">{{ __('More than') }} 100 {{ __('positive votes') }}</option>
            </SelectInput>

            <SelectInput v-model="form.minDownvotes" class="w-40 text-xs">
              <option value="">{{ __('All negative votes') }}</option>
              <option value="10">{{ __('More than') }} 10 {{ __('negative votes') }}</option>
              <option value="20">{{ __('More than') }} 20 {{ __('negative votes') }}</option>
              <option value="30">{{ __('More than') }} 30 {{ __('negative votes') }}</option>
              <option value="50">{{ __('More than') }} 50 {{ __('negative votes') }}</option>
              <option value="100">{{ __('More than') }} 100 {{ __('negative votes') }}</option>
            </SelectInput>

            <SelectInput v-model="form.downvoteReason" class="w-48 text-xs">
              <option value="">{{ __('All downvote reasons') }}</option>
              <option value="sound_quality">{{ __('Poor sound quality') }}</option>
              <option value="difficulty">{{ __('Too difficult') }}</option>
              <option value="passage_choice">{{ __('Bad passage choice') }}</option>
              <option value="personal_taste">{{ __('Not my taste') }}</option>
              <option value="controversial_artist">{{ __('Controversial artist') }}</option>
              <option value="other">{{ __('Other reason') }}</option>
            </SelectInput>

            <button v-if="form.difficulty || form.provider || form.minUpvotes || form.minDownvotes || form.downvoteReason" class="ml-auto px-3 py-1.5 text-sm text-neutral-200 transition-colors hover:text-neutral-100" @click="clearFilters">
              {{ __('Clear filters') }}
            </button>
          </div>
        </div>
      </div>

      <!-- Add Tracks Section: search + upload (two columns from lg) -->
      <div class="mb-6 rounded-lg border border-neutral-700/50 bg-neutral-800/40 p-4 lg:p-5">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:items-start md:gap-5 lg:gap-6">
          <div class="min-w-0">
            <div class="mb-3">
              <h4 class="mb-1 text-sm font-semibold text-neutral-200">{{ __('Search for songs to add to your playlist') }}</h4>
              <p class="text-xs text-neutral-300 md:text-sm md:text-neutral-200">{{ __('Type the name of a song or artist to search on YouTube, Apple Music, Deezer and Blinest') }}</p>
            </div>
            <Dropdown placement="bottom-start" :auto-close="false" :overlay="false" class="w-full">
              <template #default>
                <TextInput class="w-full" v-model="search_online" prepend-icon="search" append-icon="cheveron-down" :loading="searchLoading" :placeholder="__('Search for a song') + '...'" />
              </template>

              <template #dropdown>
                <template v-if="search_online.length > 1">
                  <div class="border-b-2 border-neutral-700/50 bg-neutral-800 p-3 lg:p-4">
                    <p class="mb-3 text-sm text-neutral-200">{{ __('Select the platforms to search on') }}:</p>
                    <div class="flex flex-wrap gap-2 lg:gap-3">
                      <button v-for="provider in providers" :key="provider.id" @click="toggleProvider(provider)" class="flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-medium transition-all duration-200 lg:px-4 lg:py-2 lg:text-sm" :class="[provider.enabled ? 'bg-teal-500 text-white shadow-lg shadow-teal-500/20' : 'bg-neutral-700 text-neutral-400 hover:bg-neutral-600']">
                        <Icon :name="provider.provider" class="h-3 w-3 lg:h-4 lg:w-4" :class="{ 'opacity-50': !provider.enabled }" />
                        {{ provider.name }}
                      </button>
                    </div>
                  </div>

                  <div class="relative">
                    <!-- Show provider errors section -->
                    <template v-if="Object.keys(providerErrors).length > 0">
                      <div
                        v-for="(error, provider) in providerErrors"
                        :key="provider"
                        class="flex items-center gap-2 p-3 text-xs lg:gap-3 lg:p-4 lg:text-sm"
                        :class="{
                          'bg-red-400/10 text-red-400': error.quota_exceeded,
                          'bg-yellow-400/10 text-yellow-400': error.status_code === 503,
                          'bg-orange-400/10 text-orange-400': error.status_code === 400,
                          'bg-orange-400/10 text-orange-400': error.status_code === 404,
                        }"
                      >
                        <Icon :name="provider" class="h-4 w-4 lg:h-5 lg:w-5" />
                        <div class="flex flex-col">
                          <span class="max-w-[400px] font-medium">{{ error.message }}</span>
                          <span v-if="error.reset_time" class="text-xs opacity-90 lg:text-sm"> Le service sera disponible à nouveau le {{ new Date(error.reset_time).toLocaleString() }} </span>
                          <span v-else-if="error.status_code === 503" class="text-xs opacity-90 lg:text-sm"> Veuillez réessayer dans quelques minutes </span>
                        </div>
                      </div>
                    </template>

                    <!-- Results section -->
                    <ul v-if="results.filter((x) => activeProviders.includes(x.provider)).length > 0" class="max-h-[50vh] w-full divide-y divide-neutral-700/50 overflow-y-auto bg-neutral-800 lg:max-h-[480px] lg:w-[600px]">
                      <li v-for="result in results.filter((x) => activeProviders.includes(x.provider))" :key="result.id" class="group p-3 transition-colors duration-200 hover:bg-neutral-700/30 lg:p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                          <div class="flex min-w-0 flex-1 items-center gap-3">
                            <Icon :name="result.provider" :title="result.provider" class="h-5 w-5 flex-shrink-0 text-neutral-300" />

                            <div class="flex-shrink-0">
                              <MiniPlayer :key="`mini-player-results-${result.id}`" :track="result" />
                            </div>

                            <div class="flex min-w-0 flex-1 flex-col">
                              <span class="truncate text-sm font-medium text-neutral-200">{{ result.artist_name }}</span>
                              <span class="truncate text-sm text-neutral-200">{{ result.track_name }}</span>
                            </div>
                          </div>

                          <div class="flex items-center justify-end sm:justify-start">
                            <template v-if="loading">
                              <div class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 animate-spin">
                                  <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg>
                                <span>{{ __('Loading...') }}</span>
                              </div>
                            </template>

                            <template v-else>
                              <button v-if="!result.added" class="flex transform items-center gap-2 whitespace-nowrap rounded-lg bg-white px-4 py-2 text-sm font-medium text-neutral-800 shadow-lg transition-all duration-200 hover:scale-105 hover:bg-neutral-100 disabled:cursor-not-allowed disabled:opacity-50" type="button" @click="addTrack(result)" :disabled="loadingStates.addTrack === result.id">
                                <svg v-if="loadingStates.addTrack === result.id" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 animate-spin">
                                  <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg>
                                <Icon v-else name="plus" class="size-4" />
                                <span>{{ loadingStates.addTrack === result.id ? __('Adding...') : __('Add') }}</span>
                              </button>

                              <button v-else class="flex items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm text-neutral-200 transition-all duration-200 hover:text-red-500" type="button" @click="removeTrack(result)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span class="hidden sm:inline">{{ __('Already in playlist') }}</span>
                                <span class="sm:hidden">{{ __('Added') }}</span>
                              </button>
                            </template>
                          </div>
                        </div>
                      </li>
                    </ul>

                    <!-- No results message -->
                    <div v-else-if="!searchLoading" class="p-4 text-center text-sm text-neutral-200">
                      {{ __('Aucun résultat trouvé') }}
                    </div>

                    <!-- Loading message -->
                    <div v-if="searchLoading" class="p-4 text-center text-sm text-neutral-200">
                      <div class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 animate-spin">
                          <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                        </svg>
                        {{ __('Searching') }}...
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <div class="p-4 text-center lg:p-6">
                    <p class="mb-2 text-sm text-neutral-200">{{ __('Start typing to search for songs') }}</p>
                    <p class="text-sm text-neutral-300">{{ __('Example: "Bohemian Rhapsody" or "Queen"') }}</p>
                  </div>
                </template>
              </template>
            </Dropdown>
          </div>

          <div v-if="isModerator" class="min-w-0 border-t border-neutral-700/50 pt-4 md:border-l md:border-t-0 md:pl-5 md:pt-0 lg:pl-6">
            <div class="mb-3">
              <h4 class="mb-1 text-sm font-semibold text-neutral-200">{{ __('Upload a song from your computer') }}</h4>
              <p class="text-xs text-neutral-300 md:text-sm md:text-neutral-200">{{ __('Import an MP3 file from your computer. You can select a 30-second segment to use in the game') }}</p>
            </div>
            <UploadTrack :playlist="playlist" @new-track-uploaded="debouncedSearch" />
          </div>
        </div>
      </div>

      <!-- Mobile/Tablet: Card View -->
      <div v-if="tracks.data.length" class="block space-y-3 lg:hidden" :class="{ 'pointer-events-none opacity-50': loading }">
        <TrackCard v-for="track in tracks.data" :key="track.id" :track="track" :answer_types="answer_types" :loading-states="loadingStates" @edit-answer="editAnswer" @create-answer="createAnswer" @edit-hint="editHint" @update-difficulty="updateDificulty" @remove="removeTrack" />
      </div>

      <!-- Desktop: Table View -->
      <div v-if="tracks.data.length" class="hidden w-full overflow-x-auto rounded-lg border border-neutral-700/50 lg:block" :class="{ 'pointer-events-none opacity-50': loading }">
        <table class="w-full">
          <thead>
            <tr class="bg-neutral-800">
              <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-neutral-200"></th>
              <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-neutral-200">{{ __('Answers') }}</th>
              <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-neutral-200">{{ __('Hint') }}</th>
              <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-neutral-200">
                <Sortable field="dificulty" v-model="form.sortable">{{ __('Difficulty') }}</Sortable>
              </th>
              <th class="px-4 py-3 text-center text-sm font-semibold uppercase tracking-wider text-neutral-200">
                <Sortable field="votes" v-model="form.sortable">{{ __('Votes') }}</Sortable>
              </th>
              <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider text-neutral-200">
                <Sortable field="created_at" v-model="form.sortable">{{ __('Created at') }}</Sortable>
              </th>
              <th class="px-4 py-3 text-right text-sm font-semibold uppercase tracking-wider text-neutral-200">
                <span class="sr-only">{{ __('Actions') }}</span>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-700/50">
            <tr v-for="track in tracks.data" :key="track.id" class="group transition-colors duration-200 hover:bg-neutral-800/50">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <a target="_blank" :href="track.provider_url" class="flex flex-shrink-0 items-center transition-colors hover:text-teal-500" :title="track.provider">
                    <Icon :name="track.provider" class="h-4 w-4" />
                  </a>
                  <MiniPlayer :key="`mini-player-list-${track.id}`" :track="track" />
                </div>
              </td>
              <td class="px-4 py-3">
                <div class="min-w-[220px] max-w-[300px] space-y-1.5">
                  <div v-for="answer in track.answers" :key="answer.id" class="group/answer flex cursor-pointer items-center justify-between rounded-md bg-neutral-700/30 px-2.5 py-1.5 transition-all hover:bg-neutral-700/50 hover:shadow-sm" @click="editAnswer(track, answer)">
                    <div class="flex min-w-0 flex-1 items-center gap-2">
                      <span class="whitespace-nowrap text-sm font-semibold text-neutral-200">{{ __(answer.type.name) }}:</span>
                      <span class="truncate text-sm text-neutral-200">{{ answer.value }}</span>
                      <span v-if="answer.aliases && answer.aliases.length" class="shrink-0 rounded bg-neutral-600/60 px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wide text-neutral-300" :title="answer.aliases.join('\n')"> +{{ answer.aliases.length }} {{ __('alt.') }} </span>
                    </div>
                    <span class="ml-2 whitespace-nowrap text-xs font-semibold text-teal-400">{{ answer.score }}pts</span>
                  </div>
                  <button class="flex w-full items-center gap-1.5 rounded-md border border-dashed border-neutral-600 px-2.5 py-1.5 text-sm text-neutral-200 transition-colors hover:border-neutral-500 hover:bg-neutral-700/20 hover:text-neutral-100" @click="createAnswer(track)">
                    <Icon name="plus" class="h-3 w-3" />
                    <span>{{ __('Add an answer') }}</span>
                  </button>
                </div>
              </td>
              <td class="max-w-[200px] px-4 py-3">
                <div class="flex min-w-0 flex-col gap-1.5">
                  <div v-if="track.hint" class="flex items-start gap-2 rounded-md border border-yellow-400/20 bg-yellow-400/5 px-2.5 py-1.5" :title="track.hint">
                    <Icon name="hint" class="mt-0.5 h-3.5 w-3.5 flex-shrink-0 text-yellow-400" />
                    <span class="flex-1 text-sm text-neutral-200">{{ track.hint }}</span>
                  </div>
                  <button type="button" class="flex items-center gap-1.5 text-sm text-neutral-200 transition-colors hover:text-neutral-100" @click="editHint(track)">
                    <Icon :name="track.hint ? 'edit' : 'plus'" class="h-3 w-3" />
                    <span>{{ track.hint ? __('Edit hint') : __('Add hint') }}</span>
                  </button>
                </div>
              </td>
              <td class="px-4 py-3">
                <SelectInput v-model="track.dificulty" :error="$page.props.errors.dificulty" @change="updateDificulty($event, track)" class="w-32 text-sm" :disabled="loadingStates.updateDifficulty === track.id">
                  <option :value="0">{{ __('Easy') }}</option>
                  <option :value="1">{{ __('Medium') }}</option>
                  <option :value="2">{{ __('Difficult') }}</option>
                  <option :value="3">{{ __('Expert') }}</option>
                </SelectInput>
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-col items-center gap-2 text-sm">
                  <div class="flex items-center justify-center gap-3">
                    <div class="flex items-center gap-1 text-teal-400">
                      <Icon name="thumb-up" class="h-4 w-4" />
                      <span>{{ track.up_votes }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-red-400">
                      <Icon name="thumb-down" class="h-4 w-4" />
                      <span>{{ track.down_votes }}</span>
                    </div>
                  </div>
                  <div v-if="track.downvote_breakdown && Object.keys(track.downvote_breakdown).length" class="flex max-w-[14rem] flex-wrap justify-center gap-1">
                    <span v-for="(count, reason) in track.downvote_breakdown" :key="reason" class="rounded bg-red-400/10 px-1.5 py-0.5 text-[10px] font-medium text-red-300">
                      {{ downvoteReasonLabel(reason) }} ({{ count }})
                    </span>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 text-sm text-neutral-200">
                {{ track.created_at }}
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  <button type="button" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-400 transition-colors hover:bg-red-400/10 hover:text-red-300 disabled:opacity-50" :title="__('Delete')" :disabled="loadingStates.removeTrack === track.id" @click="removeTrack(track)">
                    <svg v-if="loadingStates.removeTrack === track.id" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-spin">
                      <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                    </svg>
                    <Icon v-else name="delete" class="h-4 w-4 text-red-400" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="flex flex-col items-center justify-center py-8 text-sm text-neutral-200 lg:py-12 lg:text-base">
        <Icon name="music-note" class="mb-3 h-8 w-8 lg:mb-4 lg:h-12 lg:w-12" />
        {{ __('No tracks found') }}
      </div>

      <Pagination class="mt-4 lg:mt-6" :links="tracks.links" />

      <TrackAnswerForm v-if="creatingAnswer || (editingAnswer && selectedAnswer)" :answer="selectedAnswer" :answer_types="answer_types" :show="editingAnswer || creatingAnswer" max-width="md" @close="closeModal" />

      <TrackHintForm v-if="editingHint" :track="{ ...editingHint, playlist_id: playlist.id }" :show="!!editingHint" max-width="md" @close="closeModal" />
    </div>
  </Card>

  <ImportPlaylist v-if="importingPlaylist" @close="importingPlaylist = false" :playlist="playlist" />

  <!-- Delete Track Confirmation Modal -->
  <ConfirmModal :show="showDeleteTrackModal" :title="__('Delete track')" :message="__('Are you sure you want to delete this track? This action cannot be undone.')" :confirm-text="__('Delete')" :cancel-text="__('Cancel')" variant="danger" @close="closeDeleteTrackModal" @confirm="confirmDeleteTrack" />

  <ConfirmModal :show="showClearPlaylistModal" :title="__('Clear playlist')" :message="__('Are you sure you want to remove all tracks from this playlist? This action cannot be undone.')" :confirm-text="__('Clear playlist')" :cancel-text="__('Cancel')" variant="danger" @close="showClearPlaylistModal = false" @confirm="confirmClearPlaylist" />
</template>
