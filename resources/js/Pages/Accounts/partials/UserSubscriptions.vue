<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  subscriptions: Object,
})
</script>
<template>
  <div class="overflow-x-auto">
    <table class="w-full whitespace-nowrap">
      <thead>
        <tr class="text-left font-bold">
          <th class="px-6 pb-4 pt-6">#</th>
          <th class="px-6 pb-4 pt-6">{{ __('Status') }}</th>
          <th class="px-6 pb-4 pt-6">Début</th>
          <th class="px-6 pb-4 pt-6">Fin</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="subscription in subscriptions" :key="`subscription-${subscription.id}`">
          <td class="px-6 py-4 border-t border-neutral-500">{{ subscription.id }}</td>
          <td class="px-6 py-4 border-t border-neutral-500 text-sm">{{ __(subscription.status) }}</td>
          <td class="px-6 py-4 border-t border-neutral-500">{{ subscription.created_at }}</td>
          <td class="px-6 py-4 border-t border-neutral-500">{{ subscription.ends_at }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
