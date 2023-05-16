<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user

</script>
<template>
  <Link href="/account/billing" class="rounded-full px-2 text-xs text-white font-semibold uppercase" :class="user.is_pro ? 'bg-green-600' : 'bg-orange-400'">Pro</Link>
</template>