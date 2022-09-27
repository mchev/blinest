<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'

const props = defineProps({
  filters: Object,
})

const form = useForm({
  search: props?.filters?.search,
})

watch(
  form,
  throttle(() => {
    Inertia.get('/', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)
</script>
<template>
  <div :class="$attrs.class">
    <text-input class="text-sm" v-model="form.search" prepend-icon="search" :placeholder="__('Search a room') + '...'" />
  </div>
</template>
