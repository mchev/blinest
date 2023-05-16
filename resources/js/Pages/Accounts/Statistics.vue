<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AccountLayout from '@/Layouts/AccountLayout.vue'

import UserRoundsStats from './partials/UserRoundsStats.vue'

defineProps({
  summary: Object,
  rounds: Object
})
</script>
<template>
  <AccountLayout>
    <UserRoundsStats :rounds="rounds"/>
  </AccountLayout>
</template>