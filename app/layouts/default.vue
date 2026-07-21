<script setup lang="ts">
const navigation = [
  { label: 'Capabilities', to: '/capabilities' },
  { label: 'Mission Files', to: '/mission-files' },
  { label: 'About', to: '/about' },
  { label: 'Technology', to: '/technology' },
  { label: 'Contact', to: '/contact' }
]
</script>

<template>
  <div class="min-h-screen">
    <a
      href="#main-content"
      class="fixed left-4 top-4 z-50 -translate-y-24 bg-[var(--ge-accent-info)] px-4 py-3 font-semibold text-[var(--ge-bg-deep)] transition-transform focus:translate-y-0 motion-reduce:transition-none"
    >
      Skip to content
    </a>

    <header class="border-b border-[var(--ge-border-muted)] bg-[var(--ge-surface)]">
      <GEContainer class="flex min-h-18 items-center justify-between gap-4 md:gap-6">
        <NuxtLink
          to="/"
          class="ge-font-display inline-flex min-h-11 items-center text-sm font-semibold text-[var(--ge-text-heading)] no-underline"
        >
          GALACTIC EVIL
        </NuxtLink>
        <nav aria-label="Primary" class="hidden md:block">
          <ul class="flex items-center gap-6">
            <li v-for="item in navigation" :key="item.to">
              <NuxtLink
                :to="item.to"
                class="inline-flex min-h-11 items-center text-sm text-[var(--ge-text-muted)] no-underline hover:text-[var(--ge-accent-info)]"
              >
                {{ item.label }}
              </NuxtLink>
            </li>
          </ul>
        </nav>
        <GEStatusBadge label="Available" status="available" />
      </GEContainer>
    </header>

    <main id="main-content">
      <slot />
    </main>
  </div>
</template>
