<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'



</script>
<template>
  <Head :title="__('Teams')" />
  <AppLayout>
    <h1 class="text-xl font-bold">{{ __('Discover Teams')}}</h1>
  </AppLayout>
</template>
