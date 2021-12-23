<template>
  <div class="flex flex-wrap">

  	<div v-for="room in rooms" :key="room.id" class="h-32 w-32 mr-4 p-8 flex justify-center items-center bg-gray-100 rounded-lg shadow">
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
  }

}
</script>
