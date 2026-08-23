<script setup>
import { ref, watch } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import debounce from 'lodash/debounce'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  bannedUsers: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  canViewSensitiveData: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
})

const page = usePage()
const translate = useTranslate()
const search = ref(props.filters?.search || '')
const expandedUserId = ref(null)
const unbanForm = useForm({})

const performSearch = debounce(() => {
  router.get(route('moderation.banned-users.index'), { search: search.value, per_page: props.filters?.per_page || 20 }, { preserveState: true, preserveScroll: true, replace: true })
}, 300)

watch(search, () => {
  performSearch()
})

const formatDate = (value) => {
  if (!value) {
    return '—'
  }

  return new Date(value).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const toggleHistory = (userId) => {
  expandedUserId.value = expandedUserId.value === userId ? null : userId
}

const unbanUser = (user) => {
  if (!confirm(translate('Moderation unban confirm', { name: user.name }))) {
    return
  }

  unbanForm.post(route('moderation.banned-users.unban', user.id), {
    preserveScroll: true,
  })
}
</script>

<template>
  <Layout :title="__('Moderation banned users title')">
    <div class="space-y-6">
      <div v-if="page.props.flash?.success" class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        {{ page.props.flash.success }}
      </div>

      <div v-if="errors.error" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
        {{ errors.error }}
      </div>

      <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex-1">
          <label for="banned-user-search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-white/50">
            {{ __('Moderation search banned users') }}
          </label>
          <div class="relative">
            <input id="banned-user-search" v-model="search" type="search" :placeholder="canViewSensitiveData ? __('Moderation search banned placeholder admin') : __('Moderation search banned placeholder moderator')" class="w-full rounded-xl border border-white/10 bg-black/30 py-2.5 pl-10 pr-4 text-white placeholder-white/35 focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
            <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
        <p class="text-sm text-white/45">{{ __('Moderation banned users count', { count: bannedUsers.total }) }}</p>
      </div>

      <div v-if="bannedUsers.data.length === 0" class="rounded-xl border border-dashed border-white/10 px-6 py-12 text-center">
        <h3 class="text-lg font-medium text-white">{{ __('No banned users') }}</h3>
        <p class="mt-2 text-sm text-white/45">{{ __('There are currently no banned users in the system') }}</p>
      </div>

      <div v-else class="overflow-x-auto rounded-xl border border-white/10">
        <table class="min-w-full divide-y divide-white/10 text-sm">
          <thead class="bg-black/30 text-left text-xs uppercase tracking-wide text-white/45">
            <tr>
              <th class="px-4 py-3">{{ __('ID') }}</th>
              <th class="px-4 py-3">{{ __('User') }}</th>
              <th v-if="canViewSensitiveData" class="px-4 py-3">{{ __('Email') }}</th>
              <th class="px-4 py-3">{{ __('Reason') }}</th>
              <th class="px-4 py-3">{{ __('Banned by:') }}</th>
              <th class="px-4 py-3">{{ __('Ban date') }}</th>
              <th class="px-4 py-3">{{ __('Expires') }}</th>
              <th v-if="canViewSensitiveData" class="px-4 py-3">{{ __('IP Address') }}</th>
              <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5 bg-black/20">
            <template v-for="user in bannedUsers.data" :key="user.id">
              <tr class="transition hover:bg-white/5">
                <td class="px-4 py-3 font-mono text-xs text-white/55">#{{ user.id }}</td>
                <td class="px-4 py-3">
                  <Link :href="user.profile_url" class="group flex items-center gap-3">
                    <img v-if="user.photo" :src="user.photo" :alt="user.name" class="h-9 w-9 rounded-full object-cover" />
                    <div v-else class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white">
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0">
                      <div class="truncate font-medium text-white group-hover:text-brand-secondary">{{ user.name }}</div>
                      <button v-if="user.bans_count > 1" type="button" class="mt-1 text-xs text-white/45 underline decoration-dotted hover:text-white" @click.prevent="toggleHistory(user.id)">
                        {{ __('Moderation ban history count', { count: user.bans_count }) }}
                      </button>
                    </div>
                  </Link>
                </td>
                <td v-if="canViewSensitiveData" class="px-4 py-3 text-white/70">{{ user.email || '—' }}</td>
                <td class="px-4 py-3 text-white/85">{{ user.active_ban?.comment || '—' }}</td>
                <td class="px-4 py-3 text-white/70">{{ user.active_ban?.banned_by || '—' }}</td>
                <td class="px-4 py-3 text-white/70">{{ formatDate(user.active_ban?.created_at) }}</td>
                <td class="px-4 py-3">
                  <span class="rounded-full border px-2 py-0.5 text-xs" :class="user.active_ban?.is_permanent ? 'border-red-500/30 bg-red-500/10 text-red-300' : 'border-amber-500/30 bg-amber-500/10 text-amber-200'">
                    {{ user.active_ban?.expires_at_label || '—' }}
                  </span>
                </td>
                <td v-if="canViewSensitiveData" class="px-4 py-3 font-mono text-xs text-white/55">
                  {{ user.active_ban?.ip || user.ip || '—' }}
                </td>
                <td class="px-4 py-3">
                  <div class="flex justify-end gap-2">
                    <Link :href="user.profile_url" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-white/75 transition hover:border-brand-primary/30 hover:text-white">
                      {{ __('View') }}
                    </Link>
                    <LoadingButton type="button" class="rounded-lg border border-emerald-500/30 px-3 py-1.5 text-xs text-emerald-300 transition hover:bg-emerald-500/10" :loading="unbanForm.processing" @click="unbanUser(user)">
                      {{ __('Unban') }}
                    </LoadingButton>
                  </div>
                </td>
              </tr>

              <tr v-if="expandedUserId === user.id && user.ban_history.length > 1" :key="`${user.id}-history`">
                <td :colspan="canViewSensitiveData ? 9 : 7" class="bg-black/30 px-4 py-3">
                  <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/45">{{ __('Moderation ban history') }}</p>
                    <div v-for="ban in user.ban_history" :key="ban.id" class="rounded-lg border border-white/5 px-3 py-2 text-sm text-white/70">
                      <p class="text-white">{{ ban.comment }}</p>
                      <p class="mt-1 text-xs text-white/45">
                        {{ formatDate(ban.created_at) }} · {{ ban.banned_by || '—' }} · {{ ban.expires_at_label }}
                        <span v-if="canViewSensitiveData && ban.ip"> · {{ ban.ip }}</span>
                      </p>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <Pagination v-if="bannedUsers.links?.length > 3" :links="bannedUsers.links" />
    </div>
  </Layout>
</template>
