<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  trashedMessages: Object,
})

const form = useForm({})

const restoreMessage = (message) => {
  Inertia.put(route('moderation.messages.restore', message), {
    preserveScroll: true,
  });
}

</script>
<template>
  <Head :title="__('Moderation')" />
  <AppLayout>
    <Card>
      <template #header>
        <h2 class="font-bold">Messages supprimés</h2>
      </template>
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="px-2 pb-4 pt-6">Auteur</th>
            <th class="px-2 pb-4 pt-6">Date</th>
            <th class="px-2 pb-4 pt-6">Message</th>
            <th class="px-2 pb-4 pt-6">Signalements</th>
            <th class="px-2 pb-4 pt-6">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="message in trashedMessages.data" :key="message.id">
            <td class="border-t px-2 py-4">
              <div class="flex items-center gap-2">
                <img :src="message.user.photo" class="w-10 h-10 rounded-full">
                <div class="flex flex-col">
                  {{ message.user.name }}
                  <span class="text-xs text-neutral-500">ID : {{ message.user.id }}</span>
                  <span class="text-xs text-neutral-500">IP : {{ message.user.ip }}</span>
                </div>
              </div>
            </td>
            <td class="border-t px-2 py-4">
              <div class="flex flex-col">
                {{ message.time }}
                <span class="text-xs text-neutral-500">Creation : {{ message.created_at }}</span>
                <span class="text-xs text-neutral-500">Suppression : {{ message.deleted_at }}</span>
              </div>
            </td>
            <td class="border-t px-2 py-4">
              <span class="text-sm italic">{{ message.body }}</span>
            </td>
            <td class="border-t px-2 py-4">
              <div class="flex flex-col">
                {{ message.votes.total }}
                <ul class="text-xs text-neutral-500 flex gap-2" v-for="user in message.votes.voters" :key="user.id">
                  <li>{{ user.name }}</li>
                </ul>
              </div>
            </td>
            <td class="border-t px-2 py-4">
              <div class="flex gap-2">
                <button class="btn-secondary btn-sm" @click="restoreMessage(message)">Restaurer</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <Pagination :links="trashedMessages.links" />
    </Card>
  </AppLayout>
</template>
