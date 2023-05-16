<script setup>
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import Room from './Room.vue'
import { Navigation, A11y } from 'swiper'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'

const props = defineProps({
  id: String | Number,
  rooms: Object,
  is_top_5: Boolean,
})

const modules = [Navigation, A11y]
const maxSlides = props.rooms && props.rooms.length < 6 ? 5 : 6
const numbers = ['<svg id="rank-1" width="100%" height="100%" viewBox="-20 0 70 154" class="svg-icon svg-icon-rank-1 top-10-rank"><path stroke-linejoin="square" d="M35.377 152H72V2.538L2 19.362v30.341l33.377-8.459V152z"></path></svg>', '<svg id="rank-2" width="100%" height="100%" viewBox="0 0 80 154" class="svg-icon svg-icon-rank-2 top-10-rank"><path stroke-linejoin="square" d="M3.72 152H113v-30.174H50.484l4.355-3.55 29.453-24.012c5.088-4.124 9.748-8.459 13.983-13.004 4.16-4.464 7.481-9.339 9.972-14.629 2.449-5.203 3.678-11.113 3.678-17.749 0-9.428-2.294-17.627-6.875-24.645-4.597-7.042-10.941-12.494-19.07-16.376C77.803 3.957 68.496 2 58.036 2 47.591 2 38.37 4.023 30.347 8.06c-8.015 4.032-14.457 9.578-19.352 16.654-4.492 6.493-7.389 13.803-8.693 21.952h34.055c1.236-3.52 3.398-6.52 6.459-8.97 3.54-2.834 8.277-4.224 14.147-4.224 5.93 0 10.552 1.537 13.76 4.681 3.181 3.12 4.791 7.024 4.791 11.594 0 4.151-1.16 7.934-3.468 11.298-2.192 3.194-5.987 7.124-11.405 11.84L3.72 122.465V152z"></path></svg>', '<svg id="rank-3" width="100%" height="100%" viewBox="0 0 80 154" class="svg-icon svg-icon-rank-3 top-10-rank"><path stroke-linejoin="square" d="M3.809 41.577h33.243c1.3-2.702 3.545-4.947 6.674-6.72 3.554-2.015 7.83-3.01 12.798-3.01 5.555 0 10.14 1.11 13.723 3.376 3.839 2.427 5.782 6.283 5.782 11.315 0 4.553-1.853 8.395-5.473 11.38-3.547 2.926-8.18 4.37-13.821 4.37H41.44v28.366h16.77c5.572 0 10.275 1.227 14.068 3.711 4.02 2.633 6.071 6.581 6.071 11.616 0 5.705-1.943 9.975-5.853 12.562-3.658 2.42-8.292 3.61-13.863 3.61-5.205 0-9.82-.94-13.827-2.836-3.698-1.75-6.32-4.272-7.785-7.529H2.33c2.096 12.089 7.761 21.65 17.028 28.78C29.242 148.175 42.594 152 59.476 152c10.706 0 20.175-1.783 28.42-5.337 8.185-3.528 14.575-8.486 19.208-14.884 4.595-6.346 6.896-13.938 6.896-22.837 0-6.952-1.93-13.494-5.81-19.666-3.815-6.07-9.68-10.367-17.683-12.908l-5.46-1.735 5.353-2.04c6.659-2.538 11.667-6.338 15.083-11.412 3.431-5.096 5.142-10.806 5.142-17.181 0-8.471-2.262-15.778-6.787-21.985-4.574-6.275-10.7-11.17-18.408-14.696C77.683 3.775 69.109 2 59.687 2 44.084 2 31.515 5.816 21.91 13.415c-9 7.119-15.025 16.486-18.101 28.162z"></path></svg>', '<svg id="rank-4" width="100%" height="100%" viewBox="0 0 81 154" class="svg-icon svg-icon-rank-4 top-10-rank"><path stroke-linejoin="square" d="M72 152h35.333v-30.977H128V92.497h-20.667V2H69.89L2 92.712v28.311h70V152zM36.202 92.188l35.93-47.998v47.998h-35.93z"></path></svg>', '<svg id="rank-5" width="100%" height="100%" viewBox="0 0 81 154" class="svg-icon svg-icon-rank-5 top-10-rank"><path stroke-linejoin="square" d="M105.588 32.174V2H13.534l-8.3 88.357h32.463c2.145-2.362 4.866-4.254 8.143-5.675 3.585-1.554 7.543-2.328 11.859-2.328 6.247 0 11.418 1.745 15.418 5.255 4.061 3.564 6.104 8.37 6.104 14.265 0 6.041-2.044 10.89-6.121 14.387-3.999 3.43-9.162 5.132-15.401 5.132-4.299 0-8.17-.694-11.601-2.095-3.11-1.268-5.577-2.946-7.368-5.042H2.592c3.308 11.593 9.782 20.623 19.46 27.164C32.472 148.464 45.64 152 61.602 152c10.12 0 19.294-1.99 27.548-5.966 8.198-3.949 14.711-9.718 19.572-17.335 4.844-7.59 7.278-16.95 7.278-28.123 0-9.182-2.013-17.314-6.032-24.431-4.02-7.118-9.514-12.7-16.51-16.775-6.99-4.072-14.849-6.109-23.612-6.109-11.06 0-20.099 3.483-27.234 10.461l-3.892 3.806 3.273-35.354h63.595z"></path></svg>']
</script>
<template>
  <div v-if="rooms && rooms.length > 0 && !is_top_5">
    <swiper
      :modules="modules"
      :loop="props.rooms.length > 5 ? true : false"
      :loopedSlides="1"
      :slides-per-view="1"
      :space-between="16"
      :breakpoints="{
        640: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 4,
        },
        1024: {
          slidesPerView: maxSlides,
        },
      }"
      navigation
    >
      <swiper-slide v-for="room in rooms" :key="room.id">
        <Room :room="room" />
      </swiper-slide>
    </swiper>
  </div>
  <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:grid-cols-5">
    <div v-for="(room, index) in rooms" :key="'top-'+room.id" class="flex">
      <div class="w-1/4 bg-neutral-900 py-4 flex items-center">
        <div v-html="numbers[index]" class="h-24 w-24 fill-neutral-800"/>
      </div>
      <div class="w-3/4 h-52">
        <Room :room="room"/>
      </div>
    </div>
  </div>
</template>

<style>
.swiper-button-next {
  --swiper-navigation-color: white;
  @apply top-0 bottom-0 right-0 flex h-full items-center bg-gradient-to-r from-transparent to-neutral-900 px-8 -mr-4;
}

.swiper-button-prev {
  --swiper-navigation-color: white;
  @apply top-0 bottom-0 left-0 flex h-full items-center bg-gradient-to-l from-transparent to-neutral-900 px-8 -ml-4;
}

.swiper-button-disabled {
  display: none;
}
</style>
