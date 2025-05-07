<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const currentYear = new Date().getFullYear()
// const user = usePage().props.auth?.user
</script>
<template>
  <footer class="bg-slate-900 py-12 mt-16">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Main Footer Content -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8">
        <!-- Brand Column -->
        <div class="col-span-1">
          <div class="flex items-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-red-500 mr-2">
              <path d="M8 5.25a.75.75 0 01.75.75v3.25H12a.75.75 0 010 1.5H8.75V14a.75.75 0 01-1.5 0V5.25A.75.75 0 018 5.25zm9.195-1.944a.75.75 0 01.451.331A23.296 23.296 0 0121 12c0 2.786-.987 5.292-2.554 6.913a.75.75 0 11-1.048-1.074A7.25 7.25 0 0019.75 12a7.25 7.25 0 00-2.352-5.339.75.75 0 01.451-1.355z" />
            </svg>
            <span class="text-xl font-bold text-white">Blinest</span>
          </div>
          <p class="text-gray-400 mb-4">{{ __('Free multiplayer music quizzes for everyone. Challenge friends and discover new music.') }}</p>
          <div class="flex space-x-4">
            <a href="https://discord.gg/blinest" target="_blank" rel="external nofollow" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">Discord</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286z" />
              </svg>
            </a>
            <a href="https://github.com/mchev/blinest" target="_blank" rel="external nofollow" class="text-gray-400 hover:text-white transition-colors">
              <span class="sr-only">GitHub</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
        
        <!-- Quick Links -->
        <div class="col-span-1">
          <h3 class="text-white font-semibold mb-4">{{ __('Quick Links') }}</h3>
          <ul class="space-y-2">
            <li>
              <Link href="/" class="text-gray-400 hover:text-white transition-colors">{{ __('Home') }}</Link>
            </li>
            <li>
              <Link href="/faq" class="text-gray-400 hover:text-white transition-colors">{{ __('FAQ') }}</Link>
            </li>
            <li>
              <Link href="/contact" class="text-gray-400 hover:text-white transition-colors">{{ __('Contact') }}</Link>
            </li>
            <li>
              <a target="_blank" rel="external nofollow" href="https://github.com/mchev/blinest/issues/new" class="text-gray-400 hover:text-white transition-colors">
                {{ __('Report a bug') }}
              </a>
            </li>
          </ul>
        </div>
        
        <!-- Legal -->
        <div class="col-span-1">
          <h3 class="text-white font-semibold mb-4">{{ __('Legal') }}</h3>
          <ul class="space-y-2">
            <li>
              <Link :href="route('pages.show', 'mentions-legales')" class="text-gray-400 hover:text-white transition-colors">
                {{ __('Terms and Conditions') }}
              </Link>
            </li>
            <li>
              <Link :href="route('pages.show', 'politique-de-confidentialite')" class="text-gray-400 hover:text-white transition-colors">
                {{ __('Privacy Policy') }}
              </Link>
            </li>
          </ul>
        </div>
        
        <!-- Support -->
        <div class="col-span-1">
          <h3 class="text-white font-semibold mb-4">{{ __('Support Blinest') }}</h3>
          <p class="text-gray-400 mb-4">{{ __('Help us keep the music playing!') }}</p>
          <a 
            target="_blank" 
            rel="external nofollow" 
            href="https://donate.stripe.com/00g2bvf8i08X8De6oo"
            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
            </svg>
            {{ __('Donate') }}
          </a>
        </div>
      </div>
      
      <!-- Bottom Bar -->
      <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center">
        <div class="text-gray-500 mb-4 md:mb-0">
          &copy; {{ currentYear }} Blinest. {{ __('All rights reserved.') }}
        </div>
        <div class="text-gray-500">
          {{ __('Made with') }} ❤️ {{ __('in France') }}
        </div>
      </div>
    </div>
  </footer>
</template>