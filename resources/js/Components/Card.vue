<template>
  <section class="flex flex-col rounded-md bg-neutral-100 text-neutral-800 shadow">
    <header v-if="$slots.header" class="mb-2 flex h-16 min-h-fit flex-wrap items-center border-b-2 p-2">
      <slot name="header" />
    </header>
    <main class="flex-1 p-2">
      <slot />
    </main>
    <footer v-if="$slots.footer" class="flex flex-wrap items-center rounded-b-md border-t bg-neutral-200 px-2 py-4">
      <slot name="footer" />
    </footer>
  </section>
</template>
