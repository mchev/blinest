<script setup>
import { router } from '@inertiajs/vue3'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'

const props = defineProps({
  team: Object,
  score: Number,
  stats: Object,
  members: Array,
})

const page = usePage()
const user = page.props.auth.user

const __ = (key, replace = {}) => {
  let translation = page.props.language[key] ? page.props.language[key] : key
  Object.keys(replace).forEach(function (replaceKey) {
    translation = translation.replace(':' + replaceKey, replace[replaceKey])
  })
  return translation
}

const form = useForm({
  _method: 'put',
  name: props.team.name,
  photo: null,
})

const formatNumber = (num) => {
  const n = Number(num)
  if (Number.isNaN(n)) {
    return '0'
  }
  return n >= 1000 ? n.toLocaleString(undefined, { maximumFractionDigits: 1 }) : String(Math.round(n * 10) / 10)
}

const formatDate = (iso) => {
  if (! iso) {
    return null
  }
  try {
    const locale = page.props.locale || page.props.language?.locale || undefined
    return new Date(iso).toLocaleString(locale, {
      dateStyle: 'medium',
      timeStyle: 'short',
    })
  } catch {
    return iso
  }
}

const medalForRank = (index) => {
  const medals = ['🥇', '🥈', '🥉']

  return medals[index] ?? null
}

const rowStyle = (index) => {
  if (index === 0) {
    return 'border-yellow-500/25 bg-gradient-to-r from-yellow-500/5 to-transparent'
  }
  if (index === 1) {
    return 'border-neutral-400/20 bg-gradient-to-r from-neutral-400/5 to-transparent'
  }
  if (index === 2) {
    return 'border-amber-700/25 bg-gradient-to-r from-amber-700/10 to-transparent'
  }
  return 'border-neutral-700/50 bg-neutral-900/40'
}

const leave = () => {
  if (confirm(__('Are you sure?'))) {
    router.post(`/teams/${props.team.id}/leave`)
  }
}

const destroy = () => {
  if (confirm(__('Are you sure?'))) {
    router.delete(`/teams/${props.team.id}`)
  }
}

const sendRequest = (team) => {
  router.post(`/teams/${team.id}/request`)
}

const cancelRequest = (team) => {
  router.post(`/teams/${team.id}/request/cancel`)
}

const switchOwner = (member) => {
  if (confirm(__('Do you really want to transfer team ownership to this member?'))) {
    router.post(`/teams/${props.team.id}/owner/${member.id}`, {
      preserveState: false,
    })
  }
}

const removeMember = (member) => {
  if (confirm(__('Do you really want to remove this member from the team?'))) {
    router.post(`/teams/${props.team.id}/members/${member.id}/remove`)
  }
}

const updateTeam = () => {
  form.post(route('teams.update', props.team.id), {
    preserveScroll: true,
  })
}

const isTeamFull = () => props.members.length >= Number(props.team.seats)
</script>

