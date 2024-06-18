<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
	list: Object,
})
</script>
<template>
	<article>
		<h3 class="text-center text-2xl font-bold uppercase text-shark-200 mb-4">Top 10 de la semaine</h3>
			<ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in list" :key="score.user.id" class="m-4 flex flex-col items-center">
                <div class="relative">
                  <span class="absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700">{{ index + 1 }}</span>
                  <Link :href="route('user.profile', score.user)"><img :src="score.user.photo" class="mb-2 h-20 w-20 rounded-full" /></Link>
                </div>
                <Link :href="route('user.profile', score.user)" class="mb-1 font-bold">{{ score.user.name }}</Link>
                <span>{{ score.total_score }}<sup>{{ __('PTS') }}</sup></span>
              </li>
            </ul>
	</article>
</template>
