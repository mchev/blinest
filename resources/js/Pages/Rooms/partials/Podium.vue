<script setup>
import { computed } from 'vue'
import EloBadge from '@/Components/EloBadge.vue'

const props = defineProps({
  list: Object,
})

const hasEntries = computed(() => props.list && props.list.length > 0)

const podiumOrder = computed(() => {
  if (! props.list?.length) {
    return []
  }

  const [first, second, third] = props.list

  if (! second) {
    return first ? [{ entry: first, rank: 1 }] : []
  }

  if (! third) {
    return [
      { entry: second, rank: 2 },
      { entry: first, rank: 1 },
    ]
  }

  return [
    { entry: third, rank: 3 },
    { entry: first, rank: 1 },
    { entry: second, rank: 2 },
  ]
})

const displayName = (entry) => entry.user?.name ?? entry.team?.name ?? ''

const displayPhoto = (entry) => entry.user?.photo ?? entry.team?.photo ?? ''

const displayScore = (entry) => entry.score ?? entry.total ?? 0

const medalClass = (rank) => {
  if (rank === 1) {
    return 'medal gold'
  }

  if (rank === 2) {
    return 'medal silver'
  }

  return 'medal bronze'
}

const scoreClass = (rank) => {
  if (rank === 1) {
    return 'text-brand-secondary'
  }

  if (rank === 2) {
    return 'text-brand-accent'
  }

  return 'text-brand-primary'
}

const avatarRingClass = (rank) => {
  if (rank === 1) {
    return 'border-brand-secondary'
  }

  if (rank === 2) {
    return 'border-brand-accent'
  }

  return 'border-brand-primary'
}

const standClass = (rank) => {
  if (rank === 1) {
    return 'podium-stand bg-brand-secondary text-brand-midnight'
  }

  if (rank === 2) {
    return 'podium-stand bg-brand-accent text-brand-midnight'
  }

  return 'podium-stand bg-brand-primary'
}
</script>

<template>
  <div v-if="hasEntries" class="podium-root relative w-full min-w-0 py-3 sm:py-6">
    <div class="absolute inset-0 hidden opacity-10 sm:block">
      <div class="absolute top-0 left-1/4 h-32 w-32 rounded-full bg-brand-secondary opacity-20 blur-3xl"></div>
      <div class="absolute bottom-0 right-1/4 h-32 w-32 rounded-full bg-brand-accent opacity-20 blur-3xl"></div>
    </div>

    <div class="relative z-10 flex items-end justify-center gap-2 px-1 sm:h-48 sm:gap-4">
      <div
        v-for="{ entry, rank } in podiumOrder"
        :key="`${displayName(entry)}-${rank}`"
        class="podium-column min-w-0 max-w-[34%] flex-1"
        :class="{ 'podium-column--first': rank === 1 }"
      >
        <div class="flex flex-col items-center gap-1.5 sm:gap-1">
          <div class="avatar-container">
            <img
              class="rounded-full border-2 object-cover shadow-lg"
              :class="[
                rank === 1 ? 'h-16 w-16 sm:h-14 sm:w-14' : 'h-14 w-14 sm:h-12 sm:w-12',
                avatarRingClass(rank),
              ]"
              :src="displayPhoto(entry)"
              :alt="displayName(entry)"
            />
            <div :class="medalClass(rank)">{{ rank }}</div>
          </div>

          <p
            class="podium-name w-full truncate text-center font-semibold leading-tight text-white"
            :class="rank === 1 ? 'text-sm sm:text-xs' : 'text-xs sm:text-[11px]'"
            :title="displayName(entry)"
          >
            {{ displayName(entry) }}
          </p>

          <div class="hidden min-h-[1.25rem] items-center justify-center sm:flex">
            <EloBadge
              v-if="entry.user?.elo"
              :elo="entry.user.elo"
              size="sm"
              variant="compact"
            />
          </div>

          <p
            class="podium-score font-bold leading-none"
            :class="[
              scoreClass(rank),
              rank === 1 ? 'text-lg sm:text-sm' : 'text-base sm:text-sm',
            ]"
          >
            {{ displayScore(entry) }}
            <span class="text-[11px] font-medium text-white/60 sm:text-[10px]">pts</span>
          </p>

          <div class="podium-stand hidden sm:flex" :class="standClass(rank)">
            <span class="font-bold" :class="rank === 1 ? 'text-xl' : 'text-lg'">{{ rank }}</span>
          </div>
        </div>
      </div>
    </div>

    <div v-if="list[3] || list[4]" class="mt-3 flex flex-wrap justify-center gap-2 px-2 sm:mt-4 sm:gap-4">
      <div
        v-for="(entry, offset) in [list[3], list[4]].filter(Boolean)"
        :key="displayName(entry) + offset"
        class="flex min-w-0 max-w-full items-center gap-2.5 border border-white/10 bg-brand-midnight px-3 py-2 sm:gap-2 sm:py-1"
      >
        <div class="relative shrink-0">
          <img
            class="h-9 w-9 rounded-full border object-cover sm:h-8 sm:w-8"
            :class="offset === 0 ? 'border-brand-accent' : 'border-brand-primary'"
            :src="displayPhoto(entry)"
            :alt="displayName(entry)"
          />
          <div
            class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center text-[10px] font-bold"
            :class="offset === 0 ? 'bg-brand-accent text-brand-midnight' : 'bg-brand-primary text-white'"
          >
            {{ offset + 4 }}
          </div>
        </div>
        <div class="min-w-0 text-sm sm:text-xs">
          <div class="truncate font-medium text-white/90">
            {{ displayName(entry) }}
          </div>
          <div class="hidden sm:block">
            <EloBadge
              v-if="entry.user?.elo"
              :elo="entry.user.elo"
              size="sm"
              variant="compact"
            />
          </div>
          <div
            class="text-xs font-bold sm:text-[10px]"
            :class="offset === 0 ? 'text-brand-accent' : 'text-brand-primary'"
          >
            {{ displayScore(entry) }} pts
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.podium-column {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.podium-column--first {
  transform: scale(1.04);
}

@media (min-width: 640px) {
  .podium-column--first {
    transform: scale(1.1);
  }
}

.podium-stand {
  height: 0;
  width: 2.25rem;
  border-radius: 6px 6px 0 0;
  align-items: flex-start;
  justify-content: center;
  padding-top: 6px;
  color: white;
  box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
  animation: grow-up 1s ease-out forwards;
  overflow: hidden;
}

@media (min-width: 640px) {
  .podium-stand {
    width: 50px;
  }
}

.avatar-container {
  position: relative;
  animation: bounce-in 0.5s ease-out;
}

.medal {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 11px;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

@media (min-width: 640px) {
  .medal {
    width: 18px;
    height: 18px;
  }
}

.gold {
  background: #f9ed69;
  color: #1a1a2e;
  box-shadow: var(--glow-secondary);
}

.silver {
  background: #00adb5;
  color: #1a1a2e;
  box-shadow: var(--glow-accent);
}

.bronze {
  background: #e94560;
  box-shadow: var(--glow-primary);
}

@keyframes grow-up {
  from {
    height: 0;
  }

  to {
    height: 80px;
  }
}

@keyframes bounce-in {
  0% {
    transform: scale(0);
  }

  50% {
    transform: scale(1.2);
  }

  100% {
    transform: scale(1);
  }
}
</style>
