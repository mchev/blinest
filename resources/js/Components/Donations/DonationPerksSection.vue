<script setup>
import LucideIcon from '@/Components/Icons/LucideIcon.vue'
</script>

<template>
  <div class="rounded-2xl border border-amber-500/20 bg-gradient-to-br from-slate-800 via-slate-900 to-amber-950/20 p-6">
    <div class="mb-4 flex flex-wrap items-center gap-2">
      <h2 class="text-2xl font-bold text-white">{{ __('Donation perks title') }}</h2>
      <span class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-300">
        {{ __('Donation perks from one euro') }}
      </span>
    </div>
    <p class="mb-2 text-sm leading-relaxed text-neutral-300">{{ __('Donation perks intro') }}</p>
    <p class="mb-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation perks identified note') }}</p>

    <div class="grid gap-3 sm:grid-cols-2">
      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
            <LucideIcon name="circle-slash" />
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk ad free title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk ad free desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
            <LucideIcon name="crown" />
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk crown title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk crown desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
            <LucideIcon name="smile-plus" />
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk reactions title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk reactions desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
            <LucideIcon name="calendar-days" />
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk tenure title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk tenure desc') }}</p>
      </div>

      <div class="rounded-xl border border-amber-400/15 bg-white/5 p-4 sm:col-span-2">
        <div class="mb-2 flex items-center gap-2">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-amber-400/20 bg-amber-500/10 text-amber-300">
            <LucideIcon name="badge-check" />
          </span>
          <h3 class="font-semibold text-white">{{ __('Donation perk profile title') }}</h3>
        </div>
        <p class="text-sm leading-relaxed text-neutral-400">{{ __('Donation perk profile desc') }}</p>
      </div>
    </div>

    <p class="mt-6 text-sm leading-relaxed text-neutral-400">{{ __('Donation perks community note') }}</p>
  </div>
</template>
