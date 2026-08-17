<script setup>
import Layout from './Layout.vue'
import BannedUsers from './partials/BannedUsers.vue'

const props = defineProps({
  bannedUsers: Object,
  filters: Object,
})
</script>

<template>
  <Layout title="Utilisateurs bannis">
    <BannedUsers :bannedUsers="bannedUsers" :filters="filters" />
  </Layout>
</template> 