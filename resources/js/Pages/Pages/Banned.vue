<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'
import Tip from '@/Components/Tip.vue'

defineProps({
  ban: Object,
})
</script>
<template>
  <Head>
    <title>{{ __('You have been banned!') }}</title>
  </Head>
  <AppLayout>
    <Card class="mx-auto">
      <template #header>
        <div class="flex w-full items-center justify-between">
          <h1 class="my-4 text-xl text-red-500">{{ __('You have been banned from blinest.com!') }}</h1>
        </div>
      </template>
      <div class="prose prose-invert" v-if="ban">
        <p><b>{{ __('Reason for ban:') }}</b><br />{{ ban.comment }}</p>
        <p><b>{{ __('The eviction from the site ends on:') }}</b> <br />{{ ban.expired_at }}</p>
        <Tip class="text-orange-500">{{ __('In some cases, a report can be made to the relevant authorities.') }}</Tip>
        <Tip class="text-orange-500">{{ __('You can always access your account management to comply with data protection rules, but in case of reporting we reserve the right to retain evidence as provided by law.') }}</Tip>
      </div>
      <div class="prose prose-invert" v-else>
        <p>{{ __('The IP address you are browsing from has been blocked.') }}</p>
        <Tip class="text-orange-500">{{ __('The IP address is blocked because a user with the same address was banned or suspicious activity was detected on that IP.') }}</Tip>
      </div>
    </Card>
  </AppLayout>
</template>
