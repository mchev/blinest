<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Room from './partials/Room.vue'
import Rooms from './partials/Rooms.vue'
import TopPlayers from './partials/TopPlayers.vue'
import FeaturedRoom from './partials/FeaturedRoom.vue'

defineProps({
  filters: Object,
  categories: Object,
  featured_rooms: Object,
  private_rooms: Object,
  user_rooms: Object,
  search_result: Object,
  weekly_top_users: Object,
})

const user = usePage().props.auth.user;

</script>
<template>
  <Head>
    <title>{{ __('Free multiplayer music quizzes') }}</title>
    <meta head-key="description" name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
  </Head>
  <Layout>
    <h1 class="hidden">Blinest, {{ __('Free multiplayer music quizzes') }}</h1>
    
    <!-- Hero Section -->
    <div v-if="!search_result" class="relative mb-16 overflow-hidden rounded-3xl bg-gradient-to-br from-red-900 to-slate-900 shadow-2xl">
      <div class="absolute inset-0 bg-[url('/images/music-pattern.svg')] opacity-10"></div>
      <div class="relative z-10 px-6 py-16 sm:px-12 md:py-20 lg:flex lg:items-center lg:px-16">
        <div class="lg:w-3/5">
          <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
            <span class="block">{{ __('Discover Music') }}</span>
            <span class="block text-red-400">{{ __('Test Your Knowledge') }}</span>
          </h2>
          <p class="mt-6 max-w-lg text-xl text-gray-300">
            {{ __('Join thousands of music lovers in multiplayer quizzes across all genres. Challenge friends and climb the leaderboard!') }}
          </p>
          <div class="mt-10 flex flex-wrap gap-4">
            <a href="#featured" class="transform rounded-full bg-red-500 px-8 py-3 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-red-400 hover:shadow-red-500/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
              {{ __('Featured Rooms') }}
            </a>
            <a href="#categories" class="transform rounded-full bg-slate-700 px-8 py-3 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-slate-600 hover:shadow-slate-500/30 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
              {{ __('Browse Categories') }}
            </a>
          </div>
        </div>
        <div class="mt-12 hidden lg:mt-0 lg:block lg:w-2/5">
          <div class="relative">
            <div class="absolute -left-10 top-0 h-72 w-72 rounded-full bg-red-500 opacity-20 blur-3xl"></div>
            <div class="absolute -right-10 bottom-0 h-72 w-72 rounded-full bg-blue-500 opacity-20 blur-3xl"></div>
            <div class="relative">
              <svg viewBox="0 0 200 200" class="h-80 w-80 fill-white/10">
                <path d="M139.4 31.8c15.1 11.3 25.9 27.8 29.7 47.3 3.8 19.5 0.5 42.1-12.3 56.9-12.8 14.8-35.1 21.7-55.3 19.5-20.2-2.3-38.3-13.8-50.5-30.2-12.2-16.4-18.5-37.7-12.4-55.4 6.1-17.7 24.6-31.7 43.9-40.1 19.3-8.4 39.4-11.2 56.9-2z"></path>
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-32 w-32 text-white/80">
                  <path d="M8 5.25a.75.75 0 01.75.75v3.25H12a.75.75 0 010 1.5H8.75V14a.75.75 0 01-1.5 0V5.25A.75.75 0 018 5.25zm9.195-1.944a.75.75 0 01.451.331A23.296 23.296 0 0121 12c0 2.786-.987 5.292-2.554 6.913a.75.75 0 11-1.048-1.074A7.25 7.25 0 0019.75 12a7.25 7.25 0 00-2.352-5.339.75.75 0 01.451-1.355zm-2.652 3.195a.75.75 0 010 1.097A3.751 3.751 0 0016.75 12c0 1.093-.473 2.077-1.217 2.757a.75.75 0 01-1.098-1.02A2.25 2.25 0 0015.25 12c0-.605-.243-1.152-.638-1.552a.75.75 0 01.021-1.096z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Results -->
    <section v-if="search_result" class="mb-16">
      <div class="mb-8 flex items-center justify-between">
        <h2 class="text-3xl font-bold text-white">{{ __('Search Results') }}</h2>
        <button @click="$inertia.get('/')" class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-600">
          {{ __('Clear Search') }}
        </button>
      </div>
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
        <Room v-for="room in search_result" :room="room" :key="room.id" />
      </div>
    </section>

    <div v-else class="flex flex-col lg:flex-row lg:gap-12">
      <!-- Main Content -->
      <section v-if="categories.length" id="categories" class="w-full lg:w-3/4 space-y-8">
        <div v-for="category in categories" :key="category.id" class="relative">
          <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-white">{{ __(category.name) }}</h2>
          </div>
          <Rooms :rooms="category.rooms" :id="category.id" />
        </div>
        
        <div class="relative">
          <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-white">{{ __('Private rooms') }}</h2>
          </div>
          <Rooms :rooms="private_rooms" id="privateRooms" />
        </div>
        
        <div v-if="user" class="relative">
          <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold text-white">{{ __('My rooms') }}</h2>
          </div>
          <Rooms :rooms="user_rooms" id="userRooms" />
        </div>
      </section>

      <!-- Sidebar -->
      <aside class="mt-16 w-full lg:mt-0 lg:w-1/4">
        <!-- Support Blinest -->
        <div id="featured" class="mb-10 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 shadow-lg">
          <div class="p-6">
            <h3 class="mb-2 text-2xl font-bold text-white">{{ __('Support Blinest') }}</h3>
            <p class="mb-6 text-gray-300">{{ __('Help ensure the longevity and growth of Blinest. Your donation contributes to server costs and ongoing improvements.') }}</p>
            <a href="https://donate.stripe.com/00g2bvf8i08X8De6oo" target="_blank" rel="external nofollow" data-umami-event="Faire un don" class="flex items-center justify-center gap-2 rounded-lg bg-red-500 px-6 py-3 text-center font-medium text-white transition-all duration-300 hover:bg-red-400 hover:shadow-lg hover:shadow-red-500/20">
              <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor">
                <title>Stripe</title>
                <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z" />
              </svg>
              {{ __('Donate') }}
            </a>
          </div>
        </div>
        
        <!-- Mise en lumière -->
        <div class="mb-10 overflow-hidden rounded-2xl bg-gradient-to-br from-green-800 to-emerald-900 shadow-lg">
          <div class="p-6">
            <h3 class="mb-2 text-2xl font-bold text-white">{{ __('Spotlight') }}</h3>
            <p class="mb-6 text-gray-300">{{ __('Blinest supports this organic farming and farm inn project in Burgundy. Help Camille finalize her project to open a convivial inn where visitors can enjoy homemade dishes made from her own harvests.') }}</p>
            <a href="https://miimosa.com/projects/maraichage-bio-et-ferme-auberge-en-bourgogne-du-sud" target="_blank" rel="external nofollow" data-umami-event="Cagnotte MiiMOSA" class="flex items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 text-center font-medium text-white transition-all duration-300 hover:bg-green-500 hover:shadow-lg hover:shadow-green-500/20">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
              </svg>
              {{ __('Support this project') }}
            </a>
          </div>
        </div>
        
        <!-- Featured Rooms -->
        <div v-for="featured_room in featured_rooms" :key="`featured-room-${featured_room.id}`" class="mb-10">
          <div class="mb-4 flex items-center">
            <div class="h-1 flex-grow rounded-full bg-red-500"></div>
            <h3 class="mx-4 text-xl font-bold text-white">{{ __('Featured') }}</h3>
            <div class="h-1 flex-grow rounded-full bg-red-500"></div>
          </div>
          <FeaturedRoom :room="featured_room" />
        </div>
        
        <!-- Top Players -->
        <div class="mb-10">
          <TopPlayers :list="weekly_top_users"/>
        </div>
        
        <!-- Discord Community -->
        <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-900 to-slate-900 shadow-lg">
          <div class="p-6">
            <h3 class="mb-4 text-2xl font-bold text-white">{{ __('Join Our Community') }}</h3>
            <p class="mb-4 text-gray-300">{{ __('Connect with other music lovers on Discord:') }}</p>
            <ul class="mb-6 space-y-2 text-gray-300">
              <li class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-indigo-400">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                {{ __('Get help and support') }}
              </li>
              <li class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-indigo-400">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                {{ __('Chat with other players') }}
              </li>
              <li class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-indigo-400">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                {{ __('Stay updated on new tracks') }}
              </li>
              <li class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-indigo-400">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                </svg>
                {{ __('Help improve the site') }}
              </li>
            </ul>
            <a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" rel="external nofollow" class="flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition-all duration-300 hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/20" :title="__('Join the Blinest community on Discord')" data-umami-event="Discord button">
              <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor">
                <title>Discord</title>
                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z" />
              </svg>
              {{ __('Join Discord') }}
            </a>
          </div>
        </div>
      </aside>
    </div>
  </Layout>
</template>
