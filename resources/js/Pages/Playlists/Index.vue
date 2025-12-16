<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  filters: Object,
  playlists: Object,
})

const form = useForm({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  form,
  throttle(() => {
    router.get('/playlists', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>

<template>
  <Head :title="__('My Playlists')" />
  <AppLayout>
    <!-- Header Section -->
    <header class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-neutral-100 sm:text-3xl">{{ __('My Playlists') }}</h1>
        <p v-if="playlists && playlists.total" class="mt-1 text-sm text-neutral-400">
          {{ __('Total') }}: <span class="font-medium text-neutral-200">{{ playlists.total }}</span> {{ __('playlists') }}
        </p>
      </div>
      <Link 
        class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/25 transition-all duration-200 hover:from-teal-400 hover:to-teal-500 hover:shadow-xl hover:shadow-teal-500/40 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900 active:scale-100" 
        :href="route('playlists.create')"
        :aria-label="__('Create a new playlist')"
      >
        <Icon name="plus" class="h-5 w-5 text-white transition-transform duration-200 group-hover:rotate-90" aria-hidden="true" />
        <span>{{ __('Create a playlist') }}</span>
      </Link>
    </header>

    <!-- Search Section -->
    <div class="mb-6">
      <label for="search-playlists" class="sr-only">{{ __('Search playlists') }}</label>
      <Card>
        <div class="p-4">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex-1">
              <text-input 
                id="search-playlists"
                v-model="form.search" 
                prepend-icon="search" 
                :placeholder="__('Search playlists') + '...'"
                class="w-full bg-neutral-900/50 border-neutral-600/50 focus:border-teal-500/50"
                :aria-label="__('Search playlists by name or description')"
              />
            </div>
            <button 
              v-if="form.search" 
              class="px-4 py-2 text-sm text-neutral-400 hover:text-neutral-200 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800 rounded"
              @click="reset"
              :aria-label="__('Reset search')"
            >
              {{ __('Reset') }}
            </button>
          </div>
        </div>
      </Card>
    </div>

    <!-- Desktop Table View (lg and above) -->
    <Card class="hidden lg:block">
      <div class="overflow-x-auto">
        <table class="w-full" role="table" :aria-label="__('Playlists list')">
          <thead>
            <tr class="text-left">
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Name') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Description') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Tracks') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Owner') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Moderators') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200" colspan="2">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="playlist in playlists.data" :key="playlist.id" class="group border-t border-neutral-700/50 transition-colors hover:bg-neutral-800/30">
              <td class="px-4 py-4">
                <Link 
                  class="flex items-center focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800 rounded" 
                  :href="route('playlists.edit', playlist.id)"
                  :aria-label="`${__('Edit playlist')}: ${playlist.name}`"
                >
                  <div class="flex min-w-0 flex-col">
                    <span class="font-medium text-neutral-100 group-hover:text-teal-400 transition-colors truncate">{{ playlist.name }}</span>
                    <icon v-if="playlist.deleted_at" name="trash" class="mt-1 h-3 w-3 flex-shrink-0 fill-red-400" aria-hidden="true" />
                  </div>
                </Link>
              </td>
              <td class="px-4 py-4">
                <p v-if="playlist.description" class="max-w-md truncate text-sm text-neutral-300">{{ playlist.description }}</p>
                <span v-else class="text-sm text-neutral-400">{{ __('No description') }}</span>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-1.5">
                  <Icon name="music" class="h-4 w-4 text-neutral-400" aria-hidden="true" />
                  <span class="text-sm font-medium text-neutral-200">{{ playlist.total_tracks || 0 }}</span>
                </div>
              </td>
              <td class="px-4 py-4">
                <span class="text-sm text-neutral-200">{{ playlist.owner.name }}</span>
              </td>
              <td class="px-4 py-4">
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="moderator in playlist.moderators.slice(0, 3)" 
                    :key="moderator.id"
                    class="badge bg-neutral-700/50 text-sm text-neutral-200"
                  >
                    {{ moderator.name }}
                  </span>
                  <span 
                    v-if="playlist.moderators.length > 3"
                    class="text-sm text-neutral-400"
                    :aria-label="__('More moderators')"
                  >
                    +{{ playlist.moderators.length - 3 }}
                  </span>
                  <span v-if="playlist.moderators.length === 0" class="text-sm text-neutral-400">{{ __('None') }}</span>
                </div>
              </td>
              <td class="px-4 py-4">
                <span class="text-sm text-neutral-400">{{ __('Updated') }}: {{ new Date(playlist.updated_at).toLocaleDateString() }}</span>
              </td>
              <td class="w-px px-4">
                <Link 
                  class="flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800 rounded" 
                  :href="route('playlists.edit', playlist.id)"
                  :aria-label="`${__('Edit playlist')}: ${playlist.name}`"
                >
                  <icon name="cheveron-right" class="block h-6 w-6 fill-neutral-400 transition-colors group-hover:fill-teal-400" aria-hidden="true" />
                </Link>
              </td>
            </tr>
            <tr v-if="playlists && playlists.data.length === 0">
              <td class="border-t border-neutral-700/50 px-4 py-8 text-center text-sm text-neutral-300" colspan="7">
                {{ __('No playlists found') }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div v-if="playlists && playlists.links && playlists.links.length > 3" class="border-t border-neutral-700/50 px-4 py-6 bg-neutral-800/30">
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
          <div class="text-sm text-neutral-300" role="status" aria-live="polite">
            <span class="font-medium text-neutral-200">{{ playlists.from || 0 }}</span>
            <span class="mx-1">{{ __('to') }}</span>
            <span class="font-medium text-neutral-200">{{ playlists.to || 0 }}</span>
            <span class="mx-1">{{ __('of') }}</span>
            <span class="font-medium text-neutral-200">{{ playlists.total || 0 }}</span>
            <span class="ml-1">{{ __('playlists') }}</span>
          </div>
          <nav :aria-label="__('Pagination')">
            <Pagination :links="playlists.links" />
          </nav>
        </div>
      </div>
    </Card>

    <!-- Mobile/Tablet Card View (below lg) -->
    <div class="grid gap-4 sm:grid-cols-2 lg:hidden">
      <Card 
        v-for="playlist in playlists.data" 
        :key="playlist.id"
        class="group transition-all hover:shadow-xl hover:border-teal-500/50"
      >
        <Link 
          :href="route('playlists.edit', playlist.id)"
          class="block focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800 rounded"
          :aria-label="`${__('Edit playlist')}: ${playlist.name}`"
        >
          <div class="p-4 lg:p-5">
            <!-- Header -->
            <div class="mb-4 flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <h3 class="mb-1 truncate text-lg font-bold text-neutral-200 transition-colors group-hover:text-teal-400">
                  {{ playlist.name }}
                </h3>
                <p v-if="playlist.description" class="line-clamp-2 text-sm text-neutral-300">
                  {{ playlist.description }}
                </p>
              </div>
              <Icon 
                v-if="playlist.deleted_at" 
                name="trash" 
                class="h-4 w-4 flex-shrink-0 text-red-400" 
                aria-hidden="true"
              />
            </div>

            <!-- Stats -->
            <div class="mb-4 flex items-center gap-4 text-xs text-neutral-400">
              <div class="flex items-center gap-1.5">
                <Icon name="music" class="h-3.5 w-3.5" aria-hidden="true" />
                <span>{{ playlist.total_tracks || 0 }} {{ __('tracks') }}</span>
              </div>
              <div class="flex items-center gap-1.5">
                <Icon name="users" class="h-3.5 w-3.5" aria-hidden="true" />
                <span>{{ playlist.moderators.length }} {{ __('Moderators') }}</span>
              </div>
            </div>

            <!-- Owner & Moderators -->
            <div class="space-y-2 border-t border-neutral-700/50 pt-3">
              <div class="flex items-center gap-2 text-xs">
                <span class="text-neutral-500">{{ __('Owner') }}:</span>
                <span class="text-neutral-200">{{ playlist.owner.name }}</span>
              </div>
              <div v-if="playlist.moderators.length > 0" class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs text-neutral-500">{{ __('Moderators') }}:</span>
                <span 
                  v-for="moderator in playlist.moderators.slice(0, 3)" 
                  :key="moderator.id"
                  class="inline-flex items-center rounded-full bg-neutral-700/50 px-2 py-0.5 text-xs text-neutral-200"
                >
                  {{ moderator.name }}
                </span>
                <span 
                  v-if="playlist.moderators.length > 3"
                  class="text-xs text-neutral-400"
                  :aria-label="__('More moderators')"
                >
                  +{{ playlist.moderators.length - 3 }}
                </span>
              </div>
            </div>

            <!-- Arrow indicator -->
            <div class="mt-4 flex items-center justify-end">
              <Icon name="cheveron-right" class="h-5 w-5 text-neutral-500 transition-colors group-hover:text-teal-500" aria-hidden="true" />
            </div>
          </div>
        </Link>
      </Card>

      <!-- Empty State for Mobile -->
      <div v-if="playlists && playlists.data.length === 0" class="col-span-2">
        <Card>
          <div class="p-8 text-center">
            <Icon name="music" class="mx-auto h-12 w-12 text-neutral-600" aria-hidden="true" />
            <h3 class="mb-1 mt-3 text-lg font-semibold text-neutral-200">{{ __('No playlists found') }}</h3>
            <p class="text-sm text-neutral-400">
              {{ form.search ? __('Try adjusting your search') : __('Create your first playlist to get started') }}
            </p>
            <Link 
              v-if="!form.search" 
              class="btn-primary mt-4 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800" 
              :href="route('playlists.create')"
              :aria-label="__('Create your first playlist')"
            >
              {{ __('Create a playlist') }}
            </Link>
          </div>
        </Card>
      </div>
    </div>

    <!-- Mobile Pagination -->
    <div v-if="playlists && playlists.links && playlists.links.length > 3" class="mt-6 lg:hidden">
      <nav :aria-label="__('Pagination')">
        <Pagination :links="playlists.links" />
      </nav>
      <div class="mt-4 text-center text-sm text-neutral-300" role="status" aria-live="polite">
        <span class="font-medium text-neutral-200">{{ playlists.from || 0 }}</span>
        <span class="mx-1">{{ __('to') }}</span>
        <span class="font-medium text-neutral-200">{{ playlists.to || 0 }}</span>
        <span class="mx-1">{{ __('of') }}</span>
        <span class="font-medium text-neutral-200">{{ playlists.total || 0 }}</span>
        <span class="ml-1">{{ __('playlists') }}</span>
      </div>
    </div>

    <!-- Empty State (No playlists at all) -->
    <div v-if="playlists.data.length == 0 && !filters.search" class="mx-auto mt-8 max-w-screen-xl py-8 px-4 text-center lg:px-6">
      <div class="flex flex-col items-center space-y-4">
        <Icon 
          name="music" 
          class="h-16 w-16 text-neutral-400"
          aria-hidden="true"
        />
        <h2 class="text-xl font-medium text-neutral-200">{{ __('No playlists yet') }}</h2>
        <p class="text-neutral-400">{{ __('Create your first playlist to get started') }}</p>
        <Link 
          class="btn-primary btn-lg mt-4 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900" 
          :href="route('playlists.create')"
          :aria-label="__('Create your first playlist')"
        >
          {{ __('Create my first playlist') }}
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
