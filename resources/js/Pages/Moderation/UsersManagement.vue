<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Layout from './Layout.vue'
import UserModerationList from './partials/UserModerationList.vue'
import UserModerationDetail from './partials/UserModerationDetail.vue'
import UserModerationBanModal from './partials/UserModerationBanModal.vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  users: {
    type: Object,
    default: null,
  },
  user: {
    type: Object,
    default: null,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  canViewSensitiveData: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
})

const page = usePage()

const search = ref(props.filters?.search || '')
const showBanModal = ref(false)
const banTarget = ref(null)

const performSearch = debounce(() => {
  router.get(route('moderation.users.index'), { search: search.value, per_page: props.filters?.per_page || 20 }, { preserveState: true, preserveScroll: true, replace: true })
}, 300)

watch(search, () => {
  if (!props.user) {
    performSearch()
  }
})

const openBanModal = (user) => {
  banTarget.value = user
  showBanModal.value = true
}

const closeBanModal = () => {
  showBanModal.value = false
  banTarget.value = null
}
</script>

<template>
  <Layout :title="user ? __('Moderation user sheet') : __('Moderation users title')">
    <div class="space-y-6">
      <div v-if="page.props.flash?.success" class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
        {{ page.props.flash.success }}
      </div>

      <div v-if="errors.error" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
        {{ errors.error }}
      </div>

      <UserModerationDetail v-if="user" :user="user" :can-view-sensitive-data="canViewSensitiveData" @ban="openBanModal(user)" />

      <template v-else>
        <UserModerationList v-model:search="search" :users="users" :can-view-sensitive-data="canViewSensitiveData" @ban="openBanModal" />
      </template>

      <UserModerationBanModal v-if="banTarget" :show="showBanModal" :user="banTarget" @close="closeBanModal" />
    </div>
  </Layout>
</template>
