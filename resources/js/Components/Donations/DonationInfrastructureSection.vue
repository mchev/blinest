<script setup></script>

<template>
  <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
    <h2 class="mb-2 text-2xl font-bold text-white">{{ __('Donation infrastructure title') }}</h2>
    <p class="mb-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation infrastructure intro') }}</p>

    <div class="mb-6 space-y-4 text-neutral-300">
      <p class="leading-relaxed">{{ __('Donation infrastructure rampocalypse') }}</p>
      <p class="leading-relaxed">{{ __('Donation infrastructure why goal') }}</p>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
      <div class="rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-brand-midnight">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-secondary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation infra app server') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation infra app server desc') }}</p>
      </div>

      <div class="rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-brand-midnight">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-secondary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
              <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
              <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation infra db server') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation infra db server desc') }}</p>
      </div>

      <div class="rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-brand-midnight">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-secondary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.003A3.5 3.5 0 1114 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation infra backups') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation infra backups desc') }}</p>
      </div>

      <div class="rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-brand-midnight">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-secondary" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation infra media') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation infra media desc') }}</p>
      </div>
    </div>

    <p class="mt-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation infrastructure footer') }}</p>
  </div>
</template>
