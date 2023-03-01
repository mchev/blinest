<template>
  <div>
    <Head title="Users" />
    <h1 class="mb-8 text-3xl font-bold">Users ({{ users.total }})</h1>
    <div class="mb-6 flex items-center justify-between">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="mt-4 block text-gray-700">{{ __('Trashed:') }}</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">{{ __('With Trashed') }}</option>
          <option value="only">{{ __('Only Trashed') }}</option>
        </select>
      </search-filter>
      <Link class="btn-primary" :href="route('admin.users.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;User</span>
      </Link>
    </div>
    <card>
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pb-4 pt-6">{{ __('Name') }}</th>
          <th class="px-6 pb-4 pt-6">{{ __('Email') }}</th>
          <th class="px-6 pb-4 pt-6">{{ __('Provider') }}</th>
          <th class="px-6 pb-4 pt-6">{{ __('Register') }}</th>
          <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Role') }}</th>
        </tr>
        <tr v-for="user in users.data" :key="user.id" class="focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-blinest-500" :href="route('admin.users.edit', user.id)">
              <img v-if="user.photo" class="-my-2 mr-2 block h-10 w-10 rounded-full" :src="user.photo" />
              {{ user.name }}
              <icon v-if="user.deleted_at" name="trash" class="ml-2 h-3 w-3 flex-shrink-0 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', user.id)" tabindex="-1">
              {{ user.email }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', user.id)" tabindex="-1">
              {{ user.provider }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', user.id)" tabindex="-1">
              {{ user.created_at }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.users.edit', user.id)" tabindex="-1">
              {{ user.is_admin ? 'Admin' : 'User' }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="route('admin.users.edit', user.id)" tabindex="-1">
              <icon name="cheveron-right" class="block h-6 w-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="users.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No users found.</td>
        </tr>
      </table>

      <Pagination :links="users.links" />
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import pickBy from 'lodash/pickBy'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import Card from '@/Components/Card.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
    Pagination,
    Card,
  },
  layout: AdminLayout,
  props: {
    filters: Object,
    users: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('admin.users'), pickBy(this.form), { preserveState: true })
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