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
  rooms: Object,
})

const form = useForm({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  form,
  throttle(() => {
    router.get('/admin/rooms', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Rooms" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">{{ __('Rooms:') }} ({{ rooms.total }})</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="mt-4 block text-gray-700">{{ __('Trashed:') }}</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __('With Trashed') }}</option>
          <option value="only">{{ __('Only Trashed') }}</option>
        </select>
      </search-filter>
      <Link class="btn-primary" :href="route('admin.rooms.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;{{ __('Rooms:') }}</span>
      </Link>
    </div>
    <Card>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="px-2 pb-4 pt-6">{{ __('Id') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Name') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Owner') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Moderators') }}</th>
            <th class="px-2 pb-4 pt-6">{{ __('Playlists') }}</th>
            <th class="px-2 pb-4 pt-6" colspan="2">{{ __('Public') }}</th>
          </tr>
          <tr v-for="room in rooms.data" :key="room.id" class="hover:bg-neutral-700">
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
                {{ room.id }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('admin.rooms.edit', room.id)">
                <img v-if="room.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="room.photo" />
                <div class="flex flex-col">
                  {{ room.name }}
                  <small class="max-w-[18rem] truncate text-gray-500">{{ room.description }}</small>
                </div>
                <icon v-if="room.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
                <img v-if="room.owner.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="room.owner.photo" />
                {{ room.owner.name }}
              </Link>
            </td>
            <td class="border-t">
              <ul class="flex items-center px-2 py-4 text-sm">
                <li v-for="moderator in room.moderators" :key="moderator.id">
                  <Link class="m-1 rounded bg-neutral-900 p-1 hover:underline" :href="route('admin.users.edit', moderator.id)" tabindex="-1">
                    {{ moderator.name }}
                  </Link>
                </li>
              </ul>
            </td>
            <td class="border-t">
              <ul class="flex items-center px-2 py-4 text-sm">
                <li v-for="playlist in room.playlists" :key="playlist.id">
                  <Link class="m-1 rounded bg-neutral-900 p-1 hover:underline" :href="route('admin.playlists.edit', playlist.id)" tabindex="-1">
                    {{ playlist.name }}
                  </Link>
                </li>
              </ul>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-2 py-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
                <span class="m-1 rounded px-2 py-1" :class="room.is_public ? 'bg-teal-600  text-neutral-100' : 'bg-neutral-900'">{{ room.is_public ? __('Yes') : __('No') }}</span>
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="route('admin.rooms.edit', room.id)" tabindex="-1">
                <icon name="cheveron-right" class="block h-6 w-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="rooms.length === 0">
            <td class="border-t px-2 py-4" colspan="6">{{ __('No rooms found') }}</td>
          </tr>
        </table>
      </div>
      <Pagination :links="rooms.links" />
    </Card>
  </AdminLayout>
</template>