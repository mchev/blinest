<template>
  <div class="flex flex-wrap">
    <div v-for="room in rooms" :key="room.id" class="mr-4 flex h-32 w-32 items-center justify-center rounded-lg bg-gray-100 p-8 shadow">
      {{ room.name }}
    </div>
  </div>
</template>

<script>
export default {
  components: {
    //
  },

  props: {
    rooms: Object,
  },
}
</script>
