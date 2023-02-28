<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
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
    router.get('/admin/playlists', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Playlists" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">{{ __('Playlists') }} ({{ playlists.total }})</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="mt-4 block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __('With Trashed') }}</option>
          <option value="only">{{ __('Only Trashed') }}</option>
        </select>
      </search-filter>
      <Link class="btn-primary" :href="route('admin.playlists.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;{{ __('Playlists') }}</span>
      </Link>
    </div>

    <Card>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6">Name</th>
            <th class="px-6 pb-4 pt-6">Owner</th>
            <th class="px-6 pb-4 pt-6">Moderators</th>
            <th class="px-6 pb-4 pt-6">Tracks</th>
            <th class="px-6 pb-4 pt-6" colspan="2">Public</th>
          </tr>
          <tr v-for="playlist in playlists.data" :key="playlist.id" class="hover:bg-neutral-200">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('admin.playlists.edit', playlist.id)">
                <img v-if="playlist.photo" class="-my-2 mr-2 block h-5 w-5 rounded-full" :src="playlist.photo" />
                {{ playlist.name }}
                <icon v-if="playlist.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', playlist.owner.id)" tabindex="-1">
                {{ playlist.owner.name }}
              </Link>
            </td>
            <td class="border-t">
              <ul class="flex items-center px-6 py-4 text-sm">
                <li v-for="moderator in playlist.moderators" :key="moderator.id">
                  <Link class="m-1 rounded bg-neutral-300 p-1 hover:underline" :href="route('admin.users.edit', moderator.id)" tabindex="-1">
                    {{ moderator.name }}
                  </Link>
                </li>
              </ul>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="route('admin.playlists.edit', playlist.id)" tabindex="-1">
                {{ playlist.tracks_count }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="route('admin.playlists.edit', playlist.id)" tabindex="-1">
                {{ playlist.is_public ? 'Yes' : 'No' }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="route('admin.playlists.edit', playlist.id)" tabindex="-1">
                <icon name="cheveron-right" class="block h-6 w-6" />
              </Link>
            </td>
          </tr>
          <tr v-if="playlists.length === 0">
            <td class="border-t px-6 py-4" colspan="4">No playlists found.</td>
          </tr>
        </table>

        <Pagination :links="playlists.links" />
      </div>
    </Card>
  </AdminLayout>
</template>