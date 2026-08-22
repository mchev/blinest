<script setup></script>

<template>
  <div class="rounded-2xl border border-amber-500/20 bg-gradient-to-br from-slate-800 via-slate-900 to-amber-950/20 p-6">
    <h2 class="mb-2 text-2xl font-bold text-white">{{ __('Donation perks title') }}</h2>
    <p class="mb-2 text-sm leading-relaxed text-neutral-300">{{ __('Donation perks intro') }}</p>
    <p class="mb-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation perks identified note') }}</p>

    <div class="grid gap-3 sm:grid-cols-2">
      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm3 2h6v4H7V4zm8 8a1 1 0 10-2 0v1a1 1 0 102 0v-1zM7 9a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk ad free title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk ad free desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk crown title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk crown desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4 sm:col-span-2">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk profile title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk profile desc') }}</p>
      </div>
    </div>

    <p class="mt-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation perks community note') }}</p>
  </div>
</template>
