<template>
  <section class="flex flex-col rounded-md bg-neutral-100 text-neutral-800 shadow">
    <header v-if="$slots.header" class="mb-2 border-b-2 px-2 py-4">
      <slot name="header" />
    </header>
    <main class="flex-1 p-2">
      <slot />
    </main>
    <footer v-if="$slots.footer" class="p-2">
      <slot name="footer" />
    </footer>
  </section>
</template>
