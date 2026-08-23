<script setup>
import { computed, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  canViewSensitiveData: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['ban'])

const activeRoomId = ref(null)

const conversationRooms = computed(() => props.user.conversation_rooms ?? [])

const activeRoom = computed(() => {
  if (!conversationRooms.value.length) {
    return null
  }

  return conversationRooms.value.find((room) => room.room.id === activeRoomId.value) ?? conversationRooms.value[0]
})

watch(
  conversationRooms,
  (rooms) => {
    if (!rooms.length) {
      activeRoomId.value = null

      return
    }

    if (!rooms.some((room) => room.room.id === activeRoomId.value)) {
      activeRoomId.value = rooms[0].room.id
    }
  },
  { immediate: true },
)

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

const riskClass = (level) => {
  if (level === 'warning') {
    return 'border-amber-500/30 bg-amber-500/10 text-amber-200'
  }

  if (level === 'info') {
    return 'border-blue-500/30 bg-blue-500/10 text-blue-200'
  }

  return 'border-white/10 bg-white/5 text-white/60'
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
      <div class="flex items-start gap-4">
        <img v-if="user.photo" :src="user.photo" :alt="user.name" class="h-16 w-16 rounded-full object-cover" />
        <div v-else class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-2xl font-semibold text-white">
          {{ user.name.charAt(0).toUpperCase() }}
        </div>
        <div>
          <div class="flex flex-wrap items-center gap-2">
            <h2 class="text-2xl font-bold text-white">{{ user.name }}</h2>
            <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 font-mono text-xs text-white/55">#{{ user.id }}</span>
            <span v-if="user.is_banned" class="rounded-full border border-red-500/30 bg-red-500/10 px-2.5 py-1 text-xs uppercase text-red-300">{{ __('Banned') }}</span>
            <span v-if="user.is_admin" class="rounded-full border border-blue-500/30 bg-blue-500/10 px-2.5 py-1 text-xs uppercase text-blue-300">Admin</span>
            <span v-if="user.is_moderator" class="rounded-full border border-purple-500/30 bg-purple-500/10 px-2.5 py-1 text-xs uppercase text-purple-300">{{ __('Moderator') }}</span>
          </div>
          <p class="mt-2 text-sm text-white/50">
            {{ __('Registered on') }} {{ formatDate(user.created_at) }}
            <span v-if="user.provider"> · {{ user.provider }}</span>
          </p>
          <Link :href="user.profile_url" class="mt-2 inline-flex text-sm text-brand-secondary hover:text-brand-primary">
            {{ __('Moderation open public profile') }}
          </Link>
        </div>
      </div>

      <div class="flex flex-wrap gap-2">
        <Link :href="route('moderation.users.index')" class="rounded-xl border border-white/10 px-4 py-2 text-sm text-white/75 transition hover:border-brand-primary/30 hover:text-white">
          {{ __('Back') }}
        </Link>
        <button v-if="user.can_ban" type="button" class="rounded-xl border border-red-500/30 px-4 py-2 text-sm text-red-300 transition hover:bg-red-500/10" @click="emit('ban')">
          {{ __('Ban') }}
        </button>
      </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
      <div class="rounded-xl border border-white/10 bg-black/20 p-4">
        <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Messages') }}</p>
        <p class="mt-1 text-2xl font-bold text-white">{{ user.messages_count }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-black/20 p-4">
        <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Reports') }}</p>
        <p class="mt-1 text-2xl font-bold" :class="user.reported_messages_count > 0 ? 'text-amber-300' : 'text-white'">{{ user.reported_messages_count }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-black/20 p-4">
        <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Bans') }}</p>
        <p class="mt-1 text-2xl font-bold" :class="user.bans_count > 0 ? 'text-red-300' : 'text-white'">{{ user.bans_count }}</p>
      </div>
      <div class="rounded-xl border border-white/10 bg-black/20 p-4">
        <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Last activity') }}</p>
        <p class="mt-1 text-sm font-medium text-white">{{ formatDate(user.last_message_at) }}</p>
      </div>
    </div>

    <section class="rounded-xl border border-white/10 bg-black/20 p-4">
      <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-sm font-bold uppercase tracking-wide text-white/70">{{ __('Moderation recent conversations') }}</h3>
        <p class="text-xs text-white/45">{{ __('Moderation conversations hint') }}</p>
      </div>

      <div v-if="conversationRooms.length">
        <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
          <button v-for="roomGroup in conversationRooms" :key="roomGroup.room.id" type="button" class="shrink-0 rounded-full border px-3 py-1.5 text-xs font-medium transition" :class="activeRoom?.room.id === roomGroup.room.id ? 'border-brand-primary/40 bg-brand-primary/15 text-white' : 'border-white/10 bg-white/5 text-white/60 hover:border-brand-primary/20 hover:text-white'" @click="activeRoomId = roomGroup.room.id">
            {{ roomGroup.room.name }}
            <span class="ml-1 text-white/45">({{ roomGroup.threads_count }})</span>
          </button>
        </div>

        <div v-if="activeRoom" class="space-y-4">
          <div class="flex flex-wrap items-center justify-between gap-2 text-sm text-white/60">
            <Link v-if="activeRoom.room.url" :href="activeRoom.room.url" class="font-medium text-brand-secondary hover:text-brand-primary">
              {{ __('Moderation open room') }}
            </Link>
            <span>{{ __('Last activity') }}: {{ formatDate(activeRoom.last_message_at) }}</span>
          </div>

          <article v-for="thread in activeRoom.threads" :key="thread.id" class="rounded-xl border border-white/10 bg-black/30 p-4">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
              <div class="text-sm text-white/70">
                <span class="text-white/40">{{ thread.created_at_label }}</span>
              </div>
              <div class="flex flex-wrap gap-2">
                <span v-if="thread.is_deleted" class="rounded-full border border-red-500/30 bg-red-500/10 px-2 py-0.5 text-[10px] uppercase text-red-300">{{ __('Deleted') }}</span>
                <span v-if="thread.reports_count > 0" class="rounded-full border border-amber-500/30 bg-amber-500/10 px-2 py-0.5 text-[10px] uppercase text-amber-200"> {{ __('Reports') }}: {{ thread.reports_count }} </span>
              </div>
            </div>

            <div class="space-y-2 rounded-lg border border-white/5 bg-black/20 p-3">
              <div v-for="message in thread.context" :key="`${thread.id}-${message.id}`" class="rounded-lg px-3 py-2 text-sm" :class="message.is_target ? 'border border-brand-primary/30 bg-brand-primary/10 text-white' : 'text-white/70'">
                <div class="mb-1 flex flex-wrap items-center gap-2 text-[11px] uppercase tracking-wide text-white/40">
                  <span>{{ message.user?.name || '—' }}</span>
                  <span>·</span>
                  <span>{{ message.created_at_label }}</span>
                  <span v-if="message.is_deleted" class="text-red-300">{{ __('Deleted') }}</span>
                </div>
                <p class="break-words">{{ message.body }}</p>
              </div>
            </div>
          </article>
        </div>
      </div>
      <p v-else class="text-sm text-white/45">{{ __('Moderation no messages yet') }}</p>
    </section>

    <section v-if="user.related_accounts?.count" class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">
      <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-sm font-bold uppercase tracking-wide text-amber-200">{{ __('Moderation related accounts') }}</h3>
        <span class="rounded-full border border-amber-500/30 bg-amber-500/10 px-2.5 py-1 text-xs text-amber-100">
          {{ __('Moderation shared ip accounts', { count: user.related_accounts.count }) }}
        </span>
      </div>

      <ul class="space-y-2 text-sm">
        <li v-for="related in user.related_accounts.users" :key="related.id" class="rounded-lg border border-white/5 px-3 py-2">
          <div class="flex items-center justify-between gap-3">
            <Link :href="related.profile_url" class="font-medium text-white hover:text-brand-secondary"> #{{ related.id }} · {{ related.name }} </Link>
            <span v-if="related.is_banned" class="text-xs uppercase text-red-300">{{ __('Banned') }}</span>
          </div>
          <p class="mt-1 text-xs text-white/45">
            {{ formatDate(related.created_at) }}
            <span v-if="related.provider"> · {{ related.provider }}</span>
            <span v-if="canViewSensitiveData && related.ip"> · {{ related.ip }}</span>
          </p>
        </li>
      </ul>
    </section>

    <div class="grid gap-4 lg:grid-cols-2">
      <section class="rounded-xl border border-white/10 bg-black/20 p-4">
        <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-white/70">{{ __('Moderation ban history') }}</h3>
        <div v-if="user.bans?.length" class="space-y-3">
          <div v-for="ban in user.bans" :key="ban.id" class="rounded-lg border border-red-500/20 bg-red-500/5 p-3 text-sm">
            <p class="text-white">{{ ban.comment }}</p>
            <p class="mt-2 text-xs text-white/45">{{ formatDate(ban.created_at) }} · {{ ban.expires_at_label }}</p>
            <p v-if="ban.banned_by" class="text-xs text-white/45">{{ __('Banned by:') }} {{ ban.banned_by }}</p>
          </div>
        </div>
        <p v-else class="text-sm text-white/45">{{ __('Moderation no bans yet') }}</p>
      </section>

      <section class="rounded-xl border border-white/10 bg-black/20 p-4">
        <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-white/70">{{ __('Rooms') }} / {{ __('Playlists') }}</h3>
        <div class="space-y-4 text-sm">
          <div>
            <p class="mb-2 text-xs uppercase tracking-wide text-white/45">{{ __('Rooms') }} ({{ user.rooms?.length || 0 }})</p>
            <ul v-if="user.rooms?.length" class="space-y-2">
              <li v-for="room in user.rooms" :key="room.id" class="flex items-center justify-between gap-3 rounded-lg border border-white/5 px-3 py-2">
                <Link :href="room.url" class="truncate text-white/80 hover:text-brand-secondary">{{ room.name }}</Link>
                <span class="shrink-0 text-xs text-white/45">{{ room.messages_count }} msg</span>
              </li>
            </ul>
            <p v-else class="text-white/45">{{ __('Moderation no rooms yet') }}</p>
          </div>
          <div>
            <p class="mb-2 text-xs uppercase tracking-wide text-white/45">{{ __('Playlists') }} ({{ user.playlists?.length || 0 }})</p>
            <ul v-if="user.playlists?.length" class="space-y-2">
              <li v-for="playlist in user.playlists" :key="playlist.id" class="flex items-center justify-between gap-3 rounded-lg border border-white/5 px-3 py-2">
                <span class="truncate text-white/80">{{ playlist.name }}</span>
                <span class="shrink-0 text-xs text-white/45">{{ playlist.tracks_count }} tracks</span>
              </li>
            </ul>
            <p v-else class="text-white/45">{{ __('Moderation no playlists yet') }}</p>
          </div>
        </div>
      </section>
    </div>

    <section v-if="canViewSensitiveData && user.admin" class="rounded-xl border border-amber-500/20 bg-amber-500/5 p-4">
      <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-sm font-bold uppercase tracking-wide text-amber-200">{{ __('Moderation admin insights') }}</h3>
        <span class="text-xs text-amber-100/60">{{ __('Moderation admin only') }}</span>
      </div>

      <div class="grid gap-4 lg:grid-cols-2">
        <div class="space-y-3 text-sm">
          <div>
            <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Email') }}</p>
            <p class="font-mono text-white">{{ user.admin.email }}</p>
          </div>
          <div>
            <p class="text-xs uppercase tracking-wide text-white/45">{{ __('Moderation registration ip') }}</p>
            <p class="font-mono text-white">{{ user.admin.registration_ip || '—' }}</p>
          </div>
          <div class="flex flex-wrap gap-2">
            <span v-for="flag in user.admin.risk_flags" :key="flag.key" class="rounded-full border px-2.5 py-1 text-xs" :class="riskClass(flag.level)">
              {{ flag.message }}
            </span>
          </div>
          <p class="text-xs text-white/45">{{ __('Moderation vpn disclaimer') }}</p>
        </div>

        <div class="space-y-4 text-sm">
          <div>
            <p class="mb-2 text-xs uppercase tracking-wide text-white/45">{{ __('Moderation message ips') }}</p>
            <ul v-if="user.admin.message_ips?.length" class="space-y-2">
              <li v-for="entry in user.admin.message_ips" :key="entry.ip" class="flex items-center justify-between rounded-lg border border-white/5 px-3 py-2">
                <span class="font-mono text-white/80">{{ entry.ip }}</span>
                <span class="text-xs text-white/45">{{ entry.messages_count }} msg · {{ formatDate(entry.last_seen_at) }}</span>
              </li>
            </ul>
            <p v-else class="text-white/45">—</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
