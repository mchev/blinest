<script setup>
import { Link } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Icon from '@/Components/Icon.vue'

defineProps({
  playlist: Object,
})
</script>
<template>
  <Card class="w-full">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-bold text-neutral-200">{{ __('Rooms') }}</h3>
          <p class="text-sm text-neutral-200 mt-0.5">{{ __('Rooms using this playlist') }}</p>
        </div>
        <span v-if="playlist.rooms.length" class="flex items-center justify-center h-6 px-2 rounded-full bg-teal-500/20 text-xs font-medium text-teal-400">
          {{ playlist.rooms.length }}
        </span>
      </div>
    </template>

    <div class="p-4 lg:p-5">
      <div v-if="playlist.rooms.length" class="relative">
        <ul class="space-y-2 max-h-96 overflow-y-auto pr-2">
          <li 
            v-for="room in playlist.rooms" 
            :key="room.id" 
            class="group flex items-center gap-3 rounded-lg p-3 bg-neutral-800/40 border border-neutral-700/50 hover:bg-neutral-800/60 hover:border-teal-500/30 transition-all"
          >
            <div class="relative flex-shrink-0">
              <img 
                v-if="room.photo" 
                :src="room.photo" 
                class="h-10 w-10 rounded-lg object-cover"
                :alt="room.name"
              >
              <div v-else class="h-10 w-10 rounded-lg bg-gradient-to-br from-neutral-700 to-neutral-800 flex items-center justify-center">
                <Icon name="office" class="h-5 w-5 text-neutral-500" />
              </div>
            </div>
            
            <div class="flex-1 min-w-0">
              <Link 
                :href="route('rooms.show', room.slug)"
                class="block text-sm font-medium text-neutral-200 hover:text-teal-400 transition-colors truncate"
              >
                {{ room.name }}
              </Link>
              <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-sm text-neutral-300">{{ __('Owner') }}:</span>
                <Link 
                  v-if="room.owner?.id"
                  :href="route('user.profile', { user: room.owner.id })"
                  class="text-sm text-neutral-300 hover:text-teal-400 transition-colors truncate"
                >
                  {{ room.owner.name }}
                </Link>
                <span v-else class="text-sm text-neutral-300 italic">{{ room.owner?.name || __('Deleted user') }}</span>
              </div>
            </div>
            
            <Link 
              :href="route('rooms.show', room.slug)"
              class="flex items-center justify-center h-7 w-7 rounded-lg text-neutral-400 hover:text-teal-400 hover:bg-teal-500/10 transition-colors flex-shrink-0 opacity-0 group-hover:opacity-100"
              :title="__('View room')"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </Link>
          </li>
        </ul>
        <!-- Scroll indicator - shows when there are more than 5 rooms -->
        <div v-if="playlist.rooms.length > 5" class="absolute bottom-0 left-0 right-0 h-8 pointer-events-none bg-gradient-to-t from-slate-800/50 to-transparent rounded-b-lg"></div>
      </div>
      <div v-else class="rounded-lg p-6 bg-neutral-800/20 border border-neutral-700/30 text-center">
        <Icon name="office" class="h-10 w-10 mx-auto mb-3 text-neutral-600" />
        <p class="text-sm font-medium text-neutral-300 mb-1">{{ __('No rooms yet') }}</p>
        <p class="text-sm text-neutral-300">{{ __('This playlist is not used in any room') }}</p>
      </div>
    </div>
  </Card>
</template>
