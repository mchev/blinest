<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import EloBadge from '@/Components/EloBadge.vue'

const props = defineProps({
	list: {
		type: Array,
		default: () => [],
	},
	embedded: {
		type: Boolean,
		default: false,
	},
})

const podium = computed(() => (props.list || []).slice(0, 3))
const rest = computed(() => (props.list || []).slice(3))

const podiumOrder = computed(() => {
	if (podium.value.length < 2) {
		return podium.value.map((player, index) => ({ player, rank: index + 1 }))
	}

	return [
		{ player: podium.value[1], rank: 2 },
		{ player: podium.value[0], rank: 1 },
		{ player: podium.value[2], rank: 3 },
	].filter((entry) => entry.player)
})

const podiumHeight = (rank) => {
	if (rank === 1) return 'h-24'
	if (rank === 2) return 'h-16'
	return 'h-12'
}

const rankBadgeClass = (rank) => {
	if (rank === 1) return 'retro-rank-badge retro-rank-badge--1'
	if (rank === 2) return 'retro-rank-badge retro-rank-badge--2'
	return 'retro-rank-badge retro-rank-badge--3'
}
</script>

<template>
	<article v-if="list && list.length">
		<h3
			v-if="!embedded"
			class="retro-title retro-title--secondary mb-5 text-center text-sm"
		>
			{{ __('Top 10 of the week') }}
		</h3>

		<div v-if="podium.length" class="mb-4 flex items-end justify-center gap-2 px-1" :class="{ 'mb-0': embedded && !rest.length }">
			<div
				v-for="{ player, rank } in podiumOrder"
				:key="player.user?.id || rank"
				class="flex flex-col items-center"
				:class="rank === 1 ? 'w-[34%]' : 'w-[28%]'"
			>
				<div class="relative mb-2">
					<span :class="rankBadgeClass(rank)">{{ rank }}</span>
					<Link v-if="player.user?.id" :href="route('user.profile', { user: player.user.id })">
						<img
							:src="player.user.photo"
							:alt="player.user.name"
							class="rounded-full object-cover ring-2 ring-white/20 ring-offset-2 ring-offset-brand-deep"
							:class="rank === 1 ? 'h-16 w-16' : 'h-12 w-12'"
							loading="lazy"
						/>
					</Link>
					<img
						v-else
						:src="player.user?.photo || 'https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'"
						class="h-12 w-12 rounded-full"
						loading="lazy"
					/>
				</div>
				<div
					class="retro-podium-block"
					:class="podiumHeight(rank)"
				>
					<Link
						v-if="player.user?.id"
						:href="route('user.profile', { user: player.user.id })"
						class="truncate text-center text-xs font-bold text-white hover:text-white/80"
					>
						{{ player.user.name }}
					</Link>
					<span v-else class="truncate text-center text-xs font-bold text-white/70">
						{{ player.user?.name || __('Deleted user') }}
					</span>
					<span class="mt-1 text-xs font-bold text-white">
						{{ player.total_score }}<sup class="text-[10px]">{{ __('PTS') }}</sup>
					</span>
				</div>
			</div>
		</div>

		<ul v-if="rest.length" class="flex flex-col gap-2">
			<li
				v-for="(score, index) in rest"
				:key="score.user?.id || index + 4"
				class="flex items-center gap-3 rounded-lg border border-white/10 bg-brand-deep px-3 py-2 transition hover:border-white/20 hover:bg-brand-deep-hover"
			>
				<span class="w-5 shrink-0 text-center text-xs font-black text-white/60">{{ index + 4 }}</span>
				<Link v-if="score.user?.id" :href="route('user.profile', { user: score.user.id })" class="shrink-0">
					<img :src="score.user.photo" :alt="score.user.name" class="h-8 w-8 rounded-full ring-1 ring-white/15" loading="lazy" />
				</Link>
				<div class="min-w-0 flex-1">
					<Link
						v-if="score.user?.id"
						:href="route('user.profile', { user: score.user.id })"
						class="block truncate text-sm font-medium text-white hover:text-white/80"
					>
						{{ score.user.name }}
					</Link>
					<span v-else class="block truncate text-sm text-white/70">{{ score.user?.name || __('Deleted user') }}</span>
				</div>
				<div class="flex shrink-0 flex-col items-end gap-0.5">
					<span class="text-xs font-bold text-white">{{ score.total_score }} {{ __('PTS') }}</span>
					<EloBadge v-if="score.user?.elo" :elo="score.user.elo" size="sm" variant="minimal" />
				</div>
			</li>
		</ul>
	</article>
</template>
