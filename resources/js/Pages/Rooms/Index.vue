<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
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
    Inertia.get('/rooms', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Rooms" />
  <AppLayout>
    <h1 v-if="rooms && rooms.data.length" class="mb-8 text-3xl font-bold">{{ __('Rooms') }}</h1>
    <div v-if="rooms && rooms.data.length" class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-primary" :href="route('rooms.create')">
        {{ __('Create a room') }}
      </Link>
    </div>
    <Card v-if="rooms && rooms.data.length">
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-2 pb-4 pt-6">{{ __('Name') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Moderators') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Category') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Playlists') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Rounds played') }}</th>
            <th class="px-2 pb-4 pt-6" colspan="2">{{ __('Visibility') }}</th>
          </tr>
          <tr v-for="room in rooms.data" :key="room.id">
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('rooms.edit', room.id)">
                <img v-if="room.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="room.photo" />
                <div class="flex flex-col">
                  {{ room.name }}
                  <small class="max-w-[18rem] truncate text-gray-500">{{ room.description }}</small>
                </div>
                <icon v-if="room.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                <ul class="flex items-center px-2 py-4 text-sm">
                  <li v-for="moderator in room.moderators" :key="moderator.id" class="badge">
                    {{ moderator.name }}
                  </li>
                </ul>
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                {{ room.category.name }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                <ul v-if="room.playlists.length" class="flex items-center px-2 py-4 text-sm">
                  <li v-for="playlist in room.playlists" :key="playlist.id" class="badge">
                    {{ playlist.name }}
                  </li>
                </ul>
                <span v-else class="text-xs text-neutral-500">{{ __('No playlist') }}</span>
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex flex-col items-start px-2 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                {{ room.rounds_count }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex flex-col items-start px-2 py-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                <span class="badge" :class="!room.password ? 'bg-teal-600  text-neutral-100' : 'bg-neutral-600'">{{ room.password ? __('No') : __('Yes') }}</span>
                <small v-if="room.password" class="text-xs text-neutral-500">{{ __('Password protected') }}</small>
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="route('rooms.edit', room.id)" tabindex="-1">
                <icon name="cheveron-right" class="block h-6 w-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="rooms && rooms.data.length === 0">
            <td class="border-t px-2 py-4" colspan="6">{{ __('No rooms found.') }}</td>
          </tr>
        </table>
      </div>
      <Pagination :links="rooms.links" />
    </Card>
    <div v-else class="mx-auto max-w-screen-xl py-8 px-4 text-center lg:py-16 lg:px-6">
      <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
        <h2 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ __('Rooms') }}</h2>
        <p class="text-lg">"Si à 50 ans on n'a pas de Room sur Blinest, on a raté sa vie." <br><small class="text-xs italic">Auteur inconnu.</small></p>
        <div class="my-8 flex justify-center">
          <Link class="btn-primary btn-lg" :href="route('rooms.create')">
            {{ __('Create my first room') }}
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
