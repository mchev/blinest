<script setup>
import { Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'

defineProps({
  room: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Modal :show="true" :max-width="'md'" :closeable="false">
    <div class="guest-join-modal">
      <header class="guest-join-modal__header">
        <h2 class="guest-join-modal__title">{{ room.name }}</h2>
        <p class="guest-join-modal__subtitle">
          {{ __('Join the game and test your music knowledge!') }}
        </p>
      </header>

      <div class="guest-join-modal__actions">
        <a
          :href="route('rooms.guest-join', room.slug)"
          class="game-btn-play-join guest-join-modal__btn"
        >
          {{ __('Play as guest') }}
        </a>

        <div class="guest-join-modal__divider" aria-hidden="true">
          <span>{{ __('or') }}</span>
        </div>

        <Link
          :href="route('login')"
          class="game-btn-play-live guest-join-modal__btn"
        >
          {{ __('Login with my account') }}
        </Link>

        <Link
          :href="route('register')"
          class="game-btn-play-neutral guest-join-modal__btn"
        >
          {{ __('Create an account') }}
        </Link>
      </div>
    </div>
  </Modal>
</template>
