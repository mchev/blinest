<template>
  <div class="mb-4 flex items-center rounded bg-neutral-700 p-2 text-sm text-neutral-400">
    <div class="mr-2 w-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
      </svg>
    </div>
    <p class="flex-grow">
      <slot />
    </p>
  </div>
</template>
