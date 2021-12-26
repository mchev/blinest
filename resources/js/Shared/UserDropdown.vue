<template>
	<dropdown class="mt-1" placement="bottom-end">
	  <template #default>
	    <div class="group flex items-center cursor-pointer select-none">
	      <div class="mr-1 whitespace-nowrap">
	        <span>{{ user.name }}</span>
	      </div>
	      <icon class="w-5 h-5" name="cheveron-down" />
	    </div>
	  </template>
	  <template #dropdown>
	    <Link class="block px-6 py-2" :href="route('users.edit', user)">{{ __('My Profile') }}</Link>
	    <Link class="block px-6 py-2" :href="route('playlists')">{{ __('Playlists') }}</Link>
	    <Link class="block px-6 py-2" href="/users">{{ __('Rooms') }}</Link>
	    <Link class="block px-6 py-2" href="/users">{{ __('Team') }}</Link>
	    <Link class="block px-6 py-2" href="/users">{{ __('Scores') }}</Link>
	    <div class="border-t"/>
	    <Link v-if="user.admin" class="block px-6 py-2" :href="route('admin.dashboard')">{{ __('Administration') }}</Link>
	    <div class="border-t"/>
	    <Link class="block px-6 py-2" href="/logout" method="delete" as="button">{{ __('Logout') }}</Link>
	  </template>
	</dropdown>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Dropdown from '@/Shared/Dropdown'

export default {
  components: {
    Dropdown,
    Icon,
    Link,
  },
  data() {
    return {
    	user: this.$page.props.auth.user,
    }
  },
}
</script>