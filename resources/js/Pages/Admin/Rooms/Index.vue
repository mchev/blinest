<template>
  <div>
    <Head title="Rooms" />
    <h1 class="mb-8 text-3xl font-bold">Rooms</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="mt-4 block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-blinest" :href="route('admin.rooms.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Room</span>
      </Link>
    </div>
    <card>
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pb-4 pt-6">{{ __('Name') }}</th>
          <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Role') }}</th>
        </tr>
        <tr v-for="room in rooms.data" :key="room.id" class="focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('admin.rooms.edit', room.id)">
              <img v-if="room.photo" class="-my-2 mr-2 block h-5 w-5 rounded-full" :src="room.photo" />
              <div class="flex flex-col">
                {{ room.name }}
                <small class="text-gray-500">{{ room.description }}</small>
              </div>
              <icon v-if="room.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
              {{ room.owner }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
              <icon name="cheveron-right" class="block h-6 w-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="rooms.length === 0">
          <td class="border-t px-6 py-4" colspan="4">{{ __('No rooms found.') }}</td>
        </tr>
      </table>
      <Pagination :links="rooms.links" />
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import AdminLayout from '@/Layouts/AdminLayout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'
import Card from '@/Shared/Card'
import Pagination from '@/Shared/Pagination'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
    Card,
    Pagination,
  },
  layout: AdminLayout,
  props: {
    filters: Object,
    rooms: Array,
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
        this.$inertia.get(route('admin.rooms'), pickBy(this.form), { preserveState: true })
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
