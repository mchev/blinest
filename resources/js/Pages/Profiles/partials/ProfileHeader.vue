<script setup>
import { Link } from '@inertiajs/vue3'
import LevelBadge from '@/Components/LevelBadge.vue'
import EloBadge from '@/Components/EloBadge.vue'
import SupporterBadge from '@/Components/Donations/SupporterBadge.vue'
import UserAvatar from '@/Components/UserAvatar.vue'
import { userHasDonorCrown } from '@/utils/donorPerks'

defineProps({
  profile: {
    type: Object,
    required: true,
  },
  supporterSinceLabel: {
    type: String,
    default: null,
  },
})

defineEmits(['open-level'])
</script>

<template>
  <section class="overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-deep via-brand-midnight to-neutral-900 shadow-lg">
    <div class="p-6 sm:p-8">
      <div class="flex flex-col items-center gap-6 text-center sm:flex-row sm:items-start sm:text-left">
        <div class="relative shrink-0">
          <UserAvatar :user="profile" img-class="h-24 w-24 rounded-full border-2 border-white/15 object-cover shadow-lg sm:h-28 sm:w-28" crown-size="lg" />
          <div v-if="profile.level" class="absolute -bottom-1 -right-1">
            <LevelBadge :level="profile.level" :current-xp="profile.current_xp" :xp-for-next-level="profile.xp_for_next_level" :total-xp="profile.total_xp" :level-metrics="profile.level_metrics" size="sm" @click="$emit('open-level')" />
          </div>
        </div>

        <div class="min-w-0 flex-1 space-y-3">
          <div>
            <h1 class="flex flex-wrap items-center justify-center gap-2 text-2xl font-bold text-white sm:justify-start sm:text-3xl">
              {{ profile.name }}
              <SupporterBadge v-if="profile.is_supporter && !userHasDonorCrown(profile)" size="md" />
            </h1>
            <p v-if="supporterSinceLabel" class="mt-1 text-sm font-medium text-amber-300/90">
              {{ supporterSinceLabel }}
            </p>
            <p class="mt-1 text-sm text-white/50">{{ profile.created_at_from_now }}</p>
          </div>

          <Link v-if="profile.team" :href="route('teams.show', profile.team.id)" class="group inline-flex max-w-full items-center gap-3 rounded-xl border border-violet-500/25 bg-violet-950/30 px-3 py-2 transition hover:border-violet-400/40">
            <img v-if="profile.team.photo" :src="profile.team.photo" :alt="profile.team.name" class="h-10 w-10 rounded-full object-cover ring-2 ring-violet-400/40" loading="lazy" />
            <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-neutral-800 text-sm font-black text-violet-300 ring-2 ring-violet-400/30">
              {{ profile.team.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div class="min-w-0 text-left">
              <p class="text-[10px] font-bold uppercase tracking-wider text-violet-300/80">{{ __('Squad') }}</p>
              <p class="truncate font-semibold text-white group-hover:text-violet-100">{{ profile.team.name }}</p>
            </div>
          </Link>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
          <p class="text-2xl font-black tabular-nums text-white">{{ profile.total_score }}</p>
          <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('PTS') }}</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
          <div class="flex justify-center">
            <EloBadge :elo="profile.elo" size="md" variant="compact" />
          </div>
          <p class="mt-1 text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('ELO') }}</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-white/5 px-3 py-3 text-center">
          <p class="text-xl font-bold text-white">{{ profile.level }}</p>
          <p class="text-[10px] font-semibold uppercase tracking-wide text-white/45">{{ __('Level') }}</p>
        </div>
      </div>

      <div class="mt-3 grid grid-cols-3 gap-2">
        <div class="rounded-lg border border-white/5 bg-white/[0.03] px-2 py-2 text-center">
          <p class="text-sm font-bold text-white/90">{{ profile.stats.rooms }}</p>
          <p class="text-[10px] text-white/40">{{ __('Rooms') }}</p>
        </div>
        <div class="rounded-lg border border-white/5 bg-white/[0.03] px-2 py-2 text-center">
          <p class="text-sm font-bold text-white/90">{{ profile.stats.playlists }}</p>
          <p class="text-[10px] text-white/40">{{ __('Playlists') }}</p>
        </div>
        <div class="rounded-lg border border-white/5 bg-white/[0.03] px-2 py-2 text-center">
          <p class="text-sm font-bold text-white/90">{{ profile.stats.bookmarks }}</p>
          <p class="text-[10px] text-white/40">{{ __('Bookmarks') }}</p>
        </div>
      </div>

      <div class="mt-4 flex flex-wrap justify-center gap-4 text-xs text-white/55 sm:justify-start">
        <span
          >{{ __('Public') }} <strong class="text-white">{{ profile.total_public_score }}</strong> {{ __('PTS') }}</span
        >
        <span
          >{{ __('Community') }} <strong class="text-white">{{ profile.total_private_score }}</strong> {{ __('PTS') }}</span
        >
      </div>
    </div>
  </section>
</template>
