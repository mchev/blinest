<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AccountLayout from '@/Layouts/AccountLayout.vue'
import Card from '@/Components/Card.vue'

import LikesList from './partials/LikesList.vue'

defineProps({
  likes: Object,
})

</script>
<template>
  <AccountLayout>
    <Card>
      <template #header>
        <h2 class="text-xl font-bold">{{ __('Likes') }}</h2>
      </template>
      <LikesList :likes="likes"/>
      <template #footer>
        <Link :href="route('playlists.create')" class="btn-primary ml-auto">{{ __('Create a playlist') }}</Link>
      </template>
    </Card>
  </AccountLayout>
</template>