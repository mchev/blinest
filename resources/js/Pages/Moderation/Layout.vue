<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  title: String,
})

const isSidebarOpen = ref(false)

const navigation = [
  { 
    name: 'Tableau de bord', 
    href: route('moderation.dashboard'),
    routeName: 'moderation.dashboard',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
    </svg>`,
    description: 'Vue d\'ensemble des statistiques de modération et des activités récentes'
  },
  { 
    name: 'Messages supprimés', 
    href: route('moderation.trashed-messages.index'),
    routeName: 'moderation.trashed-messages.index',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
    </svg>`,
    description: 'Gérer et examiner les messages supprimés'
  },
  { 
    name: 'Utilisateurs bannis', 
    href: route('moderation.banned-users.index'),
    routeName: 'moderation.banned-users.index',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
    </svg>`,
    description: 'Voir et gérer les utilisateurs bannis'
  },
  { 
    name: 'Gestion des utilisateurs', 
    href: route('moderation.users.index'),
    routeName: 'moderation.users.index',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
    </svg>`,
    description: 'Gérer les comptes utilisateurs et les permissions'
  },
  { 
    name: 'Modérateurs', 
    href: route('moderation.moderators.index'),
    routeName: 'moderation.moderators.index',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
    </svg>`,
    description: 'Gérer les modérateurs de salle'
  },
  {
    name: 'Gestionnaire de pistes',
    href: route('moderation.tracks.index'),
    routeName: 'moderation.tracks.index',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-2v13" /><circle cx="6" cy="18" r="3" /><circle cx="18" cy="16" r="3" /></svg>`,
    description: 'Gérer les pistes locales',
  },
]

const page = usePage()

const currentSection = computed(() => {
  return navigation.find(item => route().current(item.routeName))
})

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
<div class="min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col">
      <div class="flex h-full">
        <button
          @click="toggleSidebar"
          class="retro-nav-btn--primary fixed bottom-4 right-4 z-50 p-3 lg:hidden"
        >
          <svg v-if="!isSidebarOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Sidebar -->
        <div
          :class="[
            'fixed inset-y-0 left-0 z-40 w-72 transform border-r border-white/10 bg-brand-deep backdrop-blur-sm transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
          ]"
        >
          <div class="flex h-full flex-col">
            <div class="border-b border-white/10 p-4">
              <div class="flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-bold uppercase tracking-[0.08em] text-brand-primary">Modération</h2>
                  <p class="text-sm text-white/60">Espace de gestion</p>
                </div>
                <Link
                  :href="route('home')"
                  class="retro-nav-btn text-xs"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                  </svg>
                  Retour
                </Link>
              </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 p-4">
              <Link
                v-for="item in navigation"
                :key="item.name"
                :href="item.href"
                class="group relative flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200 ease-in-out"
                :class="[
                  route().current(item.routeName)
                    ? 'border border-brand-accent/30 bg-brand-accent/10 font-bold text-brand-accent'
                    : 'text-white/60 hover:bg-brand-midnight hover:text-white'
                ]"
              >
                <div class="relative z-10 flex items-center w-full">
                  <span v-html="item.icon" class="mr-3 transition-transform duration-200 group-hover:scale-110" />
                  <span class="flex-1">{{ item.name }}</span>
                  <span
                    v-if="route().current(item.routeName)"
                    class="ml-2 h-2 w-2 bg-brand-accent"
                  ></span>
                </div>
              </Link>
            </nav>
          </div>
        </div>

        <div class="flex-1 space-y-6 p-6">
          <div class="retro-panel p-6">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="retro-page-title">{{ title }}</h1>
              </div>
              <div class="flex items-center gap-2">
                <span class="border border-brand-accent/30 bg-brand-accent/10 px-3 py-1 text-xs font-medium uppercase tracking-wider text-brand-accent">
                  Modération
                </span>
              </div>
            </div>
          </div>

          <Card class="overflow-hidden">
            <div class="p-6">
              <slot />
            </div>
          </Card>
        </div>
      </div>
      <footer class="surface-footer py-6 px-8 text-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="font-bold text-lg text-brand-primary">↳ Blinest</span>
            <span class="hidden sm:inline text-white/70">Quiz musicaux multijoueurs gratuits. Défiez vos amis et découvrez de nouvelles musiques.</span>
          </div>
          <div class="flex items-center gap-4">
            <a href="https://github.com/blinest" target="_blank" rel="noopener" class="text-white/60 hover:text-brand-accent transition-colors duration-200">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.484 2 12.021c0 4.428 2.865 8.184 6.839 9.504.5.092.682-.217.682-.482 0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.342-3.369-1.342-.454-1.157-1.11-1.465-1.11-1.465-.908-.62.069-.608.069-.608 1.004.07 1.532 1.032 1.532 1.032.892 1.53 2.341 1.088 2.91.832.091-.647.35-1.088.636-1.339-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.295 2.748-1.025 2.748-1.025.546 1.378.202 2.397.1 2.65.64.7 1.028 1.595 1.028 2.688 0 3.847-2.338 4.695-4.566 4.944.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.749 0 .267.18.577.688.479C19.138 20.2 22 16.447 22 12.021 22 6.484 17.523 2 12 2z"/></svg>
            </a>
          </div>
        </div>
        <div class="mt-4 text-xs text-white/40 text-center sm:text-left">© 2025 Blinest. Tous droits réservés.</div>
      </footer>
    </div>
  </div>
</template> 