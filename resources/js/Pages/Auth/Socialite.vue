<script setup>
import Card from '@/Components/Card.vue'
import SocialIcon from '@/Components/SocialIcon.vue'
</script>
<template>
      <Card class="px-4 w-auto">
        <div class="flex h-full flex-col justify-center">
          <small class="text-center mb-2">{{ __('Login with') }}</small>

          <a :href="route('auth.redirect', 'google')" class="btn-secondary my-2">
            <SocialIcon name="google" class="h-6 w-6 mr-2" />
            Google
          </a>

          <a :href="route('auth.redirect', 'discord')" class="btn-secondary my-2">
            <SocialIcon name="discord" class="h-6 w-6 mr-2" />
            <span class="inline">Discord</span>
          </a>

          <a :href="route('auth.redirect', 'deezer')" class="btn-secondary my-2">
            <SocialIcon name="deezer" class="h-6 w-6 mr-2" />
            Deezer
          </a>

          <a :href="route('auth.redirect', 'spotify')" class="btn-secondary my-2">
            <SocialIcon name="spotify" class="h-6 w-6 mr-2" />
            Spotify
          </a>

          <a :href="route('auth.redirect', 'facebook')" class="btn-secondary my-2">
            <SocialIcon name="facebook" class="h-6 w-6 mr-2" />
            Facebook
          </a>

        </div>
      </Card>
</template>
