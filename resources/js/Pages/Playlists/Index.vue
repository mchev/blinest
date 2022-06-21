<template>
  <div>
    <Head title="Playlists" />
    <h1 class="mb-8 text-3xl font-bold">Playlists</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="mt-4 block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-primary" :href="route('playlists.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Playlist</span>
      </Link>
    </div>

    <card>
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pb-4 pt-6">Name</th>
          <th class="px-6 pb-4 pt-6">Owner</th>
          <th class="px-6 pb-4 pt-6">Tracks</th>
          <th class="px-6 pb-4 pt-6" colspan="2">Public</th>
        </tr>
        <tr v-for="playlist in playlists.data" :key="playlist.id" class="focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('playlists.edit', playlist.id)">
              <img v-if="playlist.photo" class="-my-2 mr-2 block h-5 w-5 rounded-full" :src="playlist.photo" />
              {{ playlist.name }}
              <icon v-if="playlist.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
              {{ playlist.owner }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
              {{ playlist.tracks_count }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
              {{ playlist.is_public ? 'Yes' : 'No' }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="route('playlists.edit', playlist.id)" tabindex="-1">
              <icon name="cheveron-right" class="block h-6 w-6" />
            </Link>
          </td>
        </tr>
        <tr v-if="playlists.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No playlists found.</td>
        </tr>
      </table>

      <pagination class="p-8" :links="playlists.links" />
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Layouts/AppLayout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'
import Pagination from '@/Shared/Pagination'
import Card from '@/Shared/Card'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
    Pagination,
    Card,
  },

  layout: Layout,

  props: {
    filters: Object,
    playlists: Object,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },

  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('playlists'), pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },

  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
