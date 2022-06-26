<script setup>
import { ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import Card from '@/Components/Card.vue'

const props = defineProps({
  users: Array,
  data: Object,
})

const me = usePage().props.value.auth.user
console.log(me)
const userList = ref(props?.users)

watch(
  () => props.users,
  (value) => {
    userList.value = value
  },
)
</script>
<template>
  <Card>
    <template #header>
      <h3 class="text-xl font-bold">Classement</h3>
    </template>

    <ul>
      <li v-for="user in userList" :key="user.id" class="border-b px-2 py-4" :class="{ 'bg-neutral-200': me.id === user.id }">
        {{ user.name }}
        <div class="flex items-center">
          <span v-if="data" v-for="answer in data.track.answers" class="mr-1 rounded bg-neutral-400 p-1 text-[10px] uppercase text-white">
            {{ __(answer) }}
          </span>
        </div>
      </li>
    </ul>
  </Card>
</template>
