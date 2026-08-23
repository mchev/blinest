<script setup>
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

defineProps({
  users: {
    type: Object,
    required: true,
  },
  canViewSensitiveData: {
    type: Boolean,
    default: false,
  },
})

const search = defineModel('search', { type: String, default: '' })

const emit = defineEmits(['ban'])

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

const badgeClass = (user) => {
  if (user.is_banned) {
    return 'border-red-500/30 bg-red-500/10 text-red-300'
  }

  if (user.is_admin) {
    return 'border-blue-500/30 bg-blue-500/10 text-blue-300'
  }

  if (user.is_moderator) {
    return 'border-purple-500/30 bg-purple-500/10 text-purple-300'
  }

  return 'border-white/10 bg-white/5 text-white/70'
}
</script>

<template>
  <div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div class="flex-1">
        <label for="moderation-user-search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-white/50">
          {{ __('Moderation search users') }}
        </label>
        <div class="relative">
          <input id="moderation-user-search" v-model="search" type="search" :placeholder="canViewSensitiveData ? __('Moderation search placeholder admin') : __('Moderation search placeholder moderator')" class="w-full rounded-xl border border-white/10 bg-black/30 py-2.5 pl-10 pr-4 text-white placeholder-white/35 focus:border-brand-primary/50 focus:outline-none focus:ring-2 focus:ring-brand-primary/20" />
          <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-white/35" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      <p class="text-sm text-white/45">{{ __('Moderation users count', { count: users.total }) }}</p>
    </div>

    <div class="overflow-x-auto rounded-xl border border-white/10">
      <table class="min-w-full divide-y divide-white/10 text-sm">
        <thead class="bg-black/30 text-left text-xs uppercase tracking-wide text-white/45">
          <tr>
            <th class="px-4 py-3">{{ __('ID') }}</th>
            <th class="px-4 py-3">{{ __('User') }}</th>
            <th class="px-4 py-3">{{ __('Registered on') }}</th>
            <th class="px-4 py-3">{{ __('Messages') }}</th>
            <th class="px-4 py-3">{{ __('Reports') }}</th>
            <th class="px-4 py-3">{{ __('Bans') }}</th>
            <th class="px-4 py-3">{{ __('Last activity') }}</th>
            <th v-if="canViewSensitiveData" class="px-4 py-3">{{ __('IP') }}</th>
            <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5 bg-black/20">
          <tr v-for="user in users.data" :key="user.id" class="transition hover:bg-white/5">
            <td class="px-4 py-3 font-mono text-xs text-white/55">#{{ user.id }}</td>
            <td class="px-4 py-3">
              <Link :href="route('moderation.users.show', user.id)" class="group flex items-center gap-3">
                <img v-if="user.photo" :src="user.photo" :alt="user.name" class="h-9 w-9 rounded-full object-cover" />
                <div v-else class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-sm font-semibold text-white">
                  {{ user.name.charAt(0).toUpperCase() }}
                </div>
                <div class="min-w-0">
                  <div class="truncate font-medium text-white group-hover:text-brand-secondary">{{ user.name }}</div>
                  <div class="mt-1 flex flex-wrap gap-1">
                    <span v-if="user.is_banned" class="rounded-full border px-2 py-0.5 text-[10px] uppercase" :class="badgeClass(user)">{{ __('Banned') }}</span>
                    <span v-if="user.is_admin" class="rounded-full border border-blue-500/30 bg-blue-500/10 px-2 py-0.5 text-[10px] uppercase text-blue-300">Admin</span>
                    <span v-if="user.is_moderator" class="rounded-full border border-purple-500/30 bg-purple-500/10 px-2 py-0.5 text-[10px] uppercase text-purple-300">{{ __('Moderator') }}</span>
                    <span v-if="user.provider" class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 text-[10px] uppercase text-white/55">{{ user.provider }}</span>
                  </div>
                </div>
              </Link>
            </td>
            <td class="px-4 py-3 text-white/70">{{ formatDate(user.created_at) }}</td>
            <td class="px-4 py-3 tabular-nums text-white/70">{{ user.messages_count }}</td>
            <td class="px-4 py-3 tabular-nums" :class="user.reported_messages_count > 0 ? 'font-semibold text-amber-300' : 'text-white/70'">
              {{ user.reported_messages_count }}
            </td>
            <td class="px-4 py-3 tabular-nums" :class="user.bans_count > 0 ? 'font-semibold text-red-300' : 'text-white/70'">
              {{ user.bans_count }}
            </td>
            <td class="px-4 py-3 text-white/70">{{ formatDate(user.last_message_at) }}</td>
            <td v-if="canViewSensitiveData" class="px-4 py-3 font-mono text-xs text-white/55">{{ user.ip || '—' }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <Link :href="route('moderation.users.show', user.id)" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-white/75 transition hover:border-brand-primary/30 hover:text-white">
                  {{ __('View') }}
                </Link>
                <button v-if="user.can_ban" type="button" class="rounded-lg border border-red-500/30 px-3 py-1.5 text-xs text-red-300 transition hover:bg-red-500/10" @click="emit('ban', user)">
                  {{ __('Ban') }}
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="users.data.length === 0" class="rounded-xl border border-dashed border-white/10 px-6 py-12 text-center text-white/45">
      {{ __('No users found') }}
    </div>

    <Pagination v-if="users.links?.length > 3" :links="users.links" />
  </div>
</template>
