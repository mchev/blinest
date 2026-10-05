<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useAdsDisabled } from '@/composables/useAdsDisabled'
import { pushAdsenseSlot, shouldServeAds } from '@/ads'

const props = defineProps({
  adSlot: {
    type: String,
    default: null,
  },
  compact: {
    type: Boolean,
    default: false,
  },
  wrapperClass: {
    type: String,
    default: '',
  },
  /** Room lobby: allow ads on /rooms/{slug} when parent enables the slot. */
  forceServe: {
    type: Boolean,
    default: false,
  },
})

const page = usePage()

const clientId = import.meta.env.VITE_ADSENSE_CLIENT_ID ?? 'ca-pub-6495635642797272'
const defaultSlot = import.meta.env.VITE_ADSENSE_DEFAULT_SLOT ?? '9792147730'

const adsDisabled = useAdsDisabled()

const visible = computed(() => {
  if (adsDisabled.value) {
    return false
  }

  if (props.forceServe) {
    return true
  }

  return shouldServeAds(page.url)
})

const pushed = ref(false)

function loadAd() {
  if (!visible.value || pushed.value) {
    return
  }

  pushed.value = true
  pushAdsenseSlot()
}

onMounted(() => {
  loadAd()
})

watch(visible, (isVisible) => {
  if (!isVisible) {
    pushed.value = false

    return
  }

  loadAd()
})
</script>

<template>
  <div v-if="visible" class="adsense-slot overflow-hidden rounded-xl border border-slate-700/40 bg-slate-800/20" :class="[compact ? 'min-h-[60px]' : 'min-h-[90px]', wrapperClass]">
    <ins
      class="adsbygoogle"
      style="display: block"
      :data-ad-client="clientId"
      :data-ad-slot="adSlot ?? defaultSlot"
      data-ad-format="auto"
      data-full-width-responsive="true"
    />
  </div>
</template>
