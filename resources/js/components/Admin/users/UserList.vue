<template>

  <data-table
      :columns="columns"
      url="/admin/users/table">
  </data-table>

</template>

<script>

    export default {

      data() {
          return {
              columns: [
                  {
                      label: 'ID',
                      name: 'id',
                      filterable: true,
                  },
                  {
                      label: 'Name',
                      name: 'name',
                      filterable: true,
                  },
                  {
                      label: 'Email',
                      name: 'email',
                      filterable: true,
                  },
              ]
          }
      },

    }

</script>

<style scoped>

</style>