<script setup>
import { ref, watch } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import debounce from 'lodash/debounce'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  moderators: {
    type: Object,
    required: true,
  },
  stats: {
    type: Object,
    required: true,
  },
  coverage: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
})

const page = usePage()
const translate = useTranslate()
const search = ref(props.filters?.search || '')
const inactiveOnly = ref(Boolean(props.filters?.inactive_only))
const expandedModeratorId = ref(null)
const revokingKey = ref(null)
const revokeForm = useForm({})

const performSearch = debounce(() => {
  router.get(
    route('moderation.moderators.index'),
    {
      search: search.value || undefined,
      inactive_only: inactiveOnly.value ? 1 : undefined,
      per_page: props.filters?.per_page || 20,
    },
    { preserveState: true, preserveScroll: true, replace: true },
  )
}, 300)

watch(search, () => {
  performSearch()
})

watch(inactiveOnly, () => {
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

const formatDaysAgo = (days) => {
  if (days === null || days === undefined) {
    return translate('Moderation moderators no activity')
  }

  if (days === 0) {
    return translate('Moderation moderators today')
  }

  return translate('Moderation moderators days ago', { count: days })
}

const activityClass = (moderator) => {
  if (moderator.is_inactive) {
    return 'text-amber-300'
  }

  if (moderator.days_since_activity !== null && moderator.days_since_activity <= 30) {
    return 'text-emerald-300'
  }

  return 'text-white/70'
}

const toggleModerator = (moderatorId) => {
  expandedModeratorId.value = expandedModeratorId.value === moderatorId ? null : moderatorId
}

const revokeRoomAccess = (moderator, room) => {
  if (!confirm(translate('Moderation moderators revoke room confirm', { name: moderator.name, room: room.name }))) {
    return
  }

  const key = `room-${moderator.id}-${room.id}`
  revokingKey.value = key

  revokeForm.delete(route('moderation.moderators.rooms.detach', { room: room.id, user: moderator.id }), {
    preserveScroll: true,
    onFinish: () => {
      revokingKey.value = null
    },
  })
}

const revokePlaylistAccess = (moderator, playlist) => {
  if (!confirm(translate('Moderation moderators revoke playlist confirm', { name: moderator.name, playlist: playlist.name }))) {
    return
  }

  const key = `playlist-${moderator.id}-${playlist.id}`
  revokingKey.value = key

  revokeForm.delete(route('moderation.moderators.playlists.detach', { playlist: playlist.id, user: moderator.id }), {
    preserveScroll: true,
    onFinish: () => {
      revokingKey.value = null
    },
  })
}

const isRevoking = (key) => revokingKey.value === key && revokeForm.processing
</script>

<template>
  <Layout :title="__('Moderation moderators title')">
    <div class="space-y-6">
      <div v-if="page.props.flash?.success" class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        {{ page.props.flash.success }}
      </div>

      <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 xl:grid-cols-8">
        <div class="rounded-xl border border-white/10 bg-black/20 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Moderation moderators stats total') }}</p>
          <p class="mt-1 text-2xl font-bold text-white">{{ stats.total_moderators }}</p>
        </div>
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-emerald-300/70">{{ __('Moderation moderators stats active') }}</p>
          <p class="mt-1 text-2xl font-bold text-emerald-300">{{ stats.active_moderators }}</p>
        </div>
        <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-amber-300/70">{{ __('Moderation moderators stats inactive') }}</p>
          <p class="mt-1 text-2xl font-bold text-amber-300">{{ stats.inactive_moderators }}</p>
        </div>
        <div class="rounded-xl border border-red-500/20 bg-red-500/5 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-red-300/70">{{ __('Moderation moderators stats rooms without') }}</p>
          <p class="mt-1 text-2xl font-bold text-red-300">{{ stats.rooms_without_moderators }}</p>
        </div>
        <div class="rounded-xl border border-red-500/20 bg-red-500/5 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-red-300/70">{{ __('Moderation moderators stats playlists without') }}</p>
          <p class="mt-1 text-2xl font-bold text-red-300">{{ stats.playlists_without_moderators }}</p>
        </div>
        <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-amber-300/70">{{ __('Moderation moderators stats stale playlists') }}</p>
          <p class="mt-1 text-2xl font-bold text-amber-300">{{ stats.stale_public_playlists }}</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-black/20 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Moderation moderators stats public rooms') }}</p>
          <p class="mt-1 text-2xl font-bold text-white">{{ stats.public_rooms }}</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-black/20 p-4 xl:col-span-1">
          <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Moderation moderators stats public playlists') }}</p>
          <p class="mt-1 text-2xl font-bold text-white">{{ stats.public_playlists }}</p>
        </div>
      </div>

      <div class="grid gap-4 xl:grid-cols-3">
        <section class="rounded-xl border border-red-500/20 bg-red-500/5 p-4">
          <h3 class="text-sm font-semibold text-red-200">{{ __('Moderation moderators coverage rooms title') }}</h3>
          <p class="mt-1 text-xs text-white/45">{{ __('Moderation moderators coverage rooms hint') }}</p>
          <ul v-if="coverage.rooms_without_moderators.length" class="mt-3 max-h-56 space-y-2 overflow-y-auto">
            <li v-for="room in coverage.rooms_without_moderators" :key="`room-gap-${room.id}`" class="rounded-lg border border-white/10 bg-black/20 px-3 py-2 text-sm">
              <p class="font-medium text-white">{{ room.name }}</p>
              <p class="text-xs text-white/45">{{ __('Moderation moderators owner label') }}: {{ room.owner_name || '—' }}</p>
            </li>
          </ul>
          <p v-else class="mt-3 text-sm text-white/45">{{ __('Moderation moderators coverage none') }}</p>
        </section>

        <section class="rounded-xl border border-red-500/20 bg-red-500/5 p-4">
          <h3 class="text-sm font-semibold text-red-200">{{ __('Moderation moderators coverage playlists title') }}</h3>
          <p class="mt-1 text-xs text-white/45">{{ __('Moderation moderators coverage playlists hint') }}</p>
          <ul v-if="coverage.playlists_without_moderators.length" class="mt-3 max-h-56 space-y-2 overflow-y-auto">
            <li v-for="playlist in coverage.playlists_without_moderators" :key="`playlist-gap-${playlist.id}`" class="rounded-lg border border-white/10 bg-black/20 px-3 py-2 text-sm">
              <p class="font-medium text-white">{{ playlist.name }}</p>
              <p class="text-xs text-white/45">{{ __('Moderation moderators owner label') }}: {{ playlist.owner_name || '—' }}</p>
            </li>
          </ul>
          <p v-else class="mt-3 text-sm text-white/45">{{ __('Moderation moderators coverage none') }}</p>
        </section>

        <section class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">
          <h3 class="text-sm font-semibold text-amber-200">{{ __('Moderation moderators coverage stale title') }}</h3>
          <p class="mt-1 text-xs text-white/45">{{ __('Moderation moderators coverage stale hint') }}</p>
          <ul v-if="coverage.stale_public_playlists.length" class="mt-3 max-h-56 space-y-2 overflow-y-auto">
            <li v-for="playlist in coverage.stale_public_playlists" :key="`stale-${playlist.id}`" class="rounded-lg border border-white/10 bg-black/20 px-3 py-2 text-sm">
              <p class="font-medium text-white">{{ playlist.name }}</p>
              <p class="text-xs text-white/45">{{ __('Moderation moderators last activity') }}: {{ formatDate(playlist.last_activity_at) }}</p>
            </li>
          </ul>
          <p v-else class="mt-3 text-sm text-white/45">{{ __('Moderation moderators coverage none') }}</p>
        </section>
      </div>

      <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div class="flex-1">
          <label for="moderator-search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-white/50">
            {{ __('Moderation search moderators') }}
          </label>
          <div class="relative">
            <input id="moderator-search" v-model="search" type="search" :placeholder="__('Moderation search moderators placeholder')" class="w-full rounded-xl border border-white/10 bg-black/30 py-2.5 pl-10 pr-4 text-white placeholder-white/35 focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
            <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <label class="flex items-center gap-2 text-sm text-white/70">
          <input v-model="inactiveOnly" type="checkbox" class="rounded border-white/20 bg-black/30 text-brand-primary focus:ring-brand-primary/30" />
          {{ __('Moderation moderators inactive only') }}
        </label>
      </div>

      <p class="text-sm text-white/45">{{ __('Moderation moderators sorted hint') }}</p>
      <p class="text-sm text-white/45">{{ __('Moderation moderators count', { count: moderators.total }) }}</p>

      <div v-if="moderators.data.length === 0" class="rounded-xl border border-dashed border-white/10 px-6 py-12 text-center">
        <h3 class="text-lg font-medium text-white">{{ __('Moderation moderators no results') }}</h3>
        <p class="mt-2 text-sm text-white/45">{{ __('Moderation moderators no results description') }}</p>
      </div>

      <div v-else class="overflow-x-auto rounded-xl border border-white/10">
        <table class="min-w-full divide-y divide-white/10 text-sm">
          <thead class="bg-black/30 text-left text-xs uppercase tracking-wide text-white/45">
            <tr>
              <th class="px-4 py-3">{{ __('User') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last activity') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last connection') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last message') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last ban') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last track') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last local track') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators last score') }}</th>
              <th class="px-4 py-3">{{ __('Moderation moderators assignments') }}</th>
              <th class="px-4 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5 bg-black/20">
            <template v-for="moderator in moderators.data" :key="moderator.id">
              <tr class="transition hover:bg-white/5" :class="{ 'bg-amber-500/5': moderator.is_inactive }">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <img :src="moderator.photo" :alt="moderator.name" class="h-10 w-10 rounded-full border border-white/10 object-cover" />
                    <div>
                      <div class="flex flex-wrap items-center gap-2">
                        <Link :href="moderator.profile_url" class="font-medium text-white hover:text-brand-accent">{{ moderator.name }}</Link>
                        <span class="text-xs text-white/45">#{{ moderator.id }}</span>
                        <span v-if="moderator.is_inactive" class="rounded-full border border-amber-500/30 bg-amber-500/10 px-2 py-0.5 text-[10px] uppercase tracking-wide text-amber-300">
                          {{ __('Moderation moderators inactive badge') }}
                        </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3" :class="activityClass(moderator)">
                  <p>{{ formatDate(moderator.last_activity_at) }}</p>
                  <p class="text-xs">{{ formatDaysAgo(moderator.days_since_activity) }}</p>
                </td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_connection_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_message_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_ban_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_track_added_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_local_track_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(moderator.last_score_at) }}</td>
                <td class="px-4 py-3 text-white/70">{{ moderator.rooms_count }} / {{ moderator.playlists_count }}</td>
                <td class="px-4 py-3 text-right">
                  <button type="button" class="text-xs text-brand-accent hover:underline" @click="toggleModerator(moderator.id)">
                    {{ expandedModeratorId === moderator.id ? __('Close') : __('Details') }}
                  </button>
                </td>
              </tr>
              <tr v-if="expandedModeratorId === moderator.id">
                <td colspan="10" class="bg-black/30 px-4 py-4">
                  <div class="grid gap-4 lg:grid-cols-2">
                    <section>
                      <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-white/45">{{ __('Moderation moderators rooms') }}</h4>
                      <ul v-if="moderator.rooms.length" class="space-y-2">
                        <li v-for="room in moderator.rooms" :key="`room-${room.id}`" class="flex items-center justify-between gap-3 rounded-lg border border-white/10 bg-black/30 px-3 py-2">
                          <div class="min-w-0">
                            <p class="truncate text-sm text-white">{{ room.name }}</p>
                            <p v-if="room.is_owner" class="text-xs text-brand-accent">{{ __('Moderation moderators owner badge') }}</p>
                          </div>
                          <LoadingButton v-if="!room.is_owner" type="button" class="retro-nav-btn text-xs" :loading="isRevoking(`room-${moderator.id}-${room.id}`)" @click.stop="revokeRoomAccess(moderator, room)">
                            {{ __('Moderation moderators revoke') }}
                          </LoadingButton>
                        </li>
                      </ul>
                      <p v-else class="text-sm text-white/45">—</p>
                    </section>

                    <section>
                      <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-white/45">{{ __('Moderation moderators playlists') }}</h4>
                      <ul v-if="moderator.playlists.length" class="space-y-2">
                        <li v-for="playlist in moderator.playlists" :key="`playlist-${playlist.id}`" class="flex items-center justify-between gap-3 rounded-lg border border-white/10 bg-black/30 px-3 py-2">
                          <div class="min-w-0">
                            <p class="truncate text-sm text-white">{{ playlist.name }}</p>
                            <p v-if="playlist.is_owner" class="text-xs text-brand-accent">{{ __('Moderation moderators owner badge') }}</p>
                          </div>
                          <LoadingButton v-if="!playlist.is_owner" type="button" class="retro-nav-btn text-xs" :loading="isRevoking(`playlist-${moderator.id}-${playlist.id}`)" @click.stop="revokePlaylistAccess(moderator, playlist)">
                            {{ __('Moderation moderators revoke') }}
                          </LoadingButton>
                        </li>
                      </ul>
                      <p v-else class="text-sm text-white/45">—</p>
                    </section>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <Pagination v-if="moderators.data.length" :links="moderators.links" />
    </div>
  </Layout>
</template>
