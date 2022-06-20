<template>
  <div>

    <Link class="block px-6 py-2" :href="route('users.edit', $page.props.auth.user)">{{ __('My Profile') }}</Link>
    <Link class="block px-6 py-2" :href="route('playlists')">{{ __('Playlists') }}</Link>
    <Link class="block px-6 py-2" :href="route('rooms')">{{ __('Rooms') }}</Link>
    <Link class="block px-6 py-2" href="/users">{{ __('Team') }}</Link>


    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/organizations">
        <icon name="office" class="mr-2 w-4 h-4" :class="isUrl('organizations') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('organizations') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Organizations</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/contacts">
        <icon name="users" class="mr-2 w-4 h-4" :class="isUrl('contacts') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('contacts') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Contacts</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <icon name="printer" class="mr-2 w-4 h-4" :class="isUrl('reports') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Reports</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
    Link,
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
