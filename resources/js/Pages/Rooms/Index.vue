<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
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
  <Head :title="__('My Rooms')" />
  <AppLayout>
    <div class="mb-8 flex items-center justify-between">
      <h1 class="text-3xl font-bold text-neutral-100">{{ __('My Rooms') }}</h1>
      <Link class="btn-primary flex items-center space-x-2" :href="route('rooms.create')">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>{{ __('Create a room') }}</span>
      </Link>
    </div>

    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
    </div>

    <Card>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left text-sm font-medium text-neutral-400">
              <th class="px-4 pb-4 pt-6">{{ __('Name') }}</th>
              <th class="px-4 pb-4 pt-6">{{ __('Moderators') }}</th>
              <th class="px-4 pb-4 pt-6">{{ __('Category') }}</th>
              <th class="px-4 pb-4 pt-6">{{ __('Playlists') }}</th>
              <th class="px-4 pb-4 pt-6">{{ __('Rounds played') }}</th>
              <th class="px-4 pb-4 pt-6">{{ __('Autostart') }}</th>
              <th class="px-4 pb-4 pt-6" colspan="2">{{ __('Visibility') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="room in rooms.data" :key="room.id" class="group hover:bg-neutral-800/50 transition-colors">
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)">
                  <img v-if="room.photo" class="mr-3 h-10 w-10 rounded-full ring-1 ring-neutral-600" :src="room.photo" />
                  <div class="flex flex-col">
                    <span class="font-medium text-neutral-100 group-hover:text-teal-400 transition-colors">{{ room.name }}</span>
                    <small class="max-w-[18rem] truncate text-neutral-400">{{ room.description }}</small>
                  </div>
                  <icon v-if="room.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-neutral-400" />
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <div class="flex flex-wrap gap-2">
                    <span v-for="moderator in room.moderators" 
                          :key="moderator.id" 
                          class="badge bg-neutral-700/50 text-neutral-200">
                      {{ moderator.name }}
                    </span>
                  </div>
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link v-if="room.category" 
                      class="flex items-center px-4 py-4" 
                      :href="route('rooms.edit', room.id)" 
                      tabindex="-1">
                  <span class="badge bg-neutral-700/50 text-neutral-200">{{ room.category.name }}</span>
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <div v-if="room.playlists.length" class="flex flex-wrap gap-2">
                    <span v-for="playlist in room.playlists" 
                          :key="playlist.id" 
                          class="badge bg-neutral-700/50 text-neutral-200">
                      {{ playlist.name }}
                    </span>
                  </div>
                  <span v-else class="text-sm text-neutral-400">{{ __('No playlist') }}</span>
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <span class="font-medium text-neutral-200">{{ room.rounds_count }}</span>
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <span class="badge" :class="room.is_autostart ? 'bg-teal-500/20 text-teal-400' : 'bg-red-500/20 text-red-400'">
                    {{ __('Autostart') }}
                  </span>
                </Link>
              </td>
              <td class="border-t border-neutral-700">
                <Link class="flex items-center px-4 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <span class="badge" :class="!room.password ? 'bg-teal-500/20 text-teal-400' : 'bg-neutral-700/50 text-neutral-200'">
                    {{ room.password ? __('No') : __('Public') }}
                  </span>
                  <small v-if="room.password" class="ml-2 text-xs text-neutral-400">{{ __('Password protected') }}</small>
                </Link>
              </td>
              <td class="w-px border-t border-neutral-700">
                <Link class="flex items-center px-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                  <icon name="cheveron-right" class="block h-6 w-6 fill-neutral-400 group-hover:fill-teal-400 transition-colors" />
                </Link>
              </td>
            </tr>
            <tr v-if="rooms && rooms.data.length === 0">
              <td class="border-t border-neutral-700 px-4 py-4 text-neutral-400" colspan="8">{{ __('No rooms found') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="border-t border-neutral-700 px-4 py-4">
        <Pagination :links="rooms.links" />
      </div>
    </Card>

    <div v-if="rooms.data.length == 0 && !filters.search" class="mx-auto mt-8 max-w-screen-xl py-8 px-4 text-center lg:px-6">
      <div class="flex flex-col items-center space-y-4">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-16 w-16 text-neutral-400">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
        </svg>
        <h2 class="text-xl font-medium text-neutral-200">{{ __('No rooms yet') }}</h2>
        <p class="text-neutral-400">{{ __('Create your first room to start playing') }}</p>
        <Link class="btn-primary btn-lg mt-4" :href="route('rooms.create')">
          {{ __('Create my first room') }}
        </Link>
      </div>
    </div>
  </AppLayout>
</template>