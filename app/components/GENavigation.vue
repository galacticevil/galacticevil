<script setup lang="ts">
import { onKeyStroke, useMediaQuery } from '@vueuse/core'
import { primaryNavigation, type NavigationItem } from '~/data/navigation'

const route = useRoute()
const menuButton = ref<HTMLButtonElement | null>(null)
const isMenuOpen = ref(false)
const isDesktop = useMediaQuery('(min-width: 64rem)')

function closeMenu({ restoreFocus = false } = {}) {
  if (!isMenuOpen.value) return

  isMenuOpen.value = false
  if (restoreFocus) nextTick(() => menuButton.value?.focus())
}

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
}

function isCurrent(item: NavigationItem) {
  return route.path === item.to || route.path.startsWith(`${item.to}/`)
}

watch(() => route.fullPath, () => closeMenu())
watch(isDesktop, desktop => desktop && closeMenu())
onKeyStroke('Escape', () => closeMenu({ restoreFocus: true }))
</script>

<template>
  <header class="site-header" :class="{ 'site-header--open': isMenuOpen }">
    <GEContainer class="site-header__inner">
      <NuxtLink to="/" class="site-header__brand" aria-label="Galactic Evil home">
        <span class="site-header__brand-mark" aria-hidden="true">
          <span />
        </span>
        <span>Galactic Evil</span>
      </NuxtLink>

      <nav class="site-header__desktop-nav" aria-label="Primary navigation">
        <ul>
          <li v-for="item in primaryNavigation" :key="item.to">
            <NuxtLink
              :to="item.to"
              :class="{ 'is-current': isCurrent(item) }"
            >
              {{ item.label }}
            </NuxtLink>
          </li>
        </ul>
      </nav>

      <GEStatusBadge class="site-header__status" label="Available" status="available" />

      <button
        ref="menuButton"
        type="button"
        class="site-header__menu-button"
        :aria-expanded="isMenuOpen"
        aria-controls="mobile-navigation"
        :aria-label="isMenuOpen ? 'Close navigation menu' : 'Open navigation menu'"
        @click="toggleMenu"
      >
        <UIcon :name="isMenuOpen ? 'i-lucide-x' : 'i-lucide-menu'" aria-hidden="true" />
      </button>
    </GEContainer>

    <nav
      id="mobile-navigation"
      class="site-header__mobile-nav"
      aria-label="Mobile navigation"
      :hidden="!isMenuOpen"
    >
      <GEContainer>
        <ul>
          <li v-for="(item, index) in primaryNavigation" :key="item.to">
            <NuxtLink
              :to="item.to"
              :class="{ 'is-current': isCurrent(item) }"
              @click="closeMenu()"
            >
              <span class="site-header__nav-index" aria-hidden="true">0{{ index + 1 }}</span>
              <span>{{ item.label }}</span>
              <UIcon name="i-lucide-arrow-up-right" aria-hidden="true" />
            </NuxtLink>
          </li>
        </ul>
        <div class="site-header__mobile-status">
          <GEStatusBadge label="Available" status="available" />
          <span>South Africa // Remote worldwide</span>
        </div>
      </GEContainer>
    </nav>
  </header>
</template>

<style scoped>
.site-header {
  position: relative;
  z-index: 40;
  min-height: var(--ge-header-height);
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-surface);
  box-shadow: inset 0 -1px rgb(3 6 11 / 0.75);
}

.site-header__inner {
  display: flex;
  min-height: var(--ge-header-height);
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}

.site-header__brand {
  display: inline-flex;
  min-height: 2.75rem;
  align-items: center;
  gap: 0.75rem;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 0.875rem;
  font-weight: 600;
  text-decoration: none;
  text-transform: uppercase;
}

.site-header__brand-mark {
  position: relative;
  display: grid;
  width: 1.75rem;
  height: 1.75rem;
  flex: 0 0 auto;
  place-items: center;
  border: 1px solid var(--ge-border);
  background: var(--ge-bg-deep);
  transform: rotate(45deg);
}

