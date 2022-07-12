<template>
  <section class="flex flex-col rounded-md bg-neutral-800 shadow">
    <header v-if="$slots.header" class="mb-2 flex h-16 min-h-fit flex-wrap items-center border-b-2 border-neutral-700 p-2 text-neutral-300">
      <slot name="header" />
    </header>
    <main class="flex-1 p-2">
      <slot />
    </main>
    <footer v-if="$slots.footer" class="flex flex-wrap items-center rounded-b-md border-t border-neutral-700 px-2 py-4">
      <slot name="footer" />
    </footer>
  </section>
</template>
