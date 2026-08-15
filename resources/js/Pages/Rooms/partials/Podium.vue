<script setup>
import { computed } from 'vue'
import EloBadge from '@/Components/EloBadge.vue'

const props = defineProps({
  list: Object,
});

const podiumColors = {
  0: 'from-yellow-400 to-yellow-600', // Gold for 1st
  1: 'from-gray-300 to-gray-400',     // Silver for 2nd
  2: 'from-amber-600 to-amber-800',   // Bronze for 3rd
  3: 'from-purple-500 to-purple-700', // 4th
  4: 'from-blue-500 to-blue-700',     // 5th
};

const podiumHeights = {
  0: 'h-32',  // 1st place
  1: 'h-24',  // 2nd place
  2: 'h-20',  // 3rd place
  3: 'h-28',  // 4th place
  4: 'h-24',  // 5th place
};

const hasEntries = computed(() => props.list && props.list.length > 0);
</script>

<template>
  <div v-if="hasEntries" class="relative w-full py-6 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-0 left-1/4 w-32 h-32 rounded-full bg-brand-secondary blur-3xl opacity-20"></div>
      <div class="absolute bottom-0 right-1/4 w-32 h-32 rounded-full bg-brand-accent blur-3xl opacity-20"></div>
    </div>
    
    <!-- Podium stands -->
    <div class="flex items-end justify-center gap-2 md:gap-4 h-48 relative z-10">
      <!-- 3rd place -->
      <div v-if="list[2]" class="podium-column">
        <div class="flex flex-col items-center">
          <div class="avatar-container">
            <img 
              class="h-12 w-12 rounded-full object-cover border-2 border-brand-primary shadow-lg" 
              :src="list[2].user ? list[2].user.photo : (list[2].team ? list[2].team.photo : '')" 
              :alt="list[2].user ? list[2].user.name : (list[2].team ? list[2].team.name : 'Third place')"
            />
            <div class="medal bronze">3</div>
          </div>
          <div class="mt-1 text-xs font-medium text-center text-white/80 max-w-20 truncate">
            {{ list[2].user ? list[2].user.name : (list[2].team ? list[2].team.name : '') }}
          </div>
          <div class="flex items-center justify-center gap-1 mt-0.5">
            <EloBadge v-if="list[2].user?.elo" :elo="list[2].user.elo" size="sm" variant="compact" />
          </div>
          <div class="text-brand-primary font-bold text-sm">{{ list[2].score || list[2].total || 0 }} pts</div>
          <div class="podium-stand bg-brand-primary">
            <span class="text-lg font-bold">3</span>
          </div>
        </div>
      </div>
      
      <!-- 1st place -->
      <div v-if="list[0]" class="podium-column scale-110 z-20">
        <div class="flex flex-col items-center">
          <div class="avatar-container">
            <img 
              class="h-14 w-14 rounded-full object-cover border-2 border-brand-secondary shadow-lg" 
              :src="list[0].user ? list[0].user.photo : (list[0].team ? list[0].team.photo : '')" 
              :alt="list[0].user ? list[0].user.name : (list[0].team ? list[0].team.name : 'First place')"
            />
            <div class="medal gold">1</div>
          </div>
          <div class="mt-1 text-xs font-bold text-center text-white max-w-24 truncate">
            {{ list[0].user ? list[0].user.name : (list[0].team ? list[0].team.name : '') }}
          </div>
          <div class="flex items-center justify-center gap-1 mt-0.5">
            <EloBadge v-if="list[0].user?.elo" :elo="list[0].user.elo" size="sm" variant="compact" />
          </div>
          <div class="text-brand-secondary font-bold text-sm">{{ list[0].score || list[0].total || 0 }} pts</div>
          <div class="podium-stand bg-brand-secondary text-brand-midnight">
            <span class="text-xl font-bold">1</span>
          </div>
        </div>
      </div>
      
      <!-- 2nd place -->
      <div v-if="list[1]" class="podium-column">
        <div class="flex flex-col items-center">
          <div class="avatar-container">
            <img 
              class="h-12 w-12 rounded-full object-cover border-2 border-brand-accent shadow-lg" 
              :src="list[1].user ? list[1].user.photo : (list[1].team ? list[1].team.photo : '')" 
              :alt="list[1].user ? list[1].user.name : (list[1].team ? list[1].team.name : 'Second place')"
            />
            <div class="medal silver">2</div>
          </div>
          <div class="mt-1 text-xs font-medium text-center text-white/80 max-w-20 truncate">
            {{ list[1].user ? list[1].user.name : (list[1].team ? list[1].team.name : '') }}
          </div>
          <div class="flex items-center justify-center gap-1 mt-0.5">
            <EloBadge v-if="list[1].user?.elo" :elo="list[1].user.elo" size="sm" variant="compact" />
          </div>
          <div class="text-brand-accent font-bold text-sm">{{ list[1].score || list[1].total || 0 }} pts</div>
          <div class="podium-stand bg-brand-accent text-brand-midnight">
            <span class="text-lg font-bold">2</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 4th and 5th places -->
    <div class="flex justify-center gap-4 mt-4">
      <div v-if="list[3]" class="flex items-center gap-2 border border-white/10 bg-brand-midnight px-3 py-1">
        <div class="flex-shrink-0 relative">
          <img
            class="h-8 w-8 rounded-full object-cover border border-brand-accent" 
            :src="list[3].user ? list[3].user.photo : (list[3].team ? list[3].team.photo : '')" 
          />
          <div class="absolute -top-1 -right-1 h-4 w-4 bg-brand-accent flex items-center justify-center text-[10px] font-bold text-brand-midnight">4</div>
        </div>
        <div class="text-xs">
          <div class="font-medium text-white/80 truncate max-w-24">
            {{ list[3].user ? list[3].user.name : (list[3].team ? list[3].team.name : '') }}
          </div>
          <div class="flex items-center gap-1 mt-0.5">
            <EloBadge v-if="list[3].user?.elo" :elo="list[3].user.elo" size="sm" variant="compact" />
          </div>
          <div class="text-brand-accent text-[10px] font-bold">{{ list[3].score || list[3].total || 0 }} pts</div>
        </div>
      </div>

      <div v-if="list[4]" class="flex items-center gap-2 border border-white/10 bg-brand-midnight px-3 py-1">
        <div class="flex-shrink-0 relative">
          <img
            class="h-8 w-8 rounded-full object-cover border border-brand-primary" 
            :src="list[4].user ? list[4].user.photo : (list[4].team ? list[4].team.photo : '')" 
          />
          <div class="absolute -top-1 -right-1 h-4 w-4 bg-brand-primary flex items-center justify-center text-[10px] font-bold text-white">5</div>
        </div>
        <div class="text-xs">
          <div class="font-medium text-white/80 truncate max-w-24">
            {{ list[4].user ? list[4].user.name : (list[4].team ? list[4].team.name : '') }}
          </div>
          <div class="flex items-center gap-1 mt-0.5">
            <EloBadge v-if="list[4].user?.elo" :elo="list[4].user.elo" size="sm" variant="compact" />
          </div>
          <div class="text-brand-primary text-[10px] font-bold">{{ list[4].score || list[4].total || 0 }} pts</div>
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
  transition: all 0.3s ease;
}

.podium-stand {
  height: 0;
  width: 50px;
  border-radius: 6px 6px 0 0;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 6px;
  color: white;
  box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
  animation: grow-up 1s ease-out forwards;
  overflow: hidden;
}

.avatar-container {
  position: relative;
  margin-bottom: 6px;
  animation: bounce-in 0.5s ease-out;
}

.medal {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 11px;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.gold {
  background: #F9ED69;
  color: #1A1A2E;
  box-shadow: var(--glow-secondary);
}

.silver {
  background: #00ADB5;
  color: #1A1A2E;
  box-shadow: var(--glow-accent);
}

.bronze {
  background: #E94560;
  box-shadow: var(--glow-primary);
}

@keyframes grow-up {
  from { height: 0; }
  to { height: 80px; }
}

@keyframes bounce-in {
  0% { transform: scale(0); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}
</style>
