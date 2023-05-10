<script setup>
import { Link, usePage } from '@inertiajs/vue3'

// const user = usePage().props.auth?.user
</script>
<template>
  <div class="mt-8 border-t border-neutral-500 text-sm text-neutral-500 md:flex md:flex-shrink-0">
    <div class="flex w-full items-center justify-between py-2 md:flex-shrink-0">
      Blinest
      <ul class="flex flex-col items-center gap-4 md:flex-row">
        <li><Link href="/contact">{{ __('Contact') }}</Link></li>
        <li>
          <a target="_blank" rel="external nofollow" href="https://github.com/mchev/blinest/issues/new">{{ __('Report a bug') }}</a>
        </li>
        <li><Link :href="route('pages.show', 'mentions-legales')">{{ __('Terms and Conditions') }}</Link></li>
        <li><Link :href="route('pages.show', 'politique-de-confidentialite')">{{ __('Privacy Policy') }}</Link></li>
        <li><a target="_blank" class="font-bold" rel="external nofollow" href="https://donate.stripe.com/00g2bvf8i08X8De6oo">{{ __('Donate') }}</a></li>
        <li>
          <a href="https://tree-nation.com/fr/profil/impact/blinest#co2" target="_blank" style="position:relative;cursor:pointer;display:block;z-index:999;">
          <img src="https://tree-nation.com/images/tracking/label-co2-website-white-en.png" style="width:157px;height:auto;">
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>