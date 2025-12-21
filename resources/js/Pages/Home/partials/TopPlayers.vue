<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import EloBadge from '@/Components/EloBadge.vue'

const props = defineProps({
	list: Object,
})
</script>
<template>
	<article>
		<h3 class="text-center text-2xl font-bold uppercase text-shark-200 mb-4">Top 10 de la semaine</h3>
			<ul class="flex flex-wrap items-center justify-center">
              <li v-for="(score, index) in list" :key="score.user?.id || index" class="m-4 flex flex-col items-center">
                <div class="relative">
                  <span class="absolute -left-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 p-1 text-neutral-700">{{ index + 1 }}</span>
                  <Link v-if="score.user?.id" :href="route('user.profile', { user: score.user.id })"><img :src="score.user.photo" class="mb-2 h-20 w-20 rounded-full" /></Link>
                  <img v-else :src="score.user?.photo || 'https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'" class="mb-2 h-20 w-20 rounded-full" />
                </div>
                <Link v-if="score.user?.id" :href="route('user.profile', { user: score.user.id })" class="mb-1 font-bold">{{ score.user.name }}</Link>
                <span v-else class="mb-1 font-bold text-neutral-400">{{ score.user?.name || __('Deleted user') }}</span>
                <div class="flex flex-col items-center gap-1">
                  <span>{{ score.total_score }}<sup>{{ __('PTS') }}</sup></span>
                  <EloBadge v-if="score.user?.elo" :elo="score.user.elo" size="sm" variant="minimal" />
                </div>
              </li>
            </ul>
	</article>
</template>
