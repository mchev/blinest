<script setup>
import { usePage } from '@inertiajs/inertia-vue3'

defineProps({
  message: Object,
})

const user = usePage().props.value.auth.user
</script>
<template>
  <div class="my-1 text-sm">
    <!--     <time class="mr-1">{{ message.time }}</time>
 -->
    <span class="font-bold mr-1">{{ message.user.name }}</span
    >
    <span class="">: {{ message.body }}</span>
  </div>
</template>
