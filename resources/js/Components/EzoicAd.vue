<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { shouldServeEzoicAds } from '@/ezoic'

const props = defineProps({
  placementId: {
    type: Number,
    required: true,
  },
  compact: {
    type: Boolean,
    default: false,
  },
  wrapperClass: {
    type: String,
    default: '',
  },
})

const page = usePage()

const adsDisabled = computed(() => page.props.donation_goal?.ads_disabled ?? false)
const visible = computed(() => shouldServeEzoicAds(page.url, { adsDisabled: adsDisabled.value }))
</script>

<template>
  <div v-if="visible" class="ezoic-ad-slot overflow-hidden rounded-xl border border-slate-700/40 bg-slate-800/20" :class="[compact ? 'min-h-[60px]' : 'min-h-[90px]', wrapperClass]">
    <div :id="`ezoic-pub-ad-placeholder-${placementId}`" />
  </div>
</template>
