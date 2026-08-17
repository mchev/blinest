<template>
  <a href="https://ouvrage.art/" target="_blank" rel="external nofollow" data-umami-event="Ouvrage banner click" class="group relative block overflow-hidden rounded-xl border border-white/10 bg-gradient-to-br from-brand-deep to-brand-midnight p-4 transition hover:border-white/20">
    <div class="mb-3 flex items-start gap-3">
      <img src="/images/ouvrage-mark.png" alt="Ouvrage" class="h-11 w-11 shrink-0 rounded-lg object-contain ring-1 ring-white/15" width="44" height="44" loading="lazy" />
      <div>
        <p class="text-base font-bold uppercase tracking-wide text-brand-accent">Ouvrage</p>
        <span class="retro-badge mt-1.5">{{ __('Free') }}</span>
      </div>
    </div>

    <p class="text-sm font-bold leading-snug text-white">
      {{ __('Not on e-invoicing yet?') }}
    </p>
    <p class="mt-2 text-sm leading-relaxed text-white/70">
      {{ __('Ouvrage helps artisans and self-employed manage quotes, e-invoices and accounting in one place — free to get started.') }}
    </p>

    <span class="game-btn-play-join pointer-events-none mt-4 flex w-full">
      {{ __('Discover Ouvrage') }}
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
      </svg>
    </span>
  </a>
</template>
