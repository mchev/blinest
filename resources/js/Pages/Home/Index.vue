<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/AppLayout.vue'
import Room from './partials/Room.vue'
import HomeSectionHeader from './partials/HomeSectionHeader.vue'
import HomeSidebar from './partials/HomeSidebar.vue'
import PublicRoomsGrid from './partials/PublicRoomsGrid.vue'

defineProps({
  filters: Object,
  catalog: String,
  catalog_items: Object,
  catalog_category_id: [Number, String, null],
  public_categories: Array,
  community_categories: Array,
  homepage_hidden_category_ids: Array,
  featured_rooms: Object,
  search_result: Object,
  weekly_top_users: Object,
})

const user = usePage().props.auth.user
</script>

<template>
  <Layout>
    <h1 class="sr-only">Blinest, {{ __('Free multiplayer music quizzes') }}</h1>

    <!-- Search Results -->
    <section v-if="search_result" class="mb-12">
      <h2 class="sr-only">{{ __('Search Results') }}</h2>
      <HomeSectionHeader :title="__('Search Results')">
        <template #action>
          <button type="button" class="game-btn-secondary" @click="router.visit(route('home'))">
            {{ __('Clear Search') }}
          </button>
        </template>
      </HomeSectionHeader>
      <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
        <Room v-for="room in search_result" :key="room.id" :room="room" variant="catalog" />
      </div>
    </section>

    <div v-else class="flex flex-col gap-4 lg:gap-8">
      <div class="lg:hidden">
        <div class="retro-hero flex items-center gap-4">
          <div class="retro-hero__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6" aria-hidden="true">
              <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
            </svg>
          </div>
          <p class="text-sm font-bold uppercase leading-snug tracking-wide text-white">
            {{ __('Pick a room, listen to the excerpt, be the fastest to answer.') }}
          </p>
        </div>
      </div>

      <div class="flex flex-col gap-8 lg:flex-row lg:gap-8 xl:gap-10">
        <!-- Main Content -->
        <section class="min-w-0 flex-1 space-y-8">
          <PublicRoomsGrid v-if="catalog_items" :catalog="catalog" :catalog-items="catalog_items" :catalog-category-id="catalog_category_id" :categories="public_categories" :community-categories="community_categories" :hidden-category-ids="homepage_hidden_category_ids" />
        </section>

        <!-- Sidebar -->
        <HomeSidebar :user="user" :featured-rooms="featured_rooms" :weekly-top-users="weekly_top_users" />
      </div>
    </div>
  </Layout>
</template>
