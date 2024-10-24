<script setup>
import { ref, computed, watchEffect } from 'vue'
import Room from './Room.vue'
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide } from 'vue3-carousel'

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  },
  rooms: {
    type: Array,
    default: () => []
  },
})

const carousel = ref(null)
const currentSlide = ref(0)

const next = () => carousel.value?.next()
const prev = () => carousel.value?.prev()

const settings = {
  itemsToShow: 1,
  itemsToScroll: 1,
  snapAlign: 'center',
}

const breakpoints = {
  768: {
    itemsToShow: 3,
  },
  1024: {
    itemsToShow: 4,
  },
  1280: {
    itemsToShow: 5,
  },
}

const safeMaxSlide = computed(() => 
  Math.min(carousel.value?.data.maxSlide.value ?? 0, Number.MAX_SAFE_INTEGER)
)

const safeMinSlide = computed(() => 
  Math.max(carousel.value?.data.minSlide.value ?? 0, 0)
)

const slidesArray = computed(() => {
  const max = safeMaxSlide.value
  const min = safeMinSlide.value
  return max >= min ? Array.from({ length: max - min + 1 }, (_, i) => i + min) : []
})

const sortedRooms = computed(() => 
  [...(props.rooms || [])].sort((a, b) => 
    b.subscriptions - a.subscriptions || b.is_playing - a.is_playing
  )
)

const hasRooms = computed(() => sortedRooms.value.length > 0)

watchEffect(() => {
  if (hasRooms.value && currentSlide.value > safeMaxSlide.value) {
    currentSlide.value = safeMaxSlide.value
  }
})
</script>

<template>
  <div v-if="hasRooms">
    <Carousel v-bind="settings" :breakpoints="breakpoints" ref="carousel" v-model="currentSlide" class="w-full max-w-full">
      <Slide v-for="room in sortedRooms" :key="room.id" class="p-3">
        <Room :room="room" />
      </Slide>
    </Carousel>
    <aside v-if="carousel" class="flex w-full items-center justify-between my-2 px-2">
      <div class="items-center gap-3 hidden lg:flex">
        <button 
          v-for="slideIndex in slidesArray" 
          :key="`${id}-pagination-${slideIndex}`" 
          @click="carousel.slideTo(slideIndex)" 
          class="h-4 rounded-full text-black transition-all" 
          :class="currentSlide === slideIndex ? 'w-12 bg-red-500' : 'w-4 bg-shark-100'"
          :aria-label="`Go to slide ${slideIndex + 1}`"
        ></button>
      </div>
      <div class="flex items-center gap-3">
        <button @click="prev" class="group" aria-label="Previous slide">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10">
            <circle class="transition group-hover:fill-red-500 group-hover:stroke-red-500" cx="12" cy="12" r="10" />
            <path d="M16 12H8" />
            <path d="m12 8-4 4 4 4" />
          </svg>
        </button>
        <button @click="next" class="group" aria-label="Next slide">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10">
            <circle class="transition group-hover:fill-red-500 group-hover:stroke-red-500" cx="12" cy="12" r="10" />
            <path d="M8 12h8" />
            <path d="m12 16 4-4-4-4" />
          </svg>
        </button>
      </div>
    </aside>
  </div>
  <div v-else class="text-center py-8">
    <p>{{ __('No rooms available at the moment.') }}</p>
  </div>
</template>
