<script setup>
import { Head } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  stats: Object,
  timeStats: Object,
  recentActivity: Object,
  roomStats: Array,
})

const statCards = [
  {
    name: 'Utilisateurs totaux',
    value: props.stats.total_users,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
    </svg>`,
    change: props.timeStats.new_users_today,
    changeType: 'increase',
  },
  {
    name: 'Salles totales',
    value: props.stats.total_rooms,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
    </svg>`,
    subtext: `${props.stats.public_rooms} publiques, ${props.stats.private_rooms} privées`,
  },
  {
    name: 'Playlists totales',
    value: props.stats.total_playlists,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
    </svg>`,
    subtext: `${props.stats.public_playlists} publiques, ${props.stats.private_playlists} privées`,
  },
  {
    name: 'Messages totaux',
    value: props.stats.total_messages,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
    </svg>`,
    change: props.timeStats.new_messages_today,
    changeType: 'increase',
  },
  {
    name: 'Messages supprimés',
    value: props.stats.deleted_messages,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
    </svg>`,
    change: props.timeStats.deleted_messages_today,
    changeType: 'increase',
  },
  {
    name: 'Utilisateurs bannis',
    value: props.stats.banned_users,
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
    </svg>`,
    change: props.timeStats.banned_users_today,
    changeType: 'increase',
  },
]
</script>

<template>
  <Layout title="Tableau de bord">
    <div class="space-y-6">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="stat in statCards" :key="stat.name" class="overflow-hidden rounded-lg bg-black/20 backdrop-blur-sm shadow">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-lg bg-teal-500/10 p-3 text-teal-500" v-html="stat.icon" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="truncate text-sm font-medium text-neutral-400">{{ stat.name }}</dt>
                  <dd>
                    <div class="text-lg font-medium text-white">{{ stat.value }}</div>
                    <div v-if="stat.subtext" class="mt-1 text-xs text-neutral-400">{{ stat.subtext }}</div>
                  </dd>
                </dl>
              </div>
            </div>
          </div>
          <div v-if="stat.change !== undefined" class="bg-black/20 px-5 py-3">
            <div class="text-sm">
              <span class="font-medium text-teal-400">+{{ stat.change }}</span>
              <span class="text-neutral-400"> aujourd'hui</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity and Room Stats -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Activity -->
        <Card class="overflow-hidden">
          <div class="bg-black/20 backdrop-blur-sm p-6">
            <h3 class="text-lg font-medium text-white">Activité récente</h3>
            <div class="mt-6 space-y-6">
              <!-- Deleted Messages -->
              <div>
                <h4 class="text-sm font-medium text-teal-400">Messages supprimés récemment</h4>
                <ul role="list" class="mt-3 divide-y divide-neutral-800">
                  <li v-for="message in recentActivity.deleted_messages" :key="message.id" class="py-3">
                    <div class="flex items-center justify-between">
                      <div class="flex-1 min-w-0">
                        <p class="text-sm text-white truncate">{{ message.body }}</p>
                        <p class="mt-1 text-xs text-neutral-400">
                          Par {{ message.user.name }} dans {{ message.room.name }}
                        </p>
                      </div>
                      <div class="ml-4 text-xs text-neutral-400">
                        {{ message.deleted_at }}
                      </div>
                    </div>
                  </li>
                </ul>
              </div>

              <!-- Banned Users -->
              <div>
                <h4 class="text-sm font-medium text-teal-400">Utilisateurs bannis récemment</h4>
                <ul role="list" class="mt-3 divide-y divide-neutral-800">
                  <li v-for="user in recentActivity.banned_users" :key="user.id" class="py-3">
                    <div class="flex items-center justify-between">
                      <div class="flex-1 min-w-0">
                        <p class="text-sm text-white truncate">{{ user.name }}</p>
                        <p v-if="user.team_name" class="mt-1 text-xs text-neutral-400">{{ user.team_name }}</p>
                        <p v-if="user.reason" class="mt-1 text-xs text-orange-400">{{ user.reason }}</p>
                      </div>
                      <div class="ml-4 text-xs text-neutral-400 text-right">
                        le {{ user.banned_at }} par {{ user.moderator_name }}
                        <p v-if="user.duration" class="mt-1 text-xs text-neutral-400">Durée : {{ user.duration }}</p>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </Card>

        <!-- Room Stats -->
        <Card class="overflow-hidden">
          <div class="bg-black/20 backdrop-blur-sm p-6">
            <h3 class="text-lg font-medium text-white">Rooms populaires</h3>
            <div class="mt-6">
              <ul role="list" class="divide-y divide-neutral-800">
                <li v-for="room in roomStats" :key="room.id" class="py-3">
                  <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-white truncate">{{ room.name }}</p>
                      <div class="mt-1 flex items-center gap-4 text-xs text-neutral-400">
                        <span>{{ room.messages_count }} messages</span>
                        <span>{{ room.users_count }} utilisateurs</span>
                        <span v-if="room.is_public" class="text-teal-400">Publique</span>
                        <span v-else class="text-neutral-400">Privée</span>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </Layout>
</template> 