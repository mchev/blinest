<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Moderation from './Moderation.vue'

const props = defineProps({
  room: Object,
  message: Object,
})

const moderate = ref(false)
const user = usePage().props.value.auth.user
const isModerator = props.room.moderators.find(x => x.id === user.id)

</script>
<template>
  <div class="my-1 text-sm">
    <!--     <time class="mr-1">{{ message.time }}</time>
 -->
    <button v-if="isModerator" @click="moderate = true" class="font-bold mr-1" :class="{'text-red-500': room.moderators.find(x => x.id === message.user.id)}">
      {{ message.user.name }}
    </button>
    <span v-else class="font-bold mr-1" :class="{'text-red-500': room.moderators.find(x => x.id === message.user.id)}">
      {{ message.user.name }}
    </span>
    <span class="">: {{ message.body }}</span>
    <Moderation v-if="moderate" :message="message"/>
  </div>
</template>
