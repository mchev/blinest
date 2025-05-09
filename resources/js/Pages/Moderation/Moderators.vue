<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  roomsWithModerators: Array,
  filters: Object,
})

const search = ref(props.filters?.search || '')

function submitSearch() {
  router.get(route('moderation.moderators.index'), { search: search.value }, { preserveState: true, replace: true })
}
</script>

<template>
  <Layout title="Modérateurs">
    <Card class="overflow-hidden">
      <div class="bg-black/20 backdrop-blur-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
          <h2 class="text-xl font-bold text-white">Modérateurs par salle publique</h2>
          <form @submit.prevent="submitSearch" class="flex gap-2">
            <input
              v-model="search"
              type="text"
              placeholder="Rechercher des modérateurs..."
              class="rounded bg-neutral-800 px-3 py-2 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-teal-500"
            />
            <button type="submit" class="rounded bg-teal-500 px-4 py-2 text-white font-semibold hover:bg-teal-600 transition">Rechercher</button>
          </form>
        </div>
        <ul>
          <li v-for="room in roomsWithModerators" :key="room.id" class="mb-8 border-b border-neutral-700 pb-6">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-5 h-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
              </svg>
              <span class="text-lg font-semibold text-white">{{ room.name }}</span>
            </div>
            <div v-if="room.moderators.length" class="flex flex-wrap gap-3 mt-2">
              <div v-for="moderator in room.moderators" :key="moderator.id" class="flex items-center gap-2 bg-neutral-800 rounded px-3 py-2">
                <img v-if="moderator.photo" :src="moderator.photo" class="h-7 w-7 rounded-full object-cover border border-neutral-700" />
                <span class="text-white font-medium">{{ moderator.name }}</span>
                <span v-if="moderator.email" class="text-xs text-neutral-400 ml-2">{{ moderator.email }}</span>
              </div>
            </div>
            <div v-else class="text-neutral-400 italic mt-2">Aucun modérateur pour cette salle.</div>
          </li>
        </ul>
      </div>
    </Card>
  </Layout>
</template> 