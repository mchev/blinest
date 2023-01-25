<script setup>
import Card from '@/Components/Card.vue'
import SocialIcon from '@/Components/SocialIcon.vue'
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

      <a :href="route('auth.redirect', 'spotify')" class="btn-secondary my-2">
        <SocialIcon name="spotify" class="mr-2 h-6 w-6" />
        Spotify
      </a>

      <a :href="route('auth.redirect', 'facebook')" class="btn-secondary my-2">
        <SocialIcon name="facebook" class="mr-2 h-6 w-6" />
        Facebook
      </a>
    </div>
  </Card>
</template>
