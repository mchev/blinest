<template>
  <div class="ml-4">
    <dropdown class="mt-1 ml-auto" placement="bottom-end">
      <template #default>
        <div class="group flex cursor-pointer select-none items-center">
          <div class="mr-1 whitespace-nowrap">
            <span class="uppercase"><Icon :name="theme" class="mr-2 inline-block h-5 w-5" /></span>
          </div>
        </div>
      </template>
      <template #dropdown>
        <button type="button" class="flex cursor-pointer items-center py-1 px-2" @click="theme = 'dark'">
          <Icon name="dark" class="mr-2 inline-block h-6 w-6" />
          Dark
        </button>
        <button type="button" class="flex cursor-pointer items-center py-1 px-2" @click="theme = 'light'">
          <Icon name="light" class="mr-2 inline-block h-6 w-6" />
          Light
        </button>
        <button type="button" class="flex cursor-pointer items-center py-1 px-2" @click="theme = 'system'">
          <Icon name="system" class="mr-2 inline-block h-6 w-6" />
          System
        </button>
      </template>
    </dropdown>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Dropdown from '@/Shared/Dropdown'

export default {
  components: {
    Link,
    Icon,
    Dropdown,
  },

  data() {
    return {
      get theme() {
        return localStorage.getItem('theme') || 'system'
      },
      set theme(value) {
        localStorage.setItem('theme', value)
      },
    }
  },

  watch: {
    theme(newTheme, oldTheme) {
      this.checkTheme(newTheme)
    },
  },

  mounted() {
    this.checkTheme(this.theme)
  },

  methods: {
    checkTheme(theme) {
      switch (theme) {
      case 'dark':
        localStorage.theme = 'dark'
        document.documentElement.classList.add('dark')
        break
      case 'light':
        localStorage.theme = 'light'
        document.documentElement.classList.remove('dark')
        break
      case 'system':
        localStorage.removeItem('theme')
        break
      }
    },
  },
}
</script>
