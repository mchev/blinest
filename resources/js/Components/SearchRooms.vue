<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Components/TextInput.vue'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'

defineProps({
  filters: Object,
})

const form = useForm({
  search: '',
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
    <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search') + '...'" />
  </div>
</template>
