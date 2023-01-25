<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LoginForm from './LoginForm.vue'
</script>
<template>
  <AppLayout>
    <Head :title="__('Login')" />
    <LoginForm />
  </AppLayout>
</template>
