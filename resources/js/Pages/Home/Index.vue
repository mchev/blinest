<script setup>
import { Head, Link } from '@inertiajs/vue3'
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
</script>
<template>
  <Head>
    <title>{{ __('Free multiplayer music quizzes') }}</title>
    <meta head-key="description" name="description" content="Simple et efficace! Blind-tests multijoueurs, Années 2000, Disney, Chanson française, Années 80, etc." />
  </Head>
  <Layout>
    <h1 class="hidden">Blinest, {{ __('Free multiplayer music quizzes') }}</h1>
    <section v-if="search_result">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-4 lg:grid-cols-6">
        <Room v-for="room in search_result" :room="room" :key="room.id" />
      </div>
    </section>
    <div v-else>
      <div class="flex flex-wrap lg:flex-nowrap">
        <section v-if="categories.length" class="w-full lg:w-8/12">
          <div v-for="category in categories" :key="category.id" class="mb-4">
            <h3 class="text-center text-2xl font-bold uppercase text-shark-200">{{ __(category.name) }}</h3>
            <Rooms :rooms="category.rooms" :id="category.id" />
          </div>
          <div class="relative mb-4">
            <h2 class="text-center text-2xl font-bold uppercase text-shark-200">{{ __('Private rooms') }}</h2>
            <rooms :rooms="private_rooms" id="privateRooms" />
          </div>
        </section>
        <aside class="order-last w-full lg:order-first lg:w-4/12 lg:pr-12">
          <article class="my-6 hidden xl:flex">
            <div class="flex w-1/2 flex-col gap-2 p-4">
              <h3 class="text-xl">Soutenir Blinest</h3>
              <p class="text-sm">Financez les serveurs</p>
              <a href="https://donate.stripe.com/00g2bvf8i08X8De6oo" target="_blank" rel="external nofollow" data-umami-event="Faire un don" class="flex items-center justify-center gap-2 bg-red-500 px-2 py-1 text-center uppercase text-white hover:bg-red-400">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor">
                  <title>Stripe</title>
                  <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z" />
                </svg>
                Faire un don
              </a>
            </div>
            <div class="flex w-1/2 flex-col gap-2 p-4">
              <h3 class="text-xl">Communauté</h3>
              <p class="text-sm">Blinest est sur Discord</p>
              <a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" rel="external nofollow" class="flex items-center justify-center gap-2 bg-shark-500 px-2 py-1 uppercase text-white hover:bg-shark-400" :title="__('Join the Blinest community on Discord')" data-umami-event="Discord button">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor">
                  <title>Discord</title>
                  <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z" />
                </svg>
                Rejoindre
              </a>
            </div>
          </article>
          <div v-for="featured_room in featured_rooms" :key="`featured-room-${featured_room.id}`">
            <hr class="my-4 rounded-2xl border border-red-500" />
            <FeaturedRoom :room="featured_room" class="my-12" />
          </div>
          <TopPlayers :list="weekly_top_users"/>
        </aside>
      </div>
      <section class="my-24 flex flex-wrap gap-4 bg-red-500 p-12 text-white">
        <!-- <h2 class="text-center text-2xl uppercase">Meilleurs scores de la semaine</h2> -->
        <Link href="/rankings" class="rounded-2xl bg-white px-4 py-2 text-xl text-red-500">
          {{ __('Voir les classements') }}
        </Link>
        <Link href="/rooms" class="rounded-2xl bg-white px-4 py-2 text-xl text-red-500">
          {{ __('Gérer mes rooms') }}
        </Link>
        <Link href="/me" class="rounded-2xl bg-white px-4 py-2 text-xl text-red-500">
          {{ __('Mon compte') }}
        </Link>
      </section>
      <section v-if="user_rooms && user_rooms.length">
        <div class="relative">
          <h2 class="mb-1 text-xl font-medium text-neutral-100 lg:text-xl">{{ __('My rooms') }}</h2>
          <rooms :rooms="user_rooms" id="userRooms" />
        </div>
      </section>
    </div>
  </Layout>
</template>
