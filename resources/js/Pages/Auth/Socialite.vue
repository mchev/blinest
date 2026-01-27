<script setup>
import { ref } from 'vue'
import Card from '@/Components/Card.vue'
import SocialIcon from '@/Components/SocialIcon.vue'
import FacebookRecoveryModal from './FacebookRecoveryModal.vue'

const showFacebookModal = ref(false)

const handleFacebookClick = (e) => {
  e.preventDefault()
  showFacebookModal.value = true
}
</script>
<template>
  <Card class="w-full px-4 lg:w-auto">
    <div class="flex h-full flex-col justify-center">
      <small class="mb-2 text-center">{{ __('Login with') }}</small>

      <a :href="route('auth.redirect', 'google')" class="btn-secondary my-2">
        <SocialIcon name="google" class="mr-2 h-6 w-6" />
        Google
      </a>

      <a :href="route('auth.redirect', 'discord')" class="btn-secondary my-2">
        <SocialIcon name="discord" class="mr-2 h-6 w-6" />
        <span class="inline">Discord</span>
      </a>

      <a :href="route('auth.redirect', 'deezer')" class="btn-secondary my-2">
        <SocialIcon name="deezer" class="mr-2 h-6 w-6" />
        Deezer
      </a>

<!--       <a :href="route('auth.redirect', 'spotify')" class="btn-secondary my-2">
        <SocialIcon name="spotify" class="mr-2 h-6 w-6" />
        Spotify
      </a>
 -->
      <button @click="handleFacebookClick" class="btn-secondary my-2 w-full relative">
        <div class="flex items-center">
          <SocialIcon name="facebook" class="mr-2 h-6 w-6" />
          <span>Facebook</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1 right-1 h-4 w-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <FacebookRecoveryModal :show="showFacebookModal" @close="showFacebookModal = false" />
  </Card>
</template>
