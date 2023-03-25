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
  pages: Object,
})

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    router.get('/admin/pages', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Pages" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">{{ __('Pages') }}</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-primary" href="/admin/pages/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;{{ __('Page') }}</span>
      </Link>
    </div>
    <Card>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pb-4 pt-6">{{ __('Title') }}</th>
              <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Last revision') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="page in pages.data" :key="page.id">
              <td class="border-t">
                <Link class="flex items-center px-6 py-4" :href="`/admin/pages/${page.id}/edit`">
                  {{ page.title }}
                  <icon v-if="page.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4" :href="`/admin/pages/${page.id}/edit`" tabindex="-1">
                  {{ page.date }}
                </Link>
              </td>
              <td class="w-px border-t">
                <Link class="flex items-center px-4" :href="`/admin/pages/${page.id}/edit`" tabindex="-1">
                  <icon name="cheveron-right" class="block h-6 w-6" />
                </Link>
              </td>
            </tr>
            <tr v-if="pages.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">{{ __('No pages found.') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :links="pages.links" />
    </Card>
  </AdminLayout>
</template>
