<script setup>
import { ref, watch, onMounted } from 'vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  users: Array,
})

const userList = ref(props.users)

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
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold">Playlist</h3>
        0/15
      </div>
    </template>

    <ul>
      <li v-for="user in userList" :key="user.id">
        {{ user.name }}
      </li>
    </ul>
  </Card>
</template>
