<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import Room from './Room.vue'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
  rooms: {
    type: Array,
    default: () => [],
  },
  layout: {
    type: String,
    default: 'carousel',
    validator: (value) => ['carousel', 'grid'].includes(value),
  },
  limit: {
    type: Number,
    default: null,
  },
})

const showAll = ref(false)

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

const visibleRooms = computed(() => {
  if (props.layout !== 'grid' || props.limit === null || showAll.value) {
    return sortedRooms.value
  }

  return sortedRooms.value.slice(0, props.limit)
})

const hiddenRoomCount = computed(() => {
  if (props.layout !== 'grid' || props.limit === null || showAll.value) {
    return 0
  }

  return Math.max(0, sortedRooms.value.length - props.limit)
})

const maxSlide = computed(() =>
  Math.max(0, sortedRooms.value.length - itemsToShow.value)
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

/** Nombre d’éléments visibles selon la largeur réelle du carrousel (colonne principale). */
function updateItemsToShow(width = containerWidth.value) {
  if (width <= 0) {
    return
  }

  if (width >= 960) {
    itemsToShow.value = 4
  } else if (width >= 640) {
    itemsToShow.value = 3
  } else if (width >= 400) {
    itemsToShow.value = 2
  } else {
    itemsToShow.value = 1
  }

  currentSlide.value = Math.min(currentSlide.value, maxSlide.value)
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
  nextTick(() => {
    if (containerRef.value) {
      resizeObserver = new ResizeObserver((entries) => {
        const entry = entries[0]
        if (entry) {
          containerWidth.value = entry.contentRect.width
          updateItemsToShow(entry.contentRect.width)
        }
      })
      resizeObserver.observe(containerRef.value)
      containerWidth.value = containerRef.value.getBoundingClientRect().width
      updateItemsToShow(containerWidth.value)
    }
    const track = trackRef.value
    if (track) {
      track.addEventListener('scroll', handleScroll)
    }
  })
})

onUnmounted(() => {
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
      v-if="layout === 'grid'"
      class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"
    >
      <div
        v-for="room in visibleRooms"
        :key="room.id"
        class="flex h-full flex-col p-1.5"
      >
        <Room :room="room" variant="catalog" />
      </div>
    </div>

    <div
      v-else
      ref="trackRef"
      class="carousel-track flex items-stretch overflow-x-auto overflow-y-hidden gap-3 pb-2 scroll-smooth"
      style="scroll-snap-type: x mandatory;"
      role="region"
      :aria-label="__('Rooms carousel')"
    >
      <div
        v-for="room in sortedRooms"
        :key="room.id"
        class="flex h-auto flex-shrink-0 flex-col p-1.5"
        :style="{
          width: slideWidthPx > 0 ? `${slideWidthPx}px` : '100%',
          scrollSnapAlign: itemsToShow === 1 ? 'start' : 'center',
        }"
      >
        <Room :room="room" variant="catalog" />
      </div>
    </div>

    <div v-if="hiddenRoomCount > 0" class="mt-4 flex justify-center">
      <button
        type="button"
        class="game-btn-secondary"
        @click="showAll = true"
      >
        {{ __('Show more rooms') }}
        <span class="text-white/50">(+{{ hiddenRoomCount }})</span>
      </button>
    </div>

    <aside
      v-if="layout === 'carousel'"
      class="mt-1 flex w-full items-center justify-between px-1 py-1"
    >
      <div class="hidden items-center gap-2 lg:flex">
        <button
          v-for="idx in slidesArray"
          :key="`${id}-pagination-${idx}`"
          type="button"
          class="h-1.5 rounded-full transition-all"
          :class="currentSlide === idx ? 'w-8 bg-red-500' : 'w-1.5 bg-white/20 hover:bg-white/40'"
          :aria-label="`${__('Go to slide')} ${idx + 1}`"
          @click="scrollToSlide(idx)"
        />
      </div>
      <div class="flex items-center gap-3">
        <button
          type="button"
          class="group rounded-full p-1 transition hover:bg-white/5"
          aria-label="Previous slide"
          :disabled="currentSlide <= 0"
          :class="{ 'opacity-50 pointer-events-none': currentSlide <= 0 }"
          @click="goPrev"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-neutral-400 transition group-hover:text-red-400">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        <button
          type="button"
          class="group rounded-full p-1 transition hover:bg-white/5"
          aria-label="Next slide"
          :disabled="currentSlide >= maxSlide"
          :class="{ 'opacity-40 pointer-events-none': currentSlide >= maxSlide }"
          @click="goNext"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-neutral-400 transition group-hover:text-red-400">
            <path d="M9 18l6-6-6-6" />
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
