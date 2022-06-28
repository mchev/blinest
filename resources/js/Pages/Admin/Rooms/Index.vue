<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import Icon from '@/Components/Icon'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Pagination from '@/Components/Pagination'
import SearchFilter from '@/Components/SearchFilter'
import Card from '@/Components/Card'

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
    Inertia.get('/admin/rooms', pickBy(form), { remember: 'forget', preserveState: true })
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
      <Link class="btn-primary" :href="route('admin.rooms.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Room</span>
      </Link>
    </div>
    <Card>
      <div class="overflow-x-auto">
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
    </div>
    </Card>
        <Pagination :links="rooms.links" />
</AdminLayout>
</template>