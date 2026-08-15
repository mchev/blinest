<template>
  <a
    href="https://ouvrage.art/"
    target="_blank"
    rel="external nofollow"
    data-umami-event="Ouvrage banner click"
    class="group block overflow-hidden rounded-xl border border-emerald-500/30 bg-gradient-to-br from-emerald-950/50 via-arena-card to-arena-panel p-4 transition hover:border-emerald-400/50 hover:shadow-lg hover:shadow-emerald-950/30"
  >
    <div class="mb-3 flex items-start justify-between gap-3">
      <div class="flex items-center gap-3">
        <img
          src="/images/ouvrage-mark.png"
          alt="Ouvrage"
          class="h-11 w-11 shrink-0 rounded-xl object-contain ring-1 ring-emerald-500/30"
          width="44"
          height="44"
          loading="lazy"
        />
        <div>
          <p class="text-base font-bold tracking-tight text-white">Ouvrage</p>
          <span class="mt-1 inline-flex rounded-md bg-emerald-500/20 px-2 py-0.5 text-[11px] font-black uppercase tracking-wide text-emerald-300 ring-1 ring-emerald-500/30">
            {{ __('Free') }}
          </span>
        </div>
      </div>
    </div>

    <p class="text-sm font-bold leading-snug text-zinc-100">
      {{ __('Not on e-invoicing yet?') }}
    </p>
    <p class="mt-2 text-sm leading-relaxed text-zinc-300">
      {{ __('Ouvrage helps artisans and self-employed manage quotes, e-invoices and accounting in one place — free to get started.') }}
    </p>
    <p class="mt-2 text-xs leading-relaxed text-zinc-400">
      {{ __('E-invoicing is becoming mandatory in France. Get ready without the headache.') }}
    </p>

    <span class="pointer-events-none mt-4 flex w-full game-btn-play-join group-hover:brightness-110">
      {{ __('Discover Ouvrage') }}
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
      </svg>
    </span>
  </a>
</template>
