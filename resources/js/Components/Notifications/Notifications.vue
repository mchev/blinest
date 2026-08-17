<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Link, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import NewTeamRequest from './NewTeamRequest.vue'
import TeamRequestApproved from './TeamRequestApproved.vue'
import TeamRequestRejected from './TeamRequestRejected.vue'
import NewRoomAlert from './NewRoomAlert.vue'
import NewSuggestion from './NewSuggestion.vue'
import TrackDeleted from './TrackDeleted.vue'

const { placement } = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
})

const user = usePage().props.auth.user
const notifications = ref(user.notifications)
const popup = ref(null)

onMounted(() => {
  Echo.private('App.Models.User.' + user.id).notification((notification) => {
    popup.value = notification
    setTimeout(() => {
      popup.value = null
    }, 3000)
    notifications.value.unshift(...[notification])
  })
})

const markAsRead = (notification) => {
  hideItemBeforeRefresh(notification)
  axios.post(`/users/notifications/${notification.id}/read`).then(() => {
    hideItemBeforeRefresh(notification)
  })
}

const markAsDone = (notification) => {
  hideItemBeforeRefresh(notification)
  router.post(`/users/notifications/${notification.id}/done`, {
    preserveScroll: true,
  })
}

const hideItemBeforeRefresh = (notification) => {
  notifications.value = notifications.value.filter((x) => x.id !== notification.id)
}
</script>
<template>
  <div>
    <div class="absolute left-0 right-0 top-0 z-30 flex w-full justify-center" v-if="popup">
      <div class="retro-flash retro-flash--success my-2 max-w-2xl p-2">
        <NewRoomAlert v-if="popup.type === 'App\\Notifications\\NewRoomAlert'" :notification="popup" @markedAsdone="markAsDone(popup)" />
      </div>
    </div>
    <dropdown :placement="placement" :autoClose="false">
      <template #default>
        <div class="retro-icon-btn relative cursor-pointer" title="Notifications">
          <div v-if="notifications.length" class="absolute -right-1 -top-1 z-10 z-10 flex h-5 w-5 items-center justify-center truncate border-2 border-brand-midnight bg-brand-primary text-[10px] font-bold text-white shadow-lg">
            {{ notifications.length > 99 ? '99+' : notifications.length }}
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70 transition-colors duration-200 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            <path d="M18 8A6 6 0 0 0 6 8" />
            <circle cx="18" cy="8" r="1" fill="currentColor" class="text-brand-primary" />
          </svg>
        </div>
      </template>
      <template #dropdown>
        <div class="p-2 font-light">
          <ul v-if="notifications.length" class="max-h-96 max-w-xl overflow-y-scroll pr-2">
            <li v-for="notification in notifications" :key="notification.id" class="my-2 flex border border-white/10 bg-brand-midnight p-2">
              <NewTeamRequest v-if="notification.type === 'App\\Notifications\\NewTeamRequest'" :notification="notification" @handled="hideItemBeforeRefresh" />
              <TeamRequestApproved v-if="notification.type === 'App\\Notifications\\TeamRequestApproved'" :notification="notification" />
              <NewRoomAlert v-if="notification.type === 'App\\Notifications\\NewRoomAlert'" :notification="notification" @markedAsdone="markAsDone(notification)" />
              <NewSuggestion v-if="notification.type === 'App\\Notifications\\NewSuggestion'" :notification="notification" @markedAsdone="markAsDone(notification)" />
              <TrackDeleted v-if="notification.type === 'App\\Notifications\\TrackDeleted'" :notification="notification" @markedAsdone="markAsDone(notification)" />
              <div class="justify-end">
                <button @click="markAsRead(notification)" class="pl-4 text-white/50 hover:text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </li>
          </ul>
          <span v-else>{{ __('No notification') }}</span>
        </div>
      </template>
    </dropdown>
  </div>
</template>