.site-header__brand-mark::before,
.site-header__brand-mark::after,
.site-header__brand-mark span {
  content: "";
  position: absolute;
  background: var(--ge-accent-primary);
}

.site-header__brand-mark::before {
  width: 0.75rem;
  height: 2px;
}

.site-header__brand-mark::after {
  width: 2px;
  height: 0.75rem;
}

.site-header__brand-mark span {
  width: 0.25rem;
  height: 0.25rem;
  background: var(--ge-accent-info);
}

.site-header__desktop-nav {
  display: none;
}

.site-header__desktop-nav ul,
.site-header__mobile-nav ul {
  padding: 0;
  margin: 0;
  list-style: none;
}

.site-header__desktop-nav ul {
  align-items: center;
  gap: 0.25rem;
}

.site-header__desktop-nav a {
  position: relative;
  display: inline-flex;
  min-height: 2.75rem;
  align-items: center;
  padding-inline: 0.75rem;
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none;
  text-transform: uppercase;
}

.site-header__desktop-nav a::after {
  content: "";
  position: absolute;
  right: 0.75rem;
  bottom: 0.25rem;
  left: 0.75rem;
  height: 1px;
  background: transparent;
}

.site-header__desktop-nav a:hover,
.site-header__desktop-nav a.is-current {
  color: var(--ge-accent-info);
}

.site-header__desktop-nav a.is-current::after {
  background: var(--ge-accent-info);
}

.site-header__status {
  display: none;
}

.site-header__menu-button {
  display: inline-grid;
  width: 3rem;
  height: 3rem;
  flex: 0 0 auto;
  place-items: center;
  border: 1px solid var(--ge-border);
  border-radius: var(--ge-radius-xs);
  background: var(--ge-bg-deep);
  color: var(--ge-text-heading);
  box-shadow: inset 0 1px rgb(255 255 255 / 0.04);
  cursor: pointer;
}

.site-header__menu-button :deep(svg) {
  width: 1.25rem;
  height: 1.25rem;
}

.site-header__menu-button:hover,
.site-header__menu-button[aria-expanded='true'] {
  border-color: var(--ge-accent-info);
  color: var(--ge-accent-info);
}

.site-header__mobile-nav {
  position: absolute;
  top: 100%;
  right: 0;
  left: 0;
  z-index: 30;
  border-bottom: 1px solid var(--ge-accent-info);
  background: var(--ge-surface);
  box-shadow: 0 18px 36px rgb(3 6 11 / 0.72), inset 0 -1px var(--ge-bg-deep);
}

.site-header__mobile-nav[hidden] {
  display: none;
}

.site-header__mobile-nav ul {
  padding-block: 0.75rem;
}

.site-header__mobile-nav li + li {
  border-top: 1px solid var(--ge-border-muted);
}

.site-header__mobile-nav a {
  display: grid;
  min-height: 3.25rem;
  grid-template-columns: 2rem 1fr 1.25rem;
  align-items: center;
  gap: 0.75rem;
  color: var(--ge-text-body);
  font-family: var(--ge-font-display);
  font-size: 0.875rem;
  font-weight: 500;
  text-decoration: none;
  text-transform: uppercase;
}

.site-header__mobile-nav a.is-current,
.site-header__mobile-nav a:hover {
  color: var(--ge-accent-info);
}

.site-header__mobile-nav a :deep(svg) {
  width: 1rem;
  height: 1rem;
}

.site-header__nav-index,
.site-header__mobile-status {
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.site-header__mobile-status {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding-block: 1rem;
  border-top: 1px solid var(--ge-border);
}

@media (min-width: 48rem) {
  .site-header__status {
    display: inline-flex;
  }
}

@media (min-width: 64rem) {
  .site-header__desktop-nav,
  .site-header__desktop-nav ul {
    display: flex;
  }

  .site-header__menu-button,
  .site-header__mobile-nav {
    display: none;
  }
}

@media (prefers-reduced-motion: reduce) {
  .site-header__brand-mark {
    transform: rotate(45deg);
  }
}
</style>
