<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Icon from '@/Components/Icon.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Card from '@/Components/Card.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  filters: Object,
  teams: Object,
})

const form = useForm({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  form,
  throttle(() => {
    router.get('/admin/teams', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Teams" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">Teams</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __('With Trashed') }}</option>
          <option value="only">{{ __('Only Trashed') }}</option>
        </select>
      </search-filter>
      <Link class="btn-primary" href="/admin/teams/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Team</span>
      </Link>
    </div>
    <Card>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold">
              <th class="px-6 pb-4 pt-6">{{ __('Name') }}</th>
              <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Members') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="team in teams.data" :key="team.id" class="hover:bg-neutral-200">
              <td class="border-t">
                <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.teams.edit', team)">
                  {{ team.name }}
                  <icon v-if="team.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
                </Link>
              </td>
              <td class="border-t">
                <Link class="flex items-center px-6 py-4" :href="route('admin.teams.edit', team)" tabindex="-1">
                  {{ team.members_count }}
                </Link>
              </td>
              <td class="w-px border-t">
                <Link class="flex items-center px-4" :href="route('admin.teams.edit', team)" tabindex="-1">
                  <icon name="cheveron-right" class="block h-6 w-6" />
                </Link>
              </td>
            </tr>
            <tr v-if="teams.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">No teams found.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :links="teams.links" />
    </Card>
  </AdminLayout>
</template>