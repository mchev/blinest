<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import FeaturedRoom from './FeaturedRoom.vue'
import TopPlayers from './TopPlayers.vue'
import HomeSidebarSection from './HomeSidebarSection.vue'
import OuvragePromo from './OuvragePromo.vue'
import DonationGoalCard from '@/Components/Donations/DonationGoalCard.vue'

defineProps({
  user: {
    type: Object,
    default: null,
  },
  featuredRooms: {
    type: Array,
    default: () => [],
  },
  weeklyTopUsers: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <aside class="w-full shrink-0 lg:w-80 xl:w-96">
    <div class="retro-panel lg:sticky lg:top-6 lg:self-start">
      <div id="home-donation-goal" class="scroll-mt-24 border-b border-white/10 p-4">
        <DonationGoalCard />
      </div>

      <HomeSidebarSection v-for="featuredRoom in featuredRooms" :key="`featured-${featuredRoom.id}`" :kicker="__('Featured')">
        <FeaturedRoom :room="featuredRoom" />
      </HomeSidebarSection>

      <HomeSidebarSection v-if="weeklyTopUsers && weeklyTopUsers.length" :kicker="__('Ranking')" :title="__('Top 10 of the week')">
        <TopPlayers :list="weeklyTopUsers" embedded />
      </HomeSidebarSection>

      <HomeSidebarSection :kicker="__('Play')" :title="__('Quick Links')">
        <div class="grid grid-cols-2 gap-2">
          <Link v-if="user" :href="route('minigames.index')" class="sidebar-tile group">
            <span class="sidebar-tile__icon group-hover:scale-105" aria-hidden="true">
              <Icon name="play" class="h-5 w-5" />
            </span>
            <span class="text-xs font-semibold text-white">{{ __('Mini-games') }}</span>
          </Link>
          <a href="https://discord.com/invite/uKyVgcxcFa" target="_blank" rel="external nofollow" class="sidebar-tile group" :title="__('Join the Blinest community on Discord')" data-umami-event="Discord button">
            <span class="sidebar-tile__icon group-hover:scale-105" aria-hidden="true">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286z" />
              </svg>
            </span>
            <span class="text-xs font-semibold text-white">Discord</span>
          </a>
          <Link v-if="user && !user.is_guest" :href="route('rankings.index')" class="sidebar-tile group">
            <span class="sidebar-tile__icon group-hover:scale-105" aria-hidden="true">
              <Icon name="trophy" class="h-5 w-5" />
            </span>
            <span class="text-xs font-semibold text-white">{{ __('Rankings') }}</span>
          </Link>
          <a href="https://shop.blinest.com" target="_blank" rel="external nofollow" data-umami-event="Shop button sidebar" class="sidebar-tile group">
            <span class="sidebar-tile__icon group-hover:scale-105" aria-hidden="true">
              <Icon name="shopping-cart" class="h-5 w-5" />
            </span>
            <span class="text-xs font-semibold text-white">{{ __('Blinest Shop') }}</span>
          </a>
        </div>
      </HomeSidebarSection>

      <HomeSidebarSection :kicker="__('Partner')" :title="__('For artisans and self-employed')" :border="false">
        <OuvragePromo />
      </HomeSidebarSection>
    </div>
  </aside>
</template>
