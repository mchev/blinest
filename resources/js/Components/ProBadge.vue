<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user

</script>
<template>
  <Link href="/billing" class="rounded p-1 text-xs text-white font-bold uppercase" :class="user.is_pro ? 'bg-teal-500' : 'bg-orange-400'">Pro</Link>
</template>