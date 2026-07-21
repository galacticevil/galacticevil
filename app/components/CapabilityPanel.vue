<script setup lang="ts">
import type { Capability } from '~/data/capabilities'

const props = defineProps<{
  capability: Capability
}>()

const indexLabel = computed(() => props.capability.order.toString().padStart(2, '0'))
</script>

<template>
  <GEPanel
    as="article"
    padding="none"
    clipped
    class="capability-panel"
    :class="`capability-panel--${capability.accent}`"
  >
    <div class="capability-panel__module">
      <header class="capability-panel__header">
        <span class="capability-panel__index" aria-hidden="true">{{ indexLabel }}</span>
        <UIcon :name="capability.icon" class="capability-panel__icon" aria-hidden="true" />
        <span v-if="capability.primaryEngagement" class="capability-panel__priority">
          Primary engagement
        </span>
      </header>

      <div class="capability-panel__body">
        <h3 class="capability-panel__title">{{ capability.title }}</h3>
        <p class="capability-panel__summary">{{ capability.summary }}</p>
        <p class="capability-panel__description">{{ capability.description }}</p>

        <div class="capability-panel__services">
          <p :id="`${capability.id}-services`">Operational scope</p>
          <ul :aria-labelledby="`${capability.id}-services`">
            <li v-for="service in capability.services" :key="service">
              {{ service }}
            </li>
          </ul>
        </div>

        <NuxtLink
          to="/capabilities"
          class="capability-panel__link"
          :aria-label="`Explore ${capability.title} on the Capabilities page`"
        >
          <span>Explore capability</span>
          <UIcon name="i-lucide-arrow-up-right" aria-hidden="true" />
        </NuxtLink>
      </div>
    </div>
  </GEPanel>
</template>

<style scoped>
.capability-panel {
  --capability-accent: var(--ge-text-muted);
  height: 100%;
}

.capability-panel--orange {
  --capability-accent: var(--ge-accent-primary);
}

.capability-panel--cyan {
  --capability-accent: var(--ge-accent-info);
}

.capability-panel--violet {
  --capability-accent: var(--ge-accent-secondary);
}

.capability-panel--green {
  --capability-accent: var(--ge-accent-success);
}

.capability-panel__module {
  display: flex;
  min-height: 100%;
  flex-direction: column;
  border-left: 3px solid var(--capability-accent);
}

.capability-panel__header {
  display: grid;
  min-height: 3.25rem;
  grid-template-columns: auto auto minmax(0, 1fr);
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capability-panel__index {
  color: var(--capability-accent);
}

.capability-panel__icon {
  width: 1rem;
  height: 1rem;
  color: var(--ge-text-body);
}

.capability-panel__priority {
  justify-self: end;
  color: var(--capability-accent);
  text-align: right;
}

.capability-panel__body {
  display: flex;
  min-height: 100%;
  flex: 1;
  flex-direction: column;
  padding: 1.25rem;
}

.capability-panel__title {
  margin: 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 1.25rem;
  font-weight: 600;
  line-height: 1.25;
  text-wrap: balance;
}

.capability-panel__summary {
  margin: 1rem 0 0;
  color: var(--ge-text-body);
  font-size: 0.9375rem;
  font-weight: 600;
  line-height: 1.55;
}

.capability-panel__description {
  margin: 0.75rem 0 0;
  color: var(--ge-text-muted);
  font-size: 0.875rem;
  line-height: 1.65;
}

.capability-panel__services {
  margin-top: 1.25rem;
  padding-top: 1rem;
  border-top: 1px solid var(--ge-border-muted);
}

.capability-panel__services p {
  margin: 0;
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capability-panel__services ul {
  display: grid;
  gap: 0.5rem;
  margin: 0.75rem 0 0;
  padding: 0;
  list-style: none;
}

.capability-panel__services li {
  position: relative;
  padding-left: 1rem;
  color: var(--ge-text-body);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  line-height: 1.5;
  text-transform: uppercase;
}

.capability-panel__services li::before {
  content: "";
  position: absolute;
  top: 0.45rem;
  left: 0;
  width: 0.375rem;
  height: 0.375rem;
  border: 1px solid var(--capability-accent);
}

.capability-panel__link {
  display: flex;
  min-height: 2.75rem;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  margin-top: auto;
  padding-top: 1.25rem;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  line-height: 1.4;
  text-decoration: none;
  text-transform: uppercase;
  transition: color 140ms ease;
}

.capability-panel__link svg {
  width: 1rem;
  height: 1rem;
  flex: 0 0 auto;
  color: var(--capability-accent);
  transition: transform 140ms ease;
}

@media (hover: hover) {
  .capability-panel__link:hover {
    color: var(--capability-accent);
  }

  .capability-panel__link:hover svg {
    transform: translate(0.125rem, -0.125rem);
  }
}

@media (min-width: 48rem) {
  .capability-panel__body {
    padding: 1.5rem;
  }

  .capability-panel__title {
    font-size: 1.375rem;
  }
}

@media (min-width: 75rem) {
  .capability-panel__body {
    min-height: 25rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  .capability-panel__link,
  .capability-panel__link svg {
    transition: none;
  }
}
</style>
