<template>
  <div :class="$attrs.class">
    <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search') + '...'" />
  </div>
</template>

<script>
import TextInput from '@/Shared/TextInput'

export default {
  components: {
    TextInput,
  },
  inheritAttrs: false,

  props: {
    filters: Object,
  },

  data() {
    return {
      form: {
        search: '',
      },
    }
  },

  methods: {},
}
</script>
