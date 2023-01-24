<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import Room from './Room.vue'
import Top from './Top.vue'
import { Navigation, Lazy, A11y } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'

const props = defineProps({
  id: String | Number,
  rooms: Object,
  is_top_5: Boolean,
})

const modules = [Navigation, Lazy, A11y]
const maxSlides = props.rooms && props.rooms.length < 6 ? props.rooms.length : 6
</script>
<template>
  <div v-if="rooms && !is_top_5">
    <swiper
      :modules="modules"
      :slides-per-view="1"
      :space-between="10"
      :breakpoints="{
        640: {
          slidesPerView: 2,
          spaceBetween: 10,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: maxSlides,
          spaceBetween: 30,
        },
      }"
      navigation
    >
      <swiper-slide v-for="room in rooms" :key="room.id">
        <Room :room="room" />
      </swiper-slide>
    </swiper>
  </div>
  <div v-else class="relative grid grid-cols-1 gap-8 md:grid-cols-5">
    <Top :room="room" v-for="(room, index) in rooms" :key="room.id" :index="index" />
  </div>
</template>

<style>
.swiper-button-next {
  --swiper-navigation-color: white;
  @apply flex bg-gradient-to-r from-transparent to-neutral-900 h-full top-0 right-0 items-center px-8
}

.swiper-button-prev {
  --swiper-navigation-color: white;
  @apply flex bg-gradient-to-l from-transparent to-neutral-900 h-full top-0 left-0 items-center px-8
}

.swiper-button-disabled{
    display:none;   
}
</style>
