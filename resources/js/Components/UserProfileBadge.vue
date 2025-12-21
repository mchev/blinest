<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import LevelBadge from '@/Components/LevelBadge.vue'
import EloBadge from '@/Components/EloBadge.vue'

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  showLevel: {
    type: Boolean,
    default: true,
  },
  showElo: {
    type: Boolean,
    default: true,
  },
  showName: {
    type: Boolean,
    default: false,
  },
  variant: {
    type: String,
    default: 'badge', // 'badge' (compact around avatar) or 'full' (with name)
    validator: (value) => ['badge', 'full'].includes(value),
  },
  clickable: {
    type: Boolean,
    default: true,
  },
  ringColor: {
    type: String,
    default: 'ring-neutral-700',
  },
})

const page = usePage()

// Translation function
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

// Check if user has played any rounds
const hasPlayed = computed(() => {
  // Check if user has rounds_played_count > 0
  if (props.user.userLevel?.rounds_played_count !== undefined) {
    return props.user.userLevel.rounds_played_count > 0
  }
  // Fallback: if elo is exactly 1500 (default), assume not played
  // This is not perfect but better than showing 1500 for everyone
  if (props.user.elo === 1500) {
    return false
  }
  // If elo is different from 1500, they've definitely played
  return props.user.elo !== 1500
})

// Size configurations
const sizeConfig = computed(() => {
  const configs = {
    sm: {
      avatar: 'h-6 w-6',
      name: 'text-xs',
      gap: 'gap-1',
      badgeGap: 'gap-1',
      badgeSize: 'text-[8px]',
      badgePadding: 'px-1 py-0.5',
    },
    md: {
      avatar: 'h-8 w-8 sm:h-10 sm:w-10',
      name: 'text-sm sm:text-base',
      gap: 'gap-2',
      badgeGap: 'gap-1.5',
      badgeSize: 'text-[9px]',
      badgePadding: 'px-1.5 py-0.5',
    },
    lg: {
      avatar: 'h-10 w-10 sm:h-12 sm:w-12',
      name: 'text-base sm:text-lg',
      gap: 'gap-3',
      badgeGap: 'gap-2',
      badgeSize: 'text-[10px]',
      badgePadding: 'px-2 py-1',
    },
  }
  return configs[props.size]
})
</script>

<template>
  <!-- Badge variant: compact around avatar -->
  <div v-if="variant === 'badge'" class="relative inline-flex flex-col items-center justify-center">
    <!-- Avatar with level bubble and ELO -->
    <div class="relative flex items-center justify-center">
      <!-- Avatar -->
      <img 
        :src="user.photo" 
        :alt="user.name" 
        :class="[
          'rounded-full shadow-lg ring-2 object-cover object-center',
          sizeConfig.avatar,
          ringColor
        ]" 
      />
      
      <!-- Level number in notification bubble - top right -->
      <div 
        v-if="showLevel && user.userLevel?.level"
        class="absolute -top-1 -right-1 z-10 flex items-center justify-center rounded-full bg-purple-600 text-white font-bold shadow-lg border-2 border-neutral-900"
        :class="{
          'size-4 text-[9px]': size === 'sm',
          'size-5 text-[11px]': size === 'md',
          'size-6 text-xs': size === 'lg',
        }"
        :title="`${__('Level')} ${user.userLevel.level}`">
        {{ user.userLevel.level }}
      </div>
    </div>
    
    <!-- ELO number - completely below the avatar, reduced spacing -->
    <div v-if="showElo && user.elo" 
         class="mt-0.5 flex items-center justify-center">
      <div v-if="!hasPlayed" 
           class="text-[10px] font-medium text-neutral-500"
           :title="__('Player has not played yet - ELO will be calculated after first game')"
           aria-label="Not played yet">
        {{ __('N/A') }}
      </div>
      <span 
        v-else
        class="text-[10px] sm:text-xs font-bold text-neutral-300"
        :title="`${user.elo} ELO`"
      >
        {{ user.elo }}
      </span>
    </div>
  </div>

  <!-- Full variant: with name and badges -->
  <div v-else class="flex items-center" :class="sizeConfig.gap">
    <!-- Avatar -->
    <img 
      :src="user.photo" 
      :alt="user.name" 
      :class="[
        'rounded-full shadow-lg ring-2 flex-shrink-0',
        sizeConfig.avatar,
        ringColor
      ]" 
    />
    
    <!-- Name and badges -->
    <div class="flex flex-col min-w-0 flex-1">
      <!-- Name -->
      <div v-if="showName" class="flex items-center gap-1.5 flex-wrap">
        <Link 
          v-if="user?.id && clickable" 
          :href="route('user.profile', { user: user.id })" 
          :class="[
            'font-medium text-neutral-100 hover:text-yellow-400 transition-colors truncate',
            sizeConfig.name
          ]"
        >
          {{ user.name }}
        </Link>
        <span 
          v-else 
          :class="[
            'font-medium text-neutral-400 truncate',
            sizeConfig.name
          ]"
        >
          {{ user?.name || __('Deleted user') }}
        </span>
      </div>
      
      <!-- Level and ELO badges -->
      <div v-if="showLevel || showElo" class="flex items-center flex-wrap" :class="sizeConfig.badgeGap">
        <!-- Level Badge -->
        <LevelBadge
          v-if="showLevel && user.userLevel?.level"
          :level="user.userLevel.level"
          size="sm"
          variant="compact"
        />
        
        <!-- ELO Badge -->
        <div v-if="showElo && user.elo" class="flex items-center gap-1">
          <div v-if="!hasPlayed" 
               class="inline-flex items-center justify-center rounded-md bg-neutral-700/30 border border-neutral-600/50 px-1.5 py-0.5 text-[10px] font-bold text-neutral-500"
               :title="__('Player has not played yet - ELO will be calculated after first game')"
               aria-label="Not played yet">
            <span class="text-[10px]">{{ __('N/A') }}</span>
          </div>
          <EloBadge
            v-else
            :elo="user.elo"
            size="sm"
            variant="compact"
          />
        </div>
      </div>
    </div>
  </div>
</template>

