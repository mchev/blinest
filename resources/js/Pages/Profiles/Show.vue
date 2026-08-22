<script setup>
import { ref, computed } from 'vue'
import { Deferred } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import LevelInfo from '@/Components/LevelInfo.vue'
import LevelModal from '@/Components/LevelModal.vue'
import DonationHistoryList from '@/Components/Donations/DonationHistoryList.vue'
import { useTranslate } from '@/composables/useTranslate'
import ProfileHeader from './partials/ProfileHeader.vue'
import ProfilePerformanceBanner from './partials/ProfilePerformanceBanner.vue'
import ProfileBadges from './partials/ProfileBadges.vue'
import ProfileTopRooms from './partials/ProfileTopRooms.vue'
import ProfileTabs from './partials/ProfileTabs.vue'
import ProfileEvolutionChart from './partials/ProfileEvolutionChart.vue'
import ScoresTab from './partials/ScoresTab.vue'
import LikesTab from './partials/LikesTab.vue'
import BookmarksTab from './partials/BookmarksTab.vue'
import MinigamesTab from './partials/MinigamesTab.vue'

const props = defineProps({
  profile: {
    type: Object,
    required: true,
  },
  activeTab: {
    type: String,
    default: 'scores',
  },
  scores: {
    type: Object,
    default: null,
  },
  likes: {
    type: Object,
    default: null,
  },
  bookmarks: {
    type: Object,
    default: null,
  },
  minigames: {
    type: Object,
    default: null,
  },
  donations: {
    type: Array,
    default: () => [],
  },
  profileHighlights: {
    type: Object,
    default: null,
  },
  scoreEvolution: {
    type: Array,
    default: () => [],
  },
  scoresSort: {
    type: String,
    default: 'updated_at',
  },
  scoresDirection: {
    type: String,
    default: 'desc',
  },
})

const translate = useTranslate()
const showLevelModal = ref(false)

const supporterSinceLabel = computed(() => {
  const summary = props.profile.donation_summary
  const months = summary?.supporter_duration_months

  if (!months || months < 1) {
    return null
  }

  if (months >= 12) {
    const years = Math.floor(months / 12)

    if (years === 1) {
      return translate('Supporter since one year')
    }

    return translate('Supporter since years', { count: years })
  }

  if (months === 1) {
    return translate('Supporter since one month')
  }

  return translate('Supporter since months', { count: months })
})
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-6 sm:py-8">
      <ProfileHeader :profile="profile" :supporter-since-label="supporterSinceLabel" @open-level="showLevelModal = true" />

      <Deferred data="profileHighlights">
        <template #fallback>
          <div class="space-y-4">
            <div class="h-28 animate-pulse rounded-2xl border border-white/10 bg-white/5" />
            <div class="h-16 animate-pulse rounded-2xl border border-white/10 bg-white/5" />
            <div class="grid gap-3 sm:grid-cols-3">
              <div v-for="index in 3" :key="index" class="h-36 animate-pulse rounded-xl border border-white/10 bg-white/5" />
            </div>
          </div>
        </template>

        <div v-if="profileHighlights" class="space-y-4">
          <ProfilePerformanceBanner :performance="profileHighlights.performance" :rank="profileHighlights.rank" />
          <ProfileBadges :badges="profileHighlights.badges" />
          <ProfileTopRooms :top-rooms="profileHighlights.top_rooms" :profile-id="profile.id" />
        </div>
      </Deferred>

      <div class="grid gap-6 lg:grid-cols-12">
        <aside class="space-y-4 lg:col-span-4">
          <Card>
            <LevelInfo :level="profile.level" :current-xp="profile.current_xp" :xp-for-next-level="profile.xp_for_next_level" :total-xp="profile.total_xp" :level-metrics="profile.level_metrics" :compact="true" />
          </Card>

          <Card v-if="profile.donation_summary?.donation_count > 0">
            <template #header>
              <h2 class="text-lg font-bold text-white">{{ __('Donation history') }}</h2>
            </template>
            <Deferred data="donations">
              <template #fallback>
                <div class="space-y-3">
                  <div v-for="index in 3" :key="index" class="h-12 animate-pulse rounded-lg bg-white/5" />
                </div>
              </template>
              <DonationHistoryList :donations="donations" :summary="profile.donation_summary" />
            </Deferred>
          </Card>

          <Card>
            <template #header>
              <h2 class="text-lg font-bold text-white">{{ __('Evolution') }}</h2>
            </template>
            <Deferred data="scoreEvolution">
              <template #fallback>
                <div class="relative h-44 sm:h-48">
                  <div class="absolute inset-0 flex items-end gap-1 px-2 pb-2">
                    <div v-for="index in 7" :key="index" class="w-full animate-pulse rounded-t bg-white/10" :style="{ height: `${20 + index * 8}%`, animationDelay: `${index * 0.08}s` }" />
                  </div>
                </div>
              </template>
              <ProfileEvolutionChart :score-evolution="scoreEvolution" />
            </Deferred>
          </Card>
        </aside>

        <main class="space-y-4 lg:col-span-8">
          <ProfileTabs :profile-id="profile.id" :active-tab="activeTab" />

          <Card>
            <ScoresTab v-if="activeTab === 'scores'" :paginator="scores" :profile-id="profile.id" :sort="scoresSort" :direction="scoresDirection" />
            <LikesTab v-else-if="activeTab === 'likes'" :paginator="likes" :profile="profile" />
            <BookmarksTab v-else-if="activeTab === 'bookmarks'" :paginator="bookmarks" :profile-id="profile.id" />
            <MinigamesTab v-else :minigames="minigames" :profile-id="profile.id" />
          </Card>
        </main>
      </div>

      <LevelModal :show="showLevelModal" :level="profile.level" :current-xp="profile.current_xp" :xp-for-next-level="profile.xp_for_next_level" :total-xp="profile.total_xp" :level-metrics="profile.level_metrics" @close="showLevelModal = false" />
    </div>
  </AppLayout>
</template>
