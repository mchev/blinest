<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

const props = defineProps({
  filters: Object,
  categories: Object,
})

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    Inertia.get('/admin/categories', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Categories" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">Categories</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset"/>
      <Link class="btn-indigo" href="/admin/categories/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Category</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">
              Name
            </th>
            <th class="pb-4 pt-6 px-6">
              Public Rooms
            </th>
            <th class="pb-4 pt-6 px-6" colspan="2">
              Private Rooms
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/admin/categories/${category.id}/edit`">
                {{ category.name }}
                <icon v-if="category.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
                {{ category.public_rooms_count }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
                {{ category.private_rooms_count }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/admin/categories/${category.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="categories.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">No categories found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="categories.links" />
  </AdminLayout>
</template>