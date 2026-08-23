<script setup>
import { ref, watch } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import debounce from 'lodash/debounce'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  tracks: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
})

const page = usePage()
const translate = useTranslate()
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.per_page || 20)
const sortBy = ref(props.filters?.sort_by || 'created_at')
const sortDirection = ref(props.filters?.sort_direction || 'desc')
const editingTrackId = ref(null)

const editForm = useForm({
  track_name: '',
  artist_name: '',
})

const performSearch = debounce(() => {
  router.get(
    route('moderation.tracks.index'),
    {
      search: search.value,
      per_page: perPage.value,
      sort_by: sortBy.value,
      sort_direction: sortDirection.value,
    },
    { preserveState: true, preserveScroll: true, replace: true },
  )
}, 300)

watch(search, () => {
  performSearch()
})

watch([perPage, sortBy, sortDirection], () => {
  performSearch()
})

const formatDate = (value) => {
  if (!value) {
    return '—'
  }

  return new Date(value).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const toggleSort = (column) => {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDirection.value = 'asc'
  }
}

const sortIndicator = (column) => {
  if (sortBy.value !== column) {
    return ''
  }

  return sortDirection.value === 'asc' ? '↑' : '↓'
}

const startEditing = (track) => {
  editingTrackId.value = track.id
  editForm.clearErrors()
  editForm.track_name = track.track_name
  editForm.artist_name = track.artist_name
}

const cancelEditing = () => {
  editingTrackId.value = null
  editForm.reset()
  editForm.clearErrors()
}

const saveEdit = (track) => {
  editForm.put(route('moderation.tracks.update', track.id), {
    preserveScroll: true,
    onSuccess: () => {
      editingTrackId.value = null
      editForm.reset()
    },
  })
}

