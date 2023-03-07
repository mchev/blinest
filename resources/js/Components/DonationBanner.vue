<script setup>
import { ref } from 'vue'

const show = ref(true)
</script>
<template>
  <div class="my-4 flex items-center rounded bg-neutral-700 p-2 text-sm">
    <div class="flex gap-4 items-center">
      <a target="_blank" class="btn-primary btn-sm gap-2" rel="external nofollow" href="https://donate.stripe.com/00g2bvf8i08X8De6oo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
          <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
        </svg>
        {{ __('Donate') }}
      </a>
      <p class="flex-grow">
        {{ __('Blinest is ') }}<span class="font-bold">{{ __('free and ad-free') }}</span>{{ __('. To keep it going, it needs your support.') }}<br><span class="italic"> {{ __('If everyone gave 1€, it would be enough to cover the servers and maintenance costs for the year.":"Si chacune et chacun donnait 1€, cela suffirait à payer les serveurs et la maintenance pour l'année.') }}</span>
      </p>
    </div>
  </div>
</template>