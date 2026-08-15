<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import HomeSectionHeader from './HomeSectionHeader.vue'

const props = defineProps({
  minigames: {
    type: Array,
    default: () => [],
  },
})

const containerRef = ref(null)
const trackRef = ref(null)
const currentSlide = ref(0)
const itemsToShow = ref(1)
const containerWidth = ref(0)

const hasGames = computed(() => (props.minigames || []).length > 0)

const maxSlide = computed(() =>
  Math.max(0, (props.minigames || []).length - itemsToShow.value)
)

const slidesArray = computed(() =>
  Array.from({ length: maxSlide.value + 1 }, (_, i) => i)
)

const GAP_PX = 12

const slideWidthPx = computed(() => {
  if (itemsToShow.value <= 0 || containerWidth.value <= 0) return 0
  return (containerWidth.value - (itemsToShow.value - 1) * GAP_PX) / itemsToShow.value
})

const scrollStepPx = computed(() => slideWidthPx.value + GAP_PX)

function updateItemsToShow() {
  if (typeof window === 'undefined') return
  const w = window.innerWidth
  if (w >= 1280) itemsToShow.value = 5
  else if (w >= 1024) itemsToShow.value = 4
  else if (w >= 768) itemsToShow.value = 3
  else itemsToShow.value = 1
}

function scrollToSlide(index) {
  const track = trackRef.value
  if (!track || slideWidthPx.value <= 0) return
  const target = Math.max(0, Math.min(index, maxSlide.value))
  currentSlide.value = target
  track.scrollTo({ left: target * scrollStepPx.value, behavior: 'smooth' })
}

function onTrackScroll() {
  const track = trackRef.value
  if (!track || scrollStepPx.value <= 0) return
  const index = Math.round(track.scrollLeft / scrollStepPx.value)
  const clamped = Math.max(0, Math.min(index, maxSlide.value))
  if (clamped !== currentSlide.value) currentSlide.value = clamped
}

const goNext = () => scrollToSlide(currentSlide.value + 1)
const goPrev = () => scrollToSlide(currentSlide.value - 1)

let resizeObserver = null
let scrollTimeout = null

function handleScroll() {
  if (scrollTimeout) clearTimeout(scrollTimeout)
  scrollTimeout = setTimeout(onTrackScroll, 50)
}

onMounted(() => {
  updateItemsToShow()
  window.addEventListener('resize', updateItemsToShow)
  nextTick(() => {
    if (containerRef.value) {
      resizeObserver = new ResizeObserver((entries) => {
        const entry = entries[0]
        if (entry) containerWidth.value = entry.contentRect.width
      })
      resizeObserver.observe(containerRef.value)
      containerWidth.value = containerRef.value.getBoundingClientRect().width
    }
    const track = trackRef.value
    if (track) track.addEventListener('scroll', handleScroll)
  })
})

onUnmounted(() => {
  window.removeEventListener('resize', updateItemsToShow)
  if (resizeObserver && containerRef.value) {
    resizeObserver.unobserve(containerRef.value)
  }
  const track = trackRef.value
  if (track) track.removeEventListener('scroll', handleScroll)
  if (scrollTimeout) clearTimeout(scrollTimeout)
})
</script>

<template>
  <div v-if="hasGames" class="relative w-full max-w-full">
    <HomeSectionHeader :title="__('Mini-games')" accent="teal" :kicker="__('Solo')">
      <template #action>
        <Link
          :href="route('minigames.index')"
          class="game-link-action"
        >
          {{ __('View all') }}
          <Icon name="cheveron-right" class="inline-block h-4 w-4" />
        </Link>
      </template>
    </HomeSectionHeader>
    <div ref="containerRef" class="w-full max-w-full">
      <div
        ref="trackRef"
        class="carousel-track flex overflow-x-auto overflow-y-hidden gap-3 pb-2 scroll-smooth"
        style="scroll-snap-type: x mandatory;"
        role="region"
        :aria-label="__('Mini-games carousel')"
      >
        <div
          v-for="game in minigames"
          :key="game.type"
          class="flex-shrink-0 p-3"
          :style="{
            width: slideWidthPx > 0 ? `${slideWidthPx}px` : '100%',
            scrollSnapAlign: itemsToShow === 1 ? 'start' : 'center',
          }"
        >
          <Link
            :href="game.play_url"
            class="game-card group flex h-full min-h-[220px] flex-col"
          >
            <div class="relative flex flex-1 flex-col gap-4 bg-gradient-to-b from-brand-deep to-brand-midnight p-5">
              <span class="game-card__poster-grid absolute inset-0 opacity-25" aria-hidden="true" />
              <div
                class="relative z-10 mx-auto flex h-16 w-16 items-center justify-center bg-brand-midnight text-white ring-1 ring-white/15 transition group-hover:scale-110"
                style="clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px))"
                aria-hidden="true"
              >
                <Icon name="play" class="h-7 w-7" />
              </div>
              <div class="relative z-10 min-w-0 flex-1 text-center">
                <h3 class="retro-title retro-title--accent text-sm">
                  {{ game.name }}
                </h3>
                <p class="mt-1 line-clamp-2 text-xs text-white/70">
                  {{ game.description }}
                </p>
                <p
                  v-if="game.score !== undefined && game.score > 0"
                  class="retro-badge mt-2"
                >
                  {{ game.score }} {{ __('points') }}
                </p>
              </div>
              <span class="relative z-10 game-btn-play-join mt-auto text-xs">
                {{ __('Play') }}
              </span>
            </div>
          </Link>
        </div>
      </div>
    </div>
    <aside class="flex w-full items-center justify-between px-2 py-2">
      <div class="hidden items-center gap-3 lg:flex">
        <button
          v-for="idx in slidesArray"
          :key="`minigames-pagination-${idx}`"
          type="button"
          class="h-4 rounded-full transition-all"
          :class="currentSlide === idx ? 'w-12 bg-brand-accent' : 'w-4 bg-white/20'"
          :aria-label="`${__('Go to slide')} ${idx + 1}`"
          @click="scrollToSlide(idx)"
        />
      </div>
      <div class="flex items-center gap-3">
        <button
          type="button"
          class="group"
          aria-label="Previous slide"
          :disabled="currentSlide <= 0"
          :class="{ 'opacity-50 pointer-events-none': currentSlide <= 0 }"
          @click="goPrev"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10">
            <circle class="transition group-hover:fill-brand-accent group-hover:stroke-brand-accent" cx="12" cy="12" r="10" />
            <path d="M16 12H8" />
            <path d="m12 8-4 4 4 4" />
          </svg>
        </button>
        <button
          type="button"
          class="group"
          aria-label="Next slide"
          :disabled="currentSlide >= maxSlide"
          :class="{ 'opacity-50 pointer-events-none': currentSlide >= maxSlide }"
          @click="goNext"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10">
            <circle class="transition group-hover:fill-brand-accent group-hover:stroke-brand-accent" cx="12" cy="12" r="10" />
            <path d="M8 12h8" />
            <path d="m12 16 4-4-4-4" />
          </svg>
        </button>
      </div>
    </aside>
  </div>
</template>

<style scoped>
.carousel-track {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.carousel-track::-webkit-scrollbar {
  display: none;
}
</style>
