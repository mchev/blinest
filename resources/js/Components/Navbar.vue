<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Icon from '@/Components/Icon.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'

const user = usePage().props.value.auth?.user
</script>
<template>
	<div class="md:flex md:flex-shrink-0">
		<div class="flex w-full items-center justify-between px-4 py-2 md:flex-shrink-0 md:px-12">
			<Link class="mt-1" :href="route('home')">
				<logo class="fill-white" width="120" height="28" />
			</Link>

			<SearchRooms class="mt-1 hidden md:flex" />

			<div class="flex items-center justify-end">
				<Link v-if="user" :href="route('rankings.index')" :title="__('Rankings')"><Icon name="podium" class="h-4 W-4" /></Link>
				<Notifications class="mr-4" v-if="user" />
				<LanguageSwitcher :class="user ? 'mr-4' : ''" />
				<UserDropdown v-if="user" />
			</div>
		</div>
	</div>
</template>
