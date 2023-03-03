<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import Stats from './partials/Stats.vue'
import UsersManagement from './partials/UsersManagement.vue'
import Moderators from './partials/Moderators.vue'
import TrashedMessages from './partials/TrashedMessages.vue'
import BannedUsers from './partials/BannedUsers.vue'

const props = defineProps({
  stats: Object,
  moderators: Object,
  trashedMessages: Object,
  bannedUsers: Object,
})

const tab = ref('trashedMessages')
</script>
<template>
  <Head :title="__('Moderation')" />
  <AppLayout>
    <div class="flex-grow">
      <Stats :stats="stats" />

      <div>
        <ul class="flex flex-wrap border-b border-neutral-700 text-center text-sm font-medium text-neutral-400">
          <li class="mr-2">
            <button type="button" @click="tab = 'trashedMessages'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'trashedMessages' }">Messages supprimés ({{ trashedMessages.total }})</button>
          </li>
          <li class="mr-2">
            <button type="button" @click="tab = 'bans'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'bans' }">Bans ({{ bannedUsers.total }})</button>
          </li>
          <li class="mr-2">
            <button type="button" @click="tab = 'userManagement'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'userManagement' }">Gestion des utilisateurs</button>
          </li>
          <li class="mr-2">
            <button type="button" @click="tab = 'moderators'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'moderators' }">Modérateurs</button>
          </li>
        </ul>

        <TrashedMessages v-show="tab === 'trashedMessages'" class="rounded-t-none" :trashedMessages="trashedMessages" />
        <BannedUsers v-show="tab === 'bans'" class="rounded-t-none" :bannedUsers="bannedUsers" />
        <UsersManagement v-show="tab === 'userManagement'" class="rounded-t-none" />
        <Moderators v-show="tab === 'moderators'" class="rounded-t-none" :moderators="moderators" />
      </div>
    </div>
  </AppLayout>
</template>
