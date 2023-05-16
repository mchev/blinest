<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AccountLayout from '@/Layouts/AccountLayout.vue'
import Card from '@/Components/Card.vue'

import UserRoundsStats from './partials/UserRoundsStats.vue'
import UserScoresStats from './partials/UserScoresStats.vue'

defineProps({
  summary: Object,
  rounds: Object
})
</script>
<template>
  <AccountLayout>
    <div class="flex flex-col gap-4">
      <Card>
        <UserScoresStats :summary="summary"/>
      </Card>
      <Card>
        <UserRoundsStats :rounds="rounds"/>
      </Card>
    </div>
  </AccountLayout>
</template>