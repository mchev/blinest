<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Card from '@/Components/Card.vue'

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
    <h1 class="mb-8 text-3xl font-bold">{{ __('My Playlists') }}</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-primary" :href="route('playlists.create')">
        <span>{{ __('Create a playlist') }}</span>
      </Link>
    </div>

    <Card class="my-4">
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6">{{ __('Name') }}</th>
            <th class="px-6 pb-4 pt-6">{{ __('Owner') }}</th>
            <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Moderators') }}</th>
          </tr>
          <tr v-for="playlist in playlists.data" :key="playlist.id">
            <td class="border-t border-neutral-500">
              <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('playlists.edit', playlist.id)">
                <img v-if="playlist.photo" class="-my-2 mr-2 block h-5 w-5 rounded-full" :src="playlist.photo" />
                <div class="flex flex-col">
                  {{ playlist.name }}
                  <small class="max-w-md truncate text-xs">{{ playlist.description }}</small>
                </div>
                <icon v-if="playlist.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t border-neutral-500">
              <Link class="flex items-center px-6 py-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
                {{ playlist.owner.name }}
              </Link>
            </td>
            <td class="border-t border-neutral-500">
              <Link class="flex items-center px-6 py-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
                <ul class="flex items-center px-6 py-4 text-sm">
                  <li v-for="moderator in playlist.moderators" :key="moderator.id" class="badge">
                    {{ moderator.name }}
                  </li>
                </ul>
              </Link>
            </td>
            <td class="w-px border-t border-neutral-500">
              <Link class="flex items-center px-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
                <icon name="cheveron-right" class="block h-6 w-6" />
              </Link>
            </td>
          </tr>
          <tr v-if="playlists.length === 0">
            <td class="border-t border-neutral-500 px-6 py-4" colspan="4">No playlists found.</td>
          </tr>
        </table>

        <Pagination :links="playlists.links" />
      </div>
    </Card>
  </AppLayout>
</template>
