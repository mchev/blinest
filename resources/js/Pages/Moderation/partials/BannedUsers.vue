<script setup>
import { router } from '@inertiajs/vue3'
import Card from '@/Components/Card.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  bannedUsers: Object,
})

const restoreMessage = (user) => {
  router.put(route('moderation.users.restore', user), {
    preserveScroll: true,
  })
}
</script>
<template>
  <Card>
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-left font-bold">
          <th class="px-2 pb-4 pt-6">Utilisateur</th>
          <th class="px-2 pb-4 pt-6">Bans</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in bannedUsers.data" :key="user.id">
          <td class="border-t px-2 py-4">
            <div class="flex items-center gap-2">
              <img :src="user.photo" class="h-10 w-10 rounded-full" />
              <div class="flex flex-col">
                {{ user.name }}
                <span class="text-xs text-neutral-500">ID : {{ user.id }}</span>
              </div>
            </div>
          </td>
          <td class="border-t px-2 py-4">
              <ul class="flex flex-col">
                <li v-for="ban in user.bans" :key="ban.id" class="flex flex-col border-b border-neutral-600 p-2">
                  <span class="text-xs text-neutral-500">Banni par : {{ ban.banned_by }}</span>
                  <span class="text-xs text-neutral-500">le : {{ ban.created_at }}</span>
                  <span class="text-xs text-neutral-500">Raison : {{ ban.comment }}</span>
                  <span class="text-xs text-neutral-500">Expire le : {{ ban.expired_at }}</span>
<!--                   <button class="btn-secondary btn-sm mt-2">Annuler le ban</button>
 -->                </li>
              </ul>
          </td>
        </tr>
      </tbody>
    </table>
    <Pagination :links="bannedUsers.links" />
  </Card>
</template>
