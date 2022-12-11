<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Link } from '@inertiajs/inertia-vue3'
import Room from './Room.vue'
import Top from './Top.vue'
import SliderControls from '@/Components/SliderControls.vue'
import { Navigation, Lazy, A11y } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'

const props = defineProps({
  id: String | Number,
  rooms: Object,
})

const modules = [Navigation, Lazy, A11y]
const maxSlides = props.rooms.data && props.rooms.data.length < 6 ? props.rooms.data.length : 6
</script>
<template>
  <div v-if="rooms.data">
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
      <swiper-slide v-for="room in rooms.data" :key="room.id">
        <Room :room="room" />
      </swiper-slide>
    </swiper>
    <!--     <Room :room="room" v-for="room in rooms.data" :key="room.id" />
    <SliderControls :rooms="rooms"/> -->
  </div>
  <div v-else class="relative grid grid-cols-1 gap-8 md:grid-cols-5">
    <Top :room="room" v-for="(room, index) in rooms" :key="room.id" :index="index" />
  </div>
</template>

<style>
.swiper-button-next {
  --swiper-navigation-color: white;
}

.swiper-button-prev {
  --swiper-navigation-color: white;
}
</style>
