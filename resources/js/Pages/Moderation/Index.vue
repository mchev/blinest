<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import TrashedMessages from './partials/TrashedMessages.vue'
import BannedUsers from './partials/BannedUsers.vue'

const props = defineProps({
  trashedMessages: Object,
  bannedUsers: Object,
})

const form = useForm({})

</script>
<template>
  <Head :title="__('Moderation')" />
  <AppLayout>
    <div class="flex w-full flex-wrap gap-4">
      <TrashedMessages class="flex-grow" :trashedMessages="trashedMessages"/>
      <BannedUsers class="flex-grow" :bannedUsers="bannedUsers"/>
    </div>
  </AppLayout>
</template>
