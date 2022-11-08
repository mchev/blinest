<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
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

const form = useForm({})
</script>
<template>
  <Head :title="__('Moderation')" />
  <AppLayout>
    <Stats :stats="stats" />
    <UsersManagement class="my-4"/>

    <div class="mb-4 flex w-full flex-wrap gap-4">
      <TrashedMessages class="lg:w-2/3" :trashedMessages="trashedMessages" />
      <BannedUsers class="flex-grow" :bannedUsers="bannedUsers" />
    </div>
    <div class="flex w-full flex-wrap gap-4">
      <Moderators class="flex-grow" :moderators="moderators" />
    </div>
  </AppLayout>
</template>