const deleteTrack = (track) => {
  if (!confirm(translate('Moderation local track delete confirm', { name: track.track_name }))) {
    return
  }

  router.delete(route('moderation.tracks.destroy', track.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Layout :title="__('Moderation local tracks title')">
    <div class="space-y-6">
      <div v-if="page.props.flash?.success" class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        {{ page.props.flash.success }}
      </div>

      <div v-if="errors.error" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
        {{ errors.error }}
      </div>

      <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div class="flex-1">
          <label for="local-track-search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-white/50">
            {{ __('Moderation search local tracks') }}
          </label>
          <div class="relative">
            <input id="local-track-search" v-model="search" type="search" :placeholder="__('Moderation search local tracks placeholder')" class="w-full rounded-xl border border-white/10 bg-black/30 py-2.5 pl-10 pr-4 text-white placeholder-white/35 focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
            <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <div class="flex items-end gap-3">
          <div>
            <label for="local-track-per-page" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-white/50">
              {{ __('Per page') }}
            </label>
            <select id="local-track-per-page" v-model.number="perPage" class="rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
              <option :value="10">10</option>
              <option :value="20">20</option>
              <option :value="50">50</option>
            </select>
          </div>
          <p class="pb-2.5 text-sm text-white/45">{{ __('Moderation local tracks count', { count: tracks.total }) }}</p>
        </div>
      </div>

      <div v-if="tracks.data.length === 0" class="rounded-xl border border-dashed border-white/10 px-6 py-12 text-center">
        <h3 class="text-lg font-medium text-white">{{ __('Moderation no local tracks') }}</h3>
        <p class="mt-2 text-sm text-white/45">{{ __('Moderation no local tracks hint') }}</p>
      </div>

      <div v-else class="overflow-x-auto rounded-xl border border-white/10">
        <table class="min-w-full divide-y divide-white/10 text-sm">
          <thead class="bg-black/30 text-left text-xs uppercase tracking-wide text-white/45">
            <tr>
              <th class="px-4 py-3">{{ __('ID') }}</th>
              <th class="px-4 py-3">{{ __('Moderation artwork') }}</th>
              <th class="cursor-pointer px-4 py-3" @click="toggleSort('track_name')">
                {{ __('Track') }}
                <span v-if="sortIndicator('track_name')" class="ml-1">{{ sortIndicator('track_name') }}</span>
              </th>
              <th class="cursor-pointer px-4 py-3" @click="toggleSort('artist_name')">
                {{ __('Artist') }}
                <span v-if="sortIndicator('artist_name')" class="ml-1">{{ sortIndicator('artist_name') }}</span>
              </th>
              <th class="px-4 py-3">{{ __('Moderation uploader') }}</th>
              <th class="px-4 py-3">{{ __('Audio') }}</th>
              <th class="cursor-pointer px-4 py-3" @click="toggleSort('playlists_usage_count')">
                {{ __('Moderation playlists usage') }}
                <span v-if="sortIndicator('playlists_usage_count')" class="ml-1">{{ sortIndicator('playlists_usage_count') }}</span>
              </th>
              <th class="cursor-pointer px-4 py-3" @click="toggleSort('created_at')">
                {{ __('Added on') }}
                <span v-if="sortIndicator('created_at')" class="ml-1">{{ sortIndicator('created_at') }}</span>
              </th>
              <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5 bg-black/20">
            <tr v-for="track in tracks.data" :key="track.id" class="transition hover:bg-white/5">
              <td class="px-4 py-3 font-mono text-xs text-white/55">#{{ track.id }}</td>
              <td class="px-4 py-3">
                <img v-if="track.artwork_url" :src="track.artwork_url" :alt="track.track_name" class="h-12 w-12 rounded-lg border border-white/10 object-cover" />
                <span v-else class="text-xs text-white/45">{{ __('Moderation no artwork') }}</span>
              </td>
              <td class="px-4 py-3">
                <input v-if="editingTrackId === track.id" v-model="editForm.track_name" type="text" class="w-full min-w-[10rem] rounded-lg border border-white/10 bg-black/30 px-2 py-1.5 text-white focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
                <span v-else class="font-medium text-white">{{ track.track_name }}</span>
                <p v-if="editForm.errors.track_name && editingTrackId === track.id" class="mt-1 text-xs text-red-300">
                  {{ editForm.errors.track_name }}
                </p>
              </td>
              <td class="px-4 py-3">
                <input v-if="editingTrackId === track.id" v-model="editForm.artist_name" type="text" class="w-full min-w-[10rem] rounded-lg border border-white/10 bg-black/30 px-2 py-1.5 text-white focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
                <span v-else class="text-white/85">{{ track.artist_name }}</span>
                <p v-if="editForm.errors.artist_name && editingTrackId === track.id" class="mt-1 text-xs text-red-300">
                  {{ editForm.errors.artist_name }}
                </p>
              </td>
              <td class="px-4 py-3">
                <Link v-if="track.uploader" :href="track.uploader.profile_url" class="group flex items-center gap-2">
                  <img v-if="track.uploader.photo" :src="track.uploader.photo" :alt="track.uploader.name" class="h-8 w-8 rounded-full object-cover" />
                  <div v-else class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-white">
                    {{ track.uploader.name.charAt(0).toUpperCase() }}
                  </div>
                  <span class="truncate text-white/75 group-hover:text-brand-secondary">{{ track.uploader.name }}</span>
                </Link>
                <span v-else class="text-white/45">—</span>
              </td>
              <td class="px-4 py-3">
                <audio v-if="track.audio_url" :src="track.audio_url" controls preload="none" class="h-8 max-w-[12rem]" />
                <span v-else class="text-xs text-white/45">{{ __('Moderation no audio') }}</span>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full border border-white/10 px-2 py-0.5 text-xs text-white/70">
                  {{ track.playlists_usage_count }}
                </span>
              </td>
              <td class="px-4 py-3 text-white/70">{{ formatDate(track.created_at) }}</td>
              <td class="px-4 py-3">
                <div class="flex justify-end gap-2">
                  <template v-if="editingTrackId === track.id">
                    <LoadingButton type="button" class="rounded-lg border border-emerald-500/30 px-3 py-1.5 text-xs text-emerald-300 transition hover:bg-emerald-500/10" :loading="editForm.processing" @click="saveEdit(track)">
                      {{ __('Save') }}
                    </LoadingButton>
                    <button type="button" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-white/75 transition hover:text-white" @click="cancelEditing">
                      {{ __('Cancel') }}
                    </button>
                  </template>
                  <template v-else>
                    <button type="button" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-white/75 transition hover:border-brand-primary/30 hover:text-white" @click="startEditing(track)">
                      {{ __('Edit') }}
                    </button>
                    <button type="button" class="rounded-lg border border-red-500/30 px-3 py-1.5 text-xs text-red-300 transition hover:bg-red-500/10" @click="deleteTrack(track)">
                      {{ __('Delete') }}
                    </button>
                  </template>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination v-if="tracks.links?.length > 3" :links="tracks.links" />
    </div>
  </Layout>
</template>
