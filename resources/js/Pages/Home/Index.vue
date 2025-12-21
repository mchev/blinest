<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
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
const showBanner = ref(true)

</script>
<template>
  <Head>
    <title>{{ __('Free multiplayer music quizzes') }} | Blinest - Quiz musicaux gratuits</title>
    <meta head-key="description" name="description" content="Jouez à des quiz musicaux multijoueurs gratuits en ligne ! Rejoignez des milliers de joueurs pour tester vos connaissances musicales. Blind-tests pour tous les goûts : Années 2000, Disney, Chanson française, Années 80, Rock, Pop, Rap, et bien plus encore." />
    <meta name="keywords" content="quiz musical, blind test, quiz musique, test musical, quiz multijoueur, jeu musical en ligne, quiz gratuit, blind test gratuit, quiz années 2000, quiz disney, quiz chanson française, quiz rock, quiz pop" />
    <link rel="canonical" href="https://blinest.com/" />
  </Head>
  <Layout>
    <h1 class="hidden">Blinest, {{ __('Free multiplayer music quizzes') }}</h1>

    <!-- New Features Banner -->
    <div v-if="showBanner" class="mb-8 rounded-2xl bg-gradient-to-r from-purple-600/20 via-blue-600/20 to-teal-600/20 border border-purple-500/30 p-6 shadow-lg">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex-1">
          <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-purple-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
            </svg>
            <h2 class="text-xl font-bold text-white">Nouveau : Systèmes de progression</h2>
          </div>
          <p class="text-neutral-300 text-sm mb-3">
            On a ajouté trois systèmes pour récompenser votre activité : les <strong>Scores</strong>, les <strong>Levels</strong> et l'<strong>ELO</strong>. 
            Pas toujours évident de s'y retrouver ? On t'explique tout ça ici.
          </p>
          <Link 
            :href="route('docs.index')" 
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-purple-500 hover:to-blue-500 hover:shadow-lg hover:shadow-purple-500/20"
          >
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>
        <button 
          @click="showBanner = false"
          class="flex-shrink-0 text-neutral-400 hover:text-white transition-colors"
          aria-label="Fermer"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
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
        <!-- Soutenez Blinest -->
        <div id="featured" class="mb-10 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 shadow-lg">
          <div class="p-6">
            <h3 class="mb-2 text-2xl font-bold text-white">{{ __('Support Blinest') }}</h3>
            <p class="mb-4 text-gray-300">{{ __('Help ensure the longevity and growth of Blinest. Your donation contributes to server costs and ongoing improvements.') }}</p>
            <div class="mb-4 flex flex-col gap-3">
              <a href="https://shop.blinest.com" target="_blank" rel="external nofollow" data-umami-event="Shop button sidebar" class="flex items-center justify-center gap-2 rounded-lg bg-green-600 px-6 py-3 text-center font-medium text-white transition-all duration-300 hover:bg-green-500 hover:shadow-lg hover:shadow-green-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                {{ __('Blinest Shop') }}
              </a>
              <a href="https://donate.stripe.com/00g2bvf8i08X8De6oo" target="_blank" rel="external nofollow" data-umami-event="Faire un don" class="flex items-center justify-center gap-2 rounded-lg bg-red-500 px-6 py-3 text-center font-medium text-white transition-all duration-300 hover:bg-red-400 hover:shadow-lg hover:shadow-red-500/20">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor">
                  <title>Stripe</title>
                  <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z" />
                </svg>
                {{ __('Donate') }}
              </a>
            </div>
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
