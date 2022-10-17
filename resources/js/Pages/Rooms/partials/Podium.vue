<script setup>
defineProps({
	users: Object,
})
</script>

<template>
	<div class="flex w-full justify-center gap-1 border-b border-neutral-600" v-if="users.length">
		<div class="flex flex-col justify-end" v-if="users[4]">
			<img class="mb-2 h-10 w-10 rounded-full" :src="users[4].user.photo" />
			<div class="h-10 rounded-t bg-purple-500 p-4 opacity-25">
				<span class="text-xl font-bold">5</span>
			</div>
		</div>
		<div class="flex flex-col items-center justify-end" v-if="users[2]">
			<img class="mb-2 h-10 w-10 rounded-full" :src="users[2].user.photo" />
			<div class="h-32 rounded-t bg-purple-500 p-4 opacity-60">
				<span class="text-xl font-bold">3</span>
			</div>
		</div>
		<div class="flex flex-col items-center justify-end" v-if="users[0]">
			<img class="mb-2 h-10 w-10 rounded-full" :src="users[0].user.photo" />
			<div class="h-40 rounded-t bg-purple-500 p-4">
				<span class="text-xl font-bold">1</span>
			</div>
		</div>
		<div class="flex flex-col items-center justify-end" v-if="users[1]">
			<img class="mb-2 h-10 w-10 rounded-full" :src="users[1].user.photo" />
			<div class="h-36 rounded-t bg-purple-500 p-4 opacity-80">
				<span class="text-xl font-bold">2</span>
			</div>
		</div>
		<div class="flex flex-col items-center justify-end" v-if="users[3]">
			<img class="mb-2 h-10 w-10 rounded-full" :src="users[3].user.photo" />
			<div class="h-20 rounded-t bg-purple-500 p-4 opacity-30">
				<span class="text-xl font-bold">4</span>
			</div>
		</div>
	</div>
</template>
