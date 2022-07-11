<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
</script>
<template>
	<div class="md:flex md:flex-shrink-0">
		<div class="flex items-center justify-between px-12 py-2 md:flex-shrink-0">
			<Link class="mt-1" :href="route('home')">
				<logo class="fill-white" width="120" height="28" />
			</Link>
			<dropdown class="md:hidden" placement="bottom-end">
				<template #default>
					<svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
				</template>
				<template #dropdown>
					<div class="mt-2 rounded px-8 py-4 shadow-lg">
						<main-menu />
					</div>
				</template>
			</dropdown>
		</div>
		<div class="md:text-md grid w-full grid-cols-2 items-center p-4 text-sm md:px-12 md:py-0">
			<main-menu class="hidden w-auto md:flex" />

			<div class="flex items-center justify-end">
				<Notifications class="mr-4" />
				<UserDropdown class="mr-4" />
				<LanguageSwitcher class="mr-4" />
			</div>
		</div>
	</div>
</template>
