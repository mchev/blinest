<script setup>
import Modal from '@/Components/Modal.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['close'])
</script>

<template>
  <Modal :show="show" @close="emit('close')" maxWidth="2xl">
    <div class="p-6">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-white mb-2">
          {{ __('Facebook login is no longer available') }}
        </h2>
        <p class="text-neutral-400">
          {{ __('Don\'t worry! You can still access your account. Follow these simple steps:') }}
        </p>
      </div>

      <div class="space-y-6">
        <!-- Step 1 -->
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xl shadow-lg">
              1
            </div>
          </div>
          <div class="flex-1 pt-1">
            <h3 class="text-lg font-semibold text-white mb-2">
              {{ __('Find your email address') }}
            </h3>
            <p class="text-neutral-300 leading-relaxed mb-2">
              {{ __('When you created your account with Facebook, we saved your email address.') }}
            </p>
            <p class="text-white font-medium mb-2">
              {{ __('This is the email address you used on Facebook.') }}
            </p>
            <div class="mt-3 p-3 bg-neutral-700/50 rounded-lg border border-neutral-600">
              <p class="text-neutral-400 text-sm flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>{{ __('Tip: Check your Facebook account settings to find the email address you used.') }}</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-xl shadow-lg">
              2
            </div>
          </div>
          <div class="flex-1 pt-1">
            <h3 class="text-lg font-semibold text-white mb-2">
              {{ __('Reset your password') }}
            </h3>
            <p class="text-neutral-300 leading-relaxed mb-3">
              {{ __('Click the button below to reset your password.') }}
            </p>
            <p class="text-white font-medium mb-4">
              {{ __('We will send you an email with instructions.') }}
            </p>
            <Link 
              :href="route('password.request')" 
              class="btn-primary inline-flex items-center gap-2 hover:scale-105 transition-transform"
              @click="emit('close')"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
              {{ __('Reset my password') }}
            </Link>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="flex gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 text-white font-bold text-xl shadow-lg">
              3
            </div>
          </div>
          <div class="flex-1 pt-1">
            <h3 class="text-lg font-semibold text-white mb-2">
              {{ __('Log in with your email and new password') }}
            </h3>
            <p class="text-neutral-300 leading-relaxed mb-2">
              {{ __('Once you\'ve set your new password, come back here and log in normally with your email and password.') }}
            </p>
            <p class="text-green-300 font-medium flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              {{ __('All your data, scores, and progress will be exactly the same!') }}
            </p>
          </div>
        </div>
      </div>

      <div class="mt-8 p-4 bg-blue-900/20 border border-blue-700/50 rounded-lg">
        <p class="text-blue-200 text-sm">
          <strong class="text-blue-100">{{ __('Good news:') }}</strong>
          {{ __('You won\'t lose anything! Your account, scores, level, and all your progress are safe. You just need to set a password to log in.') }}
        </p>
      </div>

      <div class="mt-6 flex justify-end">
        <button 
          @click="emit('close')" 
          class="btn-secondary"
        >
          {{ __('Got it, thanks!') }}
        </button>
      </div>
    </div>
  </Modal>
</template>
