<template>
  <div>
    <Head title="Teams" />
    <h1 class="mb-8 text-3xl font-bold">Teams</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" :href="route('admin.teams.create')">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Team</span>
      </Link>
    </div>
    <card>
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Role</th>
        </tr>
        <tr v-for="team in teams" :key="team.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="route('admin.teams.edit', team.id)">
              <img v-if="team.photo" class="block -my-2 mr-2 w-5 h-5 rounded-full" :src="team.photo" />
              {{ team.name }}
              <icon v-if="team.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="route('admin.teams.edit', team.id)" tabindex="-1">
              {{ team.owner ? 'Owner' : 'Team' }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="route('admin.teams.edit', team.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="teams.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No teams found.</td>
        </tr>
      </table>
    </card>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import AdminLayout from '@/Shared/AdminLayout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'
import Card from '@/Shared/Card'

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
    Card,
  },
  layout: AdminLayout,
  props: {
    filters: Object,
    teams: Array,
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
        this.$inertia.get(route('admin.teams'), pickBy(this.form), { preserveState: true })
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
