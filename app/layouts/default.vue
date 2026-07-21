<template>
  <div class="min-h-screen">
    <a
      href="#main-content"
      class="fixed left-4 top-4 z-50 -translate-y-24 bg-[var(--ge-accent-info)] px-4 py-3 font-semibold text-[var(--ge-bg-deep)] transition-transform focus:translate-y-0 motion-reduce:transition-none"
    >
      Skip to content
    </a>

    <GENavigation />

    <main id="main-content">
      <slot />
    </main>
  </div>
</template>
