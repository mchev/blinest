<script setup>
import { useForm } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  bannedUsers: Object,
})

const form = useForm({});

const unban = (user) => {
  form.delete(route('user.unban', user), {
    preserveScroll: true,
    only: ['bannedUsers', 'stats']
  })
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-black/30 shadow rounded-lg overflow-hidden">
      <div v-if="bannedUsers.data.length === 0" class="py-12 text-center">
        <div class="mx-auto w-16 h-16 bg-black/40 rounded-full flex items-center justify-center mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-white">{{ __('No banned users') }}</h3>
        <p class="text-gray-400 mt-1">{{ __('There are currently no banned users in the system') }}</p>
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-left font-bold border-b border-gray-700">
              <th class="px-6 py-4 text-gray-300">{{ __('User') }}</th>
              <th class="px-6 py-4 text-gray-300">{{ __('Bans') }}</th>
              <th class="px-6 py-4 text-gray-300">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in bannedUsers.data" :key="user.id" class="hover:bg-black/40 transition-colors duration-200">
              <td class="border-t border-gray-700/50 px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="relative">
                    <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center">
                      <span class="text-lg font-medium text-white">{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                    <div class="absolute -top-1 -right-1">
                      <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                      </svg>
                    </div>
                  </div>
                  <div class="flex flex-col">
                    <span class="font-medium text-white">{{ user.name }}</span>
                    <span class="text-sm text-gray-400">{{ user.email }}</span>
                    <span class="text-xs text-gray-500">ID: {{ user.id }}</span>
                  </div>
                </div>
              </td>
              <td class="border-t border-gray-700/50 px-6 py-4">
                <div class="space-y-3">
                  <div v-for="ban in user.bans" :key="ban.id" 
                       class="flex flex-col bg-black/20 text-sm rounded-lg p-4 border border-gray-700">
                    <div class="flex justify-between items-start mb-2">
                      <span class="font-bold text-blue-400">{{ ban.comment }}</span>
                      <span class="text-xs bg-red-500/20 text-red-300 px-2 py-0.5 rounded-full">{{ __('Active') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                      <div>
                        <span class="block text-gray-400 text-xs">{{ __('Banned by') }}</span>
                        <span class="text-white">{{ ban.banned_by }}</span>
                      </div>
                      <div>
                        <span class="block text-gray-400 text-xs">{{ __('IP Address') }}</span>
                        <span class="text-white">{{ ban.ip }}</span>
                      </div>
                      <div>
                        <span class="block text-gray-400 text-xs">{{ __('Ban date') }}</span>
                        <span class="text-white">{{ formatDate(ban.created_at) }}</span>
                      </div>
                      <div>
                        <span class="block text-gray-400 text-xs">{{ __('Expires') }}</span>
                        <span class="text-white">{{ ban.expired_at ? formatDate(ban.expired_at) : __('Never') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="border-t border-gray-700/50 px-6 py-4">
                <LoadingButton 
                  type="button" 
                  @click="unban(user)" 
                  :loading="form.processing" 
                  class="inline-flex items-center px-4 py-2 bg-blue-600/80 hover:bg-blue-700/80 text-white rounded-lg transition-colors duration-200">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                  </svg>
                  {{ __('Unban') }}
                </LoadingButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="px-6 py-4 border-t border-gray-700">
        <Pagination :links="bannedUsers.links" />
      </div>
    </div>
  </div>
</template>
