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
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-primary" href="/admin/categories/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Category</span>
      </Link>
    </div>
    <div class="overflow-x-auto rounded-md bg-white shadow">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6">Name</th>
            <th class="px-6 pb-4 pt-6">Public Rooms</th>
            <th class="px-6 pb-4 pt-6" colspan="2">Private Rooms</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories.data" :key="category.id" class="focus-within:bg-gray-100 hover:bg-gray-100">
            <td class="border-t">
              <Link class="focus:text-indigo-500 flex items-center px-6 py-4" :href="`/admin/categories/${category.id}/edit`">
                {{ category.name }}
                <icon v-if="category.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
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
                <icon name="cheveron-right" class="block h-6 w-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="categories.data.length === 0">
            <td class="border-t px-6 py-4" colspan="4">No categories found.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="categories.links" />
  </AdminLayout>
</template>
