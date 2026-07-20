<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'
import Room from './Room.vue'
import EzoicAd from '@/Components/EzoicAd.vue'
import { EZOIC, scheduleEzoicSync } from '@/ezoic'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
  rooms: {
    type: Array,
    default: () => [],
  },
  showSponsoredSlide: {
    type: Boolean,
    default: false,
  },
})

const containerRef = ref(null)
const trackRef = ref(null)
const currentSlide = ref(0)
/** Nombre d’éléments visibles (1 mobile, 3/4/5 selon breakpoint) */
const itemsToShow = ref(1)
const containerWidth = ref(0)

const sortedRooms = computed(() =>
  [...(props.rooms || [])].sort(
    (a, b) => b.subscriptions - a.subscriptions || b.is_playing - a.is_playing
  )
)

const hasRooms = computed(() => sortedRooms.value.length > 0)

const hasSponsoredSlide = computed(() => props.showSponsoredSlide && hasRooms.value)

const carouselItemCount = computed(() =>
  sortedRooms.value.length + (hasSponsoredSlide.value ? 1 : 0)
)

const sponsoredInsertAfterIndex = computed(() => {
  if (! hasSponsoredSlide.value) {
    return -1
  }

  return Math.min(2, sortedRooms.value.length - 1)
})

const maxSlide = computed(() =>
  Math.max(0, carouselItemCount.value - itemsToShow.value)
)

const slidesArray = computed(() =>
  Array.from({ length: maxSlide.value + 1 }, (_, i) => i)
)

const GAP_PX = 12

const slideWidthPx = computed(() => {
  if (itemsToShow.value <= 0 || containerWidth.value <= 0) return 0
  return (containerWidth.value - (itemsToShow.value - 1) * GAP_PX) / itemsToShow.value
})

/** Pas de scroll pour une slide (largeur + gap) */
const scrollStepPx = computed(() => slideWidthPx.value + GAP_PX)

const slideStyle = computed(() => ({
  width: slideWidthPx.value > 0 ? `${slideWidthPx.value}px` : '100%',
  scrollSnapAlign: itemsToShow.value === 1 ? 'start' : 'center',
}))

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
    if (track) {
      track.addEventListener('scroll', handleScroll)
    }

    if (hasSponsoredSlide.value) {
      scheduleEzoicSync(window.location.pathname)
    }
  })
})

watch(hasSponsoredSlide, (enabled) => {
  if (enabled) {
    nextTick(() => scheduleEzoicSync(window.location.pathname))
  }
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
  <div v-if="hasRooms" ref="containerRef" class="w-full max-w-full">
    <div
      ref="trackRef"
      class="carousel-track flex overflow-x-auto overflow-y-hidden gap-3 pb-2 scroll-smooth"
      style="scroll-snap-type: x mandatory;"
      role="region"
      :aria-label="__('Rooms carousel')"
    >
      <template v-for="(room, index) in sortedRooms" :key="room.id">
        <div class="flex-shrink-0 p-3" :style="slideStyle">
          <Room :room="room" />
        </div>

        <div
          v-if="showSponsoredSlide && index === sponsoredInsertAfterIndex"
          :key="`${id}-sponsored`"
          class="flex-shrink-0 p-3"
          :style="slideStyle"
          role="complementary"
          :aria-label="__('Sponsored link')"
        >
          <div class="flex h-full min-h-[280px] flex-col overflow-hidden rounded-xl border border-slate-700/60 bg-gradient-to-br from-slate-800/90 to-slate-900 shadow-lg">
            <div class="border-b border-slate-700/50 px-3 py-2">
              <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">
                {{ __('Sponsored link') }}
              </span>
            </div>
            <div class="flex flex-1 items-center justify-center p-3">
              <EzoicAd
                :placement-id="EZOIC.midContent"
                wrapper-class="!border-0 !bg-transparent w-full min-h-[250px]"
              />
            </div>
          </div>
        </div>
      </template>
    </div>
    <aside class="flex w-full items-center justify-between px-2 py-2">
      <div class="hidden items-center gap-3 lg:flex">
        <button
          v-for="idx in slidesArray"
          :key="`${id}-pagination-${idx}`"
          type="button"
          class="h-4 rounded-full transition-all"
          :class="currentSlide === idx ? 'w-12 bg-red-500' : 'w-4 bg-shark-100'"
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
            <circle class="transition group-hover:fill-red-500 group-hover:stroke-red-500" cx="12" cy="12" r="10" />
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
            <circle class="transition group-hover:fill-red-500 group-hover:stroke-red-500" cx="12" cy="12" r="10" />
            <path d="M8 12h8" />
            <path d="m12 16 4-4-4-4" />
          </svg>
        </button>
      </div>
    </aside>
  </div>
  <div v-else class="py-8 text-center">
    <p>{{ __('No rooms available at the moment.') }}</p>
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
