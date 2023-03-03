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

</script>
<template>
  <Card>
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-left font-bold">
          <th class="px-2 pb-4 pt-6">{{ __('User') }}</th>
          <th class="px-2 pb-4 pt-6">{{ __('Bans') }}</th>
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
                <span class="text-xs text-neutral-500">IP : {{ user.ip }}</span>
              </div>
            </div>
          </td>
          <td class="border-t px-2 py-4">
            <ul class="flex flex-col">
              <li v-for="ban in user.bans" :key="ban.id" class="flex flex-col bg-neutral-700 text-sm rounded p-2 my-2">
                <span>Banni par : {{ ban.banned_by }}</span>
                <span>le : {{ ban.created_at }}</span>
                <span class="font-bold">Raison : {{ ban.comment }}</span>
                <span>Fin du ban {{ ban.expired_at }}</span>
                <span>IP : {{ ban.ip }}</span>
              </li>
            </ul>
          </td>
          <td class="border-t px-2 py-4">
            <LoadingButton type="button" @click="unban(user)" :loading="form.processing" class="btn-primary text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
              </svg>
              {{ __('Unban') }}
            </LoadingButton>
          </td>
        </tr>
      </tbody>
    </table>
    <Pagination :links="bannedUsers.links" />
  </Card>
</template>
