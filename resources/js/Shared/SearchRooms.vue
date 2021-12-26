<template>
  <div :class="$attrs.class">
    <text-input v-model="form.search" prependIcon="search" :placeholder="__('Search') + '...'" />
  </div>
</template>


<script>

  import TextInput from '@/Shared/TextInput'

  export default {

    inheritAttrs: false,

    components: {
      TextInput,
    },

    props: {
      filters: Object
    },

    data() {
      return {
        form: {
          search: '',
        },
      }
    },

    methods: {

    },

  }

</script>
