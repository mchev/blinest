<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.dashboard')">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('dashboard') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" />
        <div :class="isUrl('dashboard') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.users')">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" :class="isUrl('users') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" viewBox="0 0 20 20">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
        <div :class="isUrl('users') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Users</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.teams')">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" :class="isUrl('teams') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" viewBox="0 0 20 20">
          <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
        </svg>
        <div :class="isUrl('teams') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Teams</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.playlists')">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" :class="isUrl('playlists') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" viewBox="0 0 20 20">
          <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
          <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('playlists') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Playlists</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.categories.index')">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" :class="isUrl('categories') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('categories') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Categories</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" :href="route('admin.rooms')">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" :class="isUrl('rooms') ? 'fill-white' : 'fill-blinest-400 group-hover:fill-white'" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('rooms') ? 'text-white' : 'text-blinest-300 group-hover:text-white'">Rooms</div>
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
      return urls.filter((url) => currentUrl.startsWith('admin/' + url)).length
    },
  },
}
</script>
