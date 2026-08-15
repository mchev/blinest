<script setup>
import { Head, usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Room from './partials/Room.vue'
import Rooms from './partials/Rooms.vue'
import MinigamesSlider from './partials/MinigamesSlider.vue'
import HomeSectionHeader from './partials/HomeSectionHeader.vue'
import HomeSidebar from './partials/HomeSidebar.vue'
import PublicRoomsGrid from './partials/PublicRoomsGrid.vue'

defineProps({
  filters: Object,
  public_categories: Array,
  public_rooms: Array,
  minigames: Array,
  featured_rooms: Object,
  private_rooms: Object,
  user_rooms: Object,
  search_result: Object,
  weekly_top_users: Object,
})

const user = usePage().props.auth.user
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

    <!-- Search Results -->
    <section v-if="search_result" class="mb-12">
      <HomeSectionHeader :title="__('Search Results')">
        <template #action>
          <button
            type="button"
            class="game-btn-secondary"
            @click="$inertia.get('/')"
          >
            {{ __('Clear Search') }}
          </button>
        </template>
      </HomeSectionHeader>
      <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
        <Room v-for="room in search_result" :key="room.id" :room="room" variant="catalog" />
      </div>
    </section>

    <div v-else class="flex flex-col gap-8">
      <div class="lg:hidden">
        <div class="flex items-center gap-4 rounded-xl border border-zinc-700/60 bg-arena-panel p-4 ring-1 ring-zinc-800">
          <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-500/20 ring-1 ring-red-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 text-red-400" aria-hidden="true">
              <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
            </svg>
          </div>
          <p class="text-sm font-bold leading-snug text-zinc-100">
            {{ __('Pick a room, listen to the excerpt, be the fastest to answer.') }}
          </p>
        </div>
      </div>

      <div class="flex flex-col gap-8 lg:flex-row lg:gap-8 xl:gap-10">
      <!-- Main Content -->
      <section class="min-w-0 flex-1 space-y-8">
        <PublicRoomsGrid
          v-if="public_rooms && public_rooms.length"
          :rooms="public_rooms"
          :categories="public_categories"
        />

        <div v-if="minigames && minigames.length">
          <MinigamesSlider :minigames="minigames" />
        </div>

        <div>
          <HomeSectionHeader
            :title="__('Private rooms')"
            :subtitle="__('Community rooms created by players')"
          />
          <Rooms :rooms="private_rooms" id="privateRooms" layout="grid" :limit="12" />
        </div>

        <div v-if="user">
          <HomeSectionHeader :title="__('My rooms')" />
          <Rooms :rooms="user_rooms" id="userRooms" layout="grid" :limit="8" />
        </div>
      </section>

      <!-- Sidebar -->
      <HomeSidebar
        :user="user"
        :featured-rooms="featured_rooms"
        :weekly-top-users="weekly_top_users"
      />
      </div>
    </div>
  </Layout>
</template>
