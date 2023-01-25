<script setup>
defineProps({
  list: Object,
})
</script>

<template>
  <div class="flex w-full justify-center gap-1 border-b border-neutral-600" v-if="list.length">
    <div class="flex flex-col justify-end" v-if="list[4]">
      <img class="mb-2 h-10 w-10 rounded-full" :src="list[4].user ? list[4].user.photo : list[4].team.photo" />
      <div class="h-10 rounded-t bg-purple-500 p-4 opacity-25">
        <span class="text-xl font-bold">5</span>
      </div>
    </div>
    <div class="flex flex-col items-center justify-end" v-if="list[2]">
      <img class="mb-2 h-10 w-10 rounded-full" :src="list[2].user ? list[2].user.photo : list[2].team.photo" />
      <div class="h-32 rounded-t bg-purple-500 p-4 opacity-60">
        <span class="text-xl font-bold">3</span>
      </div>
    </div>
    <div class="flex flex-col items-center justify-end" v-if="list[0]">
      <img class="mb-2 h-10 w-10 rounded-full" :src="list[0].user ? list[0].user.photo : list[0].team.photo" />
      <div class="h-40 rounded-t bg-purple-500 p-4">
        <span class="text-xl font-bold">1</span>
      </div>
    </div>
    <div class="flex flex-col items-center justify-end" v-if="list[1]">
      <img class="mb-2 h-10 w-10 rounded-full" :src="list[1].user ? list[1].user.photo : list[1].team.photo" />
      <div class="h-36 rounded-t bg-purple-500 p-4 opacity-80">
        <span class="text-xl font-bold">2</span>
      </div>
    </div>
    <div class="flex flex-col items-center justify-end" v-if="list[3]">
      <img class="mb-2 h-10 w-10 rounded-full" :src="list[3].user ? list[3].user.photo : list[3].team.photo" />
      <div class="h-20 rounded-t bg-purple-500 p-4 opacity-30">
        <span class="text-xl font-bold">4</span>
      </div>
    </div>
  </div>
</template>
