<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'
import Card from '@/Components/Card.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'

const props = defineProps({
  filters: Object,
  rooms: Object,
})

const form = useForm({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  form,
  throttle(() => {
    router.get('/rooms', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <AppLayout>
    <!-- Header Section -->
    <header class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-neutral-100 sm:text-3xl">{{ __('My Rooms') }}</h1>
        <p v-if="rooms && rooms.total" class="mt-1 text-sm text-neutral-400">
          {{ __('Total') }}: <span class="font-medium text-neutral-200">{{ rooms.total }}</span> {{ __('rooms') }}
        </p>
      </div>
      <Link class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/25 transition-all duration-200 hover:scale-105 hover:from-teal-400 hover:to-teal-500 hover:shadow-xl hover:shadow-teal-500/40 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900 active:scale-100" :href="route('rooms.create')" :aria-label="__('Create a new room')">
        <Icon name="plus" class="h-5 w-5 text-white transition-transform duration-200 group-hover:rotate-90" aria-hidden="true" />
        <span>{{ __('Create a room') }}</span>
      </Link>
    </header>

    <!-- Search Section -->
    <div class="mb-6">
      <label for="search-rooms" class="sr-only">{{ __('Search rooms') }}</label>
      <Card>
        <div class="p-4">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex-1">
              <text-input id="search-rooms" v-model="form.search" prepend-icon="search" :placeholder="__('Search rooms') + '...'" class="w-full border-neutral-600/50 bg-neutral-900/50 focus:border-teal-500/50" :aria-label="__('Search rooms by name, description, or category')" />
            </div>
            <button v-if="form.search" class="rounded px-4 py-2 text-sm text-neutral-400 transition-colors hover:text-neutral-200 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800" @click="reset" :aria-label="__('Reset search')">
              {{ __('Reset') }}
            </button>
          </div>
        </div>
      </Card>
    </div>

    <!-- Desktop Table View (lg and above) -->
    <Card class="hidden lg:block">
      <div class="overflow-x-auto">
        <table class="w-full" role="table" :aria-label="__('Rooms list')">
          <thead>
            <tr class="text-left">
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Name') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Moderators') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Category') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Playlists') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Rounds played') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200">{{ __('Autostart') }}</th>
              <th scope="col" class="px-4 pb-4 pt-6 text-sm font-semibold text-neutral-200" colspan="2">{{ __('Visibility') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="room in rooms.data" :key="room.id" class="group border-t border-neutral-700/50 transition-colors hover:bg-neutral-800/30">
              <td class="px-4 py-4">
                <Link class="flex items-center rounded focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800" :href="route('rooms.edit', room.id)" :aria-label="`${__('Edit room')}: ${room.name}`">
                  <img v-if="room.photo" class="mr-3 h-10 w-10 flex-shrink-0 rounded-full ring-1 ring-neutral-600" :src="room.photo" :alt="room.name" />
                  <div class="flex min-w-0 flex-col">
                    <span class="truncate font-medium text-neutral-100 transition-colors group-hover:text-teal-400">{{ room.name }}</span>
                    <small v-if="room.description" class="max-w-[18rem] truncate text-sm text-neutral-300">{{ room.description }}</small>
                  </div>
                  <icon v-if="room.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-neutral-400" aria-hidden="true" />
                </Link>
              </td>
              <td class="px-4 py-4">
                <div class="flex flex-wrap gap-2">
                  <span v-for="moderator in room.moderators" :key="moderator.id" class="badge bg-neutral-700/50 text-sm text-neutral-200">
                    {{ moderator.name }}
                  </span>
                  <span v-if="!room.moderators || room.moderators.length === 0" class="text-sm text-neutral-400">{{ __('None') }}</span>
                </div>
              </td>
              <td class="px-4 py-4">
                <span v-if="room.category" class="badge bg-neutral-700/50 text-sm text-neutral-200">{{ room.category.name }}</span>
                <span v-else class="text-sm text-neutral-400">{{ __('No category') }}</span>
              </td>
              <td class="px-4 py-4">
                <div v-if="room.playlists && room.playlists.length" class="flex flex-wrap gap-2">
                  <span v-for="playlist in room.playlists" :key="playlist.id" class="badge bg-neutral-700/50 text-sm text-neutral-200">
                    {{ playlist.name }}
                  </span>
                </div>
                <span v-else class="text-sm text-neutral-300">{{ __('No playlist') }}</span>
              </td>
              <td class="px-4 py-4">
                <span class="text-sm font-medium text-neutral-200">{{ room.rounds_count || 0 }}</span>
              </td>
              <td class="px-4 py-4">
                <span class="badge text-sm" :class="room.is_autostart ? 'bg-teal-500/20 text-teal-400' : 'bg-red-500/20 text-red-400'" :aria-label="room.is_autostart ? __('Autostart enabled') : __('Autostart disabled')">
                  {{ room.is_autostart ? __('Yes') : __('No') }}
                </span>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                  <span class="badge text-sm" :class="!room.password ? 'bg-teal-500/20 text-teal-400' : 'bg-neutral-700/50 text-neutral-200'" :aria-label="!room.password ? __('Public room') : __('Private room')">
                    {{ room.password ? __('Private') : __('Public') }}
                  </span>
                  <span v-if="room.password" class="text-xs text-neutral-400" :aria-label="__('Password protected')">🔒</span>
                </div>
              </td>
              <td class="w-px px-4">
                <Link class="flex items-center justify-center rounded focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800" :href="route('rooms.edit', room.id)" :aria-label="`${__('Edit room')}: ${room.name}`">
                  <icon name="cheveron-right" class="block h-6 w-6 fill-neutral-400 transition-colors group-hover:fill-teal-400" aria-hidden="true" />
                </Link>
              </td>
            </tr>
            <tr v-if="rooms && rooms.data.length === 0">
              <td class="border-t border-neutral-700/50 px-4 py-8 text-center text-sm text-neutral-300" colspan="8">
                {{ __('No rooms found') }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div v-if="rooms && rooms.links && rooms.links.length > 3" class="border-t border-neutral-700/50 bg-neutral-800/30 px-4 py-6">
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
          <div class="text-sm text-neutral-300" role="status" aria-live="polite">
            <span class="font-medium text-neutral-200">{{ rooms.from || 0 }}</span>
            <span class="mx-1">{{ __('to') }}</span>
            <span class="font-medium text-neutral-200">{{ rooms.to || 0 }}</span>
            <span class="mx-1">{{ __('of') }}</span>
            <span class="font-medium text-neutral-200">{{ rooms.total || 0 }}</span>
            <span class="ml-1">{{ __('rooms') }}</span>
          </div>
          <nav :aria-label="__('Pagination')">
            <Pagination :links="rooms.links" />
          </nav>
        </div>
      </div>
    </Card>

    <!-- Mobile/Tablet Card View (below lg) -->
    <div class="grid gap-4 sm:grid-cols-2 lg:hidden">
      <Card v-for="room in rooms.data" :key="room.id" class="transition-all hover:border-neutral-600/50 hover:shadow-xl">
        <Link :href="route('rooms.edit', room.id)" class="block rounded focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-800" :aria-label="__('Edit room') + ': ' + room.name">
          <!-- Room Header -->
          <div class="mb-4 flex items-start gap-3">
            <img v-if="room.photo" class="h-12 w-12 flex-shrink-0 rounded-full ring-1 ring-neutral-600" :src="room.photo" :alt="room.name" />
            <div class="min-w-0 flex-1">
              <h3 class="truncate font-semibold text-neutral-100">{{ room.name }}</h3>
              <p v-if="room.description" class="mt-1 line-clamp-2 text-sm text-neutral-300">{{ room.description }}</p>
            </div>
            <icon v-if="room.deleted_at" name="trash" class="h-4 w-4 flex-shrink-0 fill-neutral-400" aria-hidden="true" />
          </div>

          <!-- Room Details Grid -->
          <div class="space-y-3">
            <!-- Moderators -->
            <div v-if="room.moderators && room.moderators.length">
              <div class="mb-1 text-xs font-medium uppercase tracking-wider text-neutral-400">{{ __('Moderators') }}</div>
              <div class="flex flex-wrap gap-2">
                <span v-for="moderator in room.moderators" :key="moderator.id" class="badge bg-neutral-700/50 text-xs text-neutral-200">
                  {{ moderator.name }}
                </span>
              </div>
            </div>

            <!-- Category & Playlists -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <div class="mb-1 text-xs font-medium uppercase tracking-wider text-neutral-400">{{ __('Category') }}</div>
                <span v-if="room.category" class="badge bg-neutral-700/50 text-xs text-neutral-200">{{ room.category.name }}</span>
                <span v-else class="text-xs text-neutral-400">{{ __('No category') }}</span>
              </div>
              <div>
                <div class="mb-1 text-xs font-medium uppercase tracking-wider text-neutral-400">{{ __('Playlists') }}</div>
                <span class="text-xs font-medium text-neutral-200">{{ room.playlists ? room.playlists.length : 0 }}</span>
              </div>
            </div>

            <!-- Stats Row -->
            <div class="flex items-center justify-between border-t border-neutral-700/50 pt-3">
              <div class="flex items-center gap-4">
                <div>
                  <div class="text-xs text-neutral-400">{{ __('Rounds') }}</div>
                  <div class="text-sm font-semibold text-neutral-200">{{ room.rounds_count || 0 }}</div>
                </div>
                <div>
                  <div class="text-xs text-neutral-400">{{ __('Autostart') }}</div>
                  <span class="badge text-xs" :class="room.is_autostart ? 'bg-teal-500/20 text-teal-400' : 'bg-red-500/20 text-red-400'">
                    {{ room.is_autostart ? __('Yes') : __('No') }}
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <span class="badge text-xs" :class="!room.password ? 'bg-teal-500/20 text-teal-400' : 'bg-neutral-700/50 text-neutral-200'">
                  {{ room.password ? __('Private') : __('Public') }}
                </span>
                <span v-if="room.password" class="text-xs text-neutral-400" :aria-label="__('Password protected')">🔒</span>
                <icon name="cheveron-right" class="h-5 w-5 fill-neutral-400" aria-hidden="true" />
              </div>
            </div>
          </div>
        </Link>
      </Card>

      <!-- Empty State for Mobile -->
      <div v-if="rooms && rooms.data.length === 0" class="col-span-2">
        <Card>
          <div class="py-8 text-center">
            <p class="text-sm text-neutral-300">{{ __('No rooms found') }}</p>
          </div>
        </Card>
      </div>
    </div>

    <!-- Mobile Pagination -->
    <div v-if="rooms && rooms.links && rooms.links.length > 3" class="mt-6 lg:hidden">
      <nav aria-label="{{ __('Pagination') }}">
        <Pagination :links="rooms.links" />
      </nav>
      <div class="mt-4 text-center text-sm text-neutral-300" role="status" aria-live="polite">
        <span class="font-medium text-neutral-200">{{ rooms.from || 0 }}</span>
        <span class="mx-1">{{ __('to') }}</span>
        <span class="font-medium text-neutral-200">{{ rooms.to || 0 }}</span>
        <span class="mx-1">{{ __('of') }}</span>
        <span class="font-medium text-neutral-200">{{ rooms.total || 0 }}</span>
        <span class="ml-1">{{ __('rooms') }}</span>
      </div>
    </div>

    <!-- Empty State (No rooms at all) -->
    <div v-if="rooms.data.length == 0 && !filters.search" class="mx-auto mt-8 max-w-screen-xl px-4 py-8 text-center lg:px-6">
      <div class="flex flex-col items-center space-y-4">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-16 w-16 text-neutral-400" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
        </svg>
        <h2 class="text-xl font-medium text-neutral-200">{{ __('No rooms yet') }}</h2>
        <p class="text-neutral-400">{{ __('Create your first room to start playing') }}</p>
        <Link class="btn-primary btn-lg mt-4 focus:outline-none focus:ring-2 focus:ring-teal-500/50 focus:ring-offset-2 focus:ring-offset-neutral-900" :href="route('rooms.create')" :aria-label="__('Create your first room')">
          {{ __('Create my first room') }}
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
