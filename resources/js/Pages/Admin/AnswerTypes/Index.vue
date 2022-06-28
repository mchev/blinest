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
  answer_types: Object,
})

const form = useForm({
  search: props.filters.search,
})

watch(
  form,
  throttle(() => {
    Inertia.get('/admin/answer_types', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>
<template>
  <Head title="Answer Types" />
  <AdminLayout>
    <h1 class="mb-8 text-3xl font-bold">Answer Types</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset" />
      <Link class="btn-primary" href="/admin/answer_types/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Answer Type</span>
      </Link>
    </div>
    <Card>
    <div class="overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-6 pb-4 pt-6">Name</th>
            <th class="px-6 pb-4 pt-6">Pronoun</th>
            <th class="px-6 pb-4 pt-6" colspan="2">Icon</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="answer_type in answer_types.data" :key="answer_type.id" class="focus-within:bg-gray-100 hover:bg-gray-100">
            <td class="border-t">
              <Link class="focus:text-indigo-500 flex items-center px-6 py-4" :href="`/admin/answer_types/${answer_type.id}/edit`">
                {{ answer_type.name }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/answer_types/${answer_type.id}/edit`" tabindex="-1">
                {{ answer_type.pronoun }}
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/admin/answer_types/${answer_type.id}/edit`" tabindex="-1">
                <span v-html="answer_type.svg_icon"/>
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/admin/answer_types/${answer_type.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block h-6 w-6" />
              </Link>
            </td>
          </tr>
          <tr v-if="answer_types.data.length === 0">
            <td class="border-t px-6 py-4" colspan="4">No answer_types found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </Card>
    <pagination class="mt-6" :links="answer_types.links" />
  </AdminLayout>
</template>