<template>
<AppLayout>
    <div class="mx-auto max-w-4xl pb-16 pt-4 md:pt-8">
      <div class="mb-6">
        <Link
          href="/teams"
          class="inline-flex items-center gap-2 text-sm font-medium text-violet-300/90 transition hover:text-violet-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          {{ __('All squads') }}
        </Link>
      </div>

      <!-- Hero -->
      <div class="relative mb-8 overflow-hidden rounded-2xl border border-violet-500/25 bg-gradient-to-br from-violet-950/70 via-neutral-900 to-fuchsia-950/40 px-6 py-8 shadow-[0_0_48px_-12px_rgba(139,92,246,0.3)] sm:px-10 sm:py-10">
        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-fuchsia-500/10 blur-3xl" />
        <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:gap-10">
          <div class="relative mx-auto shrink-0 sm:mx-0">
            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 opacity-60 blur-lg" />
            <img
              v-if="team.photo"
              :src="team.photo"
              :alt="team.name"
              class="relative h-28 w-28 rounded-full object-cover ring-4 ring-violet-400/40 ring-offset-4 ring-offset-neutral-950 sm:h-36 sm:w-36"
            >
            <div
              v-else
              class="relative flex h-28 w-28 items-center justify-center rounded-full bg-neutral-800 text-3xl font-black text-violet-300 ring-4 ring-violet-400/30 ring-offset-4 ring-offset-neutral-950 sm:h-36 sm:w-36"
            >
              {{ team.name?.charAt(0)?.toUpperCase() }}
            </div>
          </div>
          <div class="min-w-0 flex-1 text-center sm:text-left">
            <div class="mb-1 flex flex-wrap items-center justify-center gap-2 sm:justify-start">
              <span class="rounded-full border border-amber-400/35 bg-amber-400/10 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide text-amber-200">
                {{ __('Squad') }}
              </span>
              <Link
                :href="route('rankings.teams')"
                class="text-xs font-semibold text-violet-300/90 underline-offset-2 hover:text-violet-200 hover:underline"
              >
                {{ __('Team rankings') }} →
              </Link>
            </div>
            <h1 class="mb-2 text-3xl font-black tracking-tight text-white sm:text-4xl">
              {{ team.name }}
            </h1>
            <p class="mb-4 text-sm text-neutral-400">
              {{ __('Captain') }}:
              <Link
                v-if="team.owner?.id"
                :href="route('user.profile', { user: team.owner.id })"
                class="font-semibold text-violet-300 hover:text-violet-200"
              >
                {{ team.owner.name }}
              </Link>
              <span v-else class="font-semibold text-neutral-300">{{ team.owner?.name }}</span>
            </p>
            <div class="flex flex-wrap items-end justify-center gap-2 sm:justify-start">
              <span class="text-4xl font-black tabular-nums tracking-tight text-white sm:text-5xl">
                {{ formatNumber(score) }}
              </span>
              <span class="mb-1.5 text-lg font-bold text-violet-300/80">{{ __('PTS') }}</span>
              <span class="mb-2 ml-2 text-sm text-neutral-500">
                {{ __('Squad score') }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-neutral-700/70 bg-neutral-900/50 px-4 py-4 text-center sm:text-left">
          <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">
            {{ __('Rounds played') }}
          </div>
          <div class="mt-1 text-2xl font-black tabular-nums text-fuchsia-200">
            {{ stats.rounds_played ?? 0 }}
          </div>
        </div>
        <div class="rounded-xl border border-neutral-700/70 bg-neutral-900/50 px-4 py-4 text-center sm:text-left">
          <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">
            {{ __('Avg. per round') }}
          </div>
          <div class="mt-1 text-2xl font-black tabular-nums text-violet-200">
            {{ formatNumber(stats.avg_points_per_round ?? 0) }}
            <span class="text-sm font-normal text-neutral-500">{{ __('PTS') }}</span>
          </div>
        </div>
        <div class="rounded-xl border border-neutral-700/70 bg-neutral-900/50 px-4 py-4 text-center sm:text-left">
          <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">
            {{ __('Last round') }}
          </div>
          <div class="mt-1 text-sm font-semibold text-neutral-200">
            {{ formatDate(stats.last_played_at) || __('No finished rounds yet') }}
          </div>
        </div>
      </div>

      <Card v-if="user.id === team.user_id" class="mb-8">
        <template #header>
          <span class="text-sm font-bold text-neutral-200">{{ __('Manage squad') }}</span>
        </template>
        <form @submit.prevent="updateTeam">
          <TextInput v-model="form.name" :label="__('Name')" class="mb-4" :error="form.errors.name" />
          <FileInput v-if="user.can.changeTeamPicture" v-model="form.photo" :label="__('Image')" class="mb-4" :error="form.errors.photo" />
          <Tip v-if="!user.can.changeTeamPicture">
            {{ __('In order to change team picture, you need to have a minimum of three months of seniority and a total score above two thousand') }}<sup>{{ __('PTS') }}</sup>.
          </Tip>
          <LoadingButton type="submit" :loading="form.processing" class="btn-primary mb-4 ml-auto">
            {{ __('Update') }}
          </LoadingButton>
        </form>
      </Card>

      <Card>
        <template #header>
          <div class="flex w-full flex-wrap items-center justify-between gap-2">
            <span class="text-sm font-bold text-neutral-200">{{ __('Squad roster') }}</span>
            <span class="rounded-full border border-neutral-600 bg-neutral-800/80 px-3 py-1 text-xs font-semibold text-neutral-300">
              {{ members.length }} / {{ team.seats }}
            </span>
          </div>
        </template>
        <ul class="flex flex-col gap-3">
          <li
            v-for="(member, index) in members"
            :key="member.id"
            :class="['flex flex-col gap-3 rounded-xl border p-4 sm:flex-row sm:items-center', rowStyle(index)]"
          >
            <div class="flex min-w-0 flex-1 items-center gap-3">
              <div class="flex h-10 w-10 shrink-0 items-center justify-center text-xl font-black tabular-nums text-neutral-500">
                <span v-if="medalForRank(index)">{{ medalForRank(index) }}</span>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <Link
                :href="route('user.profile', { user: member.id })"
                class="flex min-w-0 items-center gap-3 transition hover:opacity-90"
              >
                <img
                  v-if="member.photo"
                  :src="member.photo"
                  alt=""
                  class="h-11 w-11 shrink-0 rounded-full object-cover ring-2 ring-neutral-600"
                >
                <div v-else class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-neutral-700 text-sm font-bold text-neutral-300">
                  {{ member.name?.charAt(0)?.toUpperCase() }}
                </div>
                <div class="min-w-0">
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="truncate font-bold text-white">{{ member.name }}</span>
                    <span
                      v-if="member.id === user.id"
                      class="shrink-0 rounded bg-violet-500/20 px-1.5 py-0.5 text-[10px] font-bold uppercase text-violet-200"
                    >
                      {{ __('You') }}
                    </span>
                    <span v-if="member.id === team.user_id" class="text-amber-400" title="Captain">★</span>
                  </div>
                  <div class="mt-1 text-xs text-neutral-500">
                    {{ member.rounds_played ?? 0 }} {{ __('Rounds played') }} · {{ __('Share of squad points') }} {{ member.contribution_percent ?? 0 }}%
                  </div>
                </div>
              </Link>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-48 sm:shrink-0">
              <div class="h-2 overflow-hidden rounded-full bg-neutral-800">
                <div
                  class="h-full rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-500 transition-all"
                  :style="{ width: `${Math.min(100, member.contribution_percent ?? 0)}%` }"
                />
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="font-black tabular-nums text-violet-200">{{ formatNumber(member.score) }} {{ __('PTS') }}</span>
                <div class="flex items-center gap-1">
                  <button
                    v-if="user.id === team.user_id && member.id !== team.user_id"
                    type="button"
                    class="rounded p-1 text-amber-400/90 transition hover:bg-amber-500/10 hover:text-amber-300"
                    :title="__('Transfer captain')"
                    @click="switchOwner(member)"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                  </button>
                  <button
                    v-if="user.id === team.user_id && member.id !== team.user_id"
                    type="button"
                    class="rounded p-1 text-red-400 transition hover:bg-red-500/10 hover:text-red-300"
                    :title="__('Remove member')"
                    @click="removeMember(member)"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </Card>

      <div class="mt-8 flex flex-wrap items-center gap-3">
        <button
          v-if="members.length === 1 && team.user_id === user.id"
          type="button"
          class="btn-danger"
          @click="destroy"
        >
          {{ __('Delete') }} {{ __('The Team') }}
        </button>
        <button
          v-else-if="members.find((x) => x.id === user.id)"
          type="button"
          class="btn-danger"
          @click="leave"
        >
          {{ __('Leave the team') }}
        </button>
        <div v-else class="flex flex-wrap gap-3">
          <button
            v-if="user.declined_requests.includes(team.id)"
            type="button"
            class="btn-danger"
            @click="cancelRequest(team)"
          >
            {{ __('Declined request') }}
          </button>
          <button
            v-else-if="user.pending_requests.includes(team.id)"
            type="button"
            class="btn-danger"
            @click="cancelRequest(team)"
          >
            {{ __('Cancel join request') }}
          </button>
          <button
            v-else-if="isTeamFull()"
            type="button"
            disabled
            class="cursor-not-allowed rounded-lg border border-neutral-600 bg-neutral-800/80 px-4 py-2 text-sm font-semibold text-neutral-500"
            :title="__('It is not possible to join this team. The maximum number of members has been reached')"
          >
            {{ __('Team is full') }}
          </button>
          <button
            v-else
            type="button"
            class="btn-secondary"
            @click="sendRequest(team)"
          >
            {{ __('Send a join request') }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
