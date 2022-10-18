<script setup>
import { ref } from 'vue'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Logo from '@/Components/Logo.vue'
import Icon from '@/Components/Icon.vue'
import Dropdown from '@/Components/Dropdown.vue'
import MainMenu from '@/Components/MainMenu.vue'
import SearchRooms from '@/Components/SearchRooms.vue'
import UserDropdown from '@/Components/UserDropdown.vue'
import Notifications from '@/Components/Notifications/Notifications.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import SocialIcon from '@/Components/SocialIcon.vue'

const user = usePage().props.value.auth?.user

const showingSearchbar = ref(false);

</script>
<template>
	<div class="md:flex md:flex-shrink-0">
		<div class="flex w-full items-center justify-between px-4 py-2 md:flex-shrink-0 md:px-12">
			<Link class="" :href="route('home')" title="Blinest">
				<logo class="fill-white w-24 lg:w-32" />
			</Link>

			<SearchRooms class="mt-1 hidden lg:block" />

			<div class="flex items-center justify-end">
				<Button type="button" @click="showingSearchbar = !showingSearchbar" class="lg:hidden"><Icon name="search" class="h-5 w-5 mr-4" /></Button>
				<Link v-if="user" :href="route('rankings.index')" :title="__('Rankings')"><Icon name="podium" class="h-5 w-5 mr-4" /></Link>
				<a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" :title="__('Join the Blinest community on Discord')" class="mr-4">
					<SocialIcon name="discord" class="h-5 w-5"/>
				</a>
				<Notifications class="mr-4" v-if="user" />
				<LanguageSwitcher class="mr-4"/>
				<UserDropdown v-if="user" />
<!-- 				<div v-if="!user" class="flex gap-4 uppercase">
					<Link :href="route('login')" :title="__('Login')">{{ __('Login') }}</Link>
					<Link :href="route('register')" :title="__('Register')">{{ __('Register') }}</Link>
				</div>
 -->			</div>
		</div>
		<div>
			<SearchRooms v-show="showingSearchbar" class="m-2"/>
		</div>
	</div>
</template>
