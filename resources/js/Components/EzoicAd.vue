<script setup>
import { onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { shouldServeEzoicAds } from '@/ezoic'

const props = defineProps({
    placementId: {
        type: Number,
        required: true,
    },
})

const page = usePage()

onMounted(() => {
    if (! shouldServeEzoicAds(page.url)) {
        return;
    }

    if (typeof window.ezstandalone === 'undefined') {
        return;
    }

    window.ezstandalone.cmd.push(function () {
        window.ezstandalone.showAds(props.placementId);
    });
});
</script>

<template>
    <div :id="`ezoic-pub-ad-placeholder-${placementId}`" />
</template>
