<script setup lang="ts">
import {
  useDocumentVisibility,
  useElementVisibility,
  usePreferredReducedMotion
} from '@vueuse/core'
import { capabilities } from '~/data/capabilities'

const sectionRoot = ref<HTMLElement | null>(null)
const isSectionVisible = useElementVisibility(sectionRoot, { threshold: 0.05 })
const documentVisibility = useDocumentVisibility()
const reducedMotion = usePreferredReducedMotion()

const isMotionActive = computed(() => (
  reducedMotion.value !== 'reduce'
  && documentVisibility.value === 'visible'
  && isSectionVisible.value
))
</script>

<template>
  <section
    ref="sectionRoot"
    class="home-capabilities"
    :class="{ 'is-motion-active': isMotionActive }"
    :data-motion="isMotionActive ? 'active' : 'paused'"
    aria-labelledby="home-capabilities-title"
  >
    <div class="home-capabilities__handoff" aria-hidden="true">
      <GEContainer class="home-capabilities__handoff-inner">
        <span>Command telemetry</span>
        <span class="home-capabilities__handoff-signal"><i /></span>
        <span>Functions online // 04</span>
      </GEContainer>
    </div>

    <GESection as="div" space="default">
      <GEContainer>
        <header class="home-capabilities__header">
          <div class="home-capabilities__heading-group">
            <GELabel tone="primary">Primary functions // Capability index</GELabel>
            <h2 id="home-capabilities-title" class="home-capabilities__title">
              Senior capability for critical software work.
            </h2>
            <p class="home-capabilities__intro">
              Four ways to bring experienced technical leadership and engineering into a startup or product team, available to companies in the United States and internationally.
            </p>
          </div>

          <GEButton to="/capabilities" variant="secondary" size="large">
            View all capabilities
            <template #trailing>
              <UIcon name="i-lucide-arrow-right" />
            </template>
          </GEButton>
        </header>

        <div class="home-capabilities__console">
          <div class="home-capabilities__console-rail" aria-hidden="true">
            <span>GE-OPS // Active modules</span>
            <span>Engagement routing available</span>
          </div>

          <ol class="home-capabilities__grid">
            <li v-for="capability in capabilities" :key="capability.id">
              <CapabilityPanel :capability="capability" />
            </li>
          </ol>
        </div>
      </GEContainer>
    </GESection>
  </section>
</template>

<style scoped>
.home-capabilities {
  position: relative;
  overflow: hidden;
  background: var(--ge-bg-deep);
}

.home-capabilities::before {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  left: max(1.25rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  width: 1px;
  background: var(--ge-border-muted);
  opacity: 0.45;
}

.home-capabilities__handoff {
  position: relative;
  min-height: 3.5rem;
  border-block: 1px solid var(--ge-border-muted);
  background: var(--ge-surface);
}

.home-capabilities__handoff-inner {
  display: grid;
  min-height: 3.5rem;
  grid-template-columns: auto minmax(2rem, 1fr) auto;
  align-items: center;
  gap: 1rem;
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.5625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.home-capabilities__handoff-signal {
  position: relative;
  height: 1px;
  overflow: hidden;
  background: var(--ge-border);
}

.home-capabilities__handoff-signal::before,
.home-capabilities__handoff-signal::after {
  content: "";
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
}

.home-capabilities__handoff-signal::before {
  right: 0;
  left: 0;
  height: 3px;
  background: repeating-linear-gradient(90deg, transparent 0 0.75rem, var(--ge-border-muted) 0.75rem 1rem);
}

.home-capabilities__handoff-signal::after {
  left: 0;
  width: 18%;
  height: 3px;
  background: var(--ge-accent-info);
  opacity: 0.75;
  animation: ge-capability-handoff 8s steps(8, end) infinite;
  animation-play-state: paused;
}

.home-capabilities__handoff-signal i {
  position: absolute;
  top: 50%;
  right: 0;
  z-index: 1;
  width: 0.375rem;
  height: 0.375rem;
  border: 1px solid var(--ge-accent-success);
  background: var(--ge-bg-deep);
  transform: translateY(-50%);
}

.home-capabilities.is-motion-active .home-capabilities__handoff-signal::after {
  animation-play-state: running;
}

.home-capabilities__header {
  position: relative;
  display: grid;
  gap: 1.75rem;
}

.home-capabilities__heading-group {
  max-width: 52rem;
}

.home-capabilities__title {
  max-width: 22ch;
  margin: 1rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 2rem;
  font-weight: 600;
  line-height: 1.15;
  text-wrap: balance;
}

.home-capabilities__intro {
  max-width: 46rem;
  margin: 1.25rem 0 0;
  color: var(--ge-text-muted);
  font-size: 1rem;
  line-height: 1.7;
}

.home-capabilities__header :deep(.ge-button) {
  width: 100%;
}

.home-capabilities__console {
  position: relative;
  margin-top: 2.5rem;
  border-block: 1px solid var(--ge-border);
  background: var(--ge-bg);
  box-shadow: inset 0 1px rgb(255 255 255 / 0.025);
}

.home-capabilities__console-rail {
  display: flex;
  min-height: 2.75rem;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 0.625rem 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.5625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.home-capabilities__console-rail span:last-child {
  display: none;
}

.home-capabilities__grid {
  display: grid;
  gap: 1px;
  margin: 0;
  padding: 1px 0;
  background: var(--ge-border-muted);
  list-style: none;
}

.home-capabilities__grid > li {
  min-width: 0;
  background: var(--ge-bg-deep);
}

@keyframes ge-capability-handoff {
  0% { transform: translate(-100%, -50%); opacity: 0; }
  12% { opacity: 0.75; }
  76% { opacity: 0.75; }
  88%, 100% { transform: translate(560%, -50%); opacity: 0; }
}

@media (min-width: 48rem) {
  .home-capabilities::before {
    left: max(2rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  }

  .home-capabilities__handoff-inner {
    gap: 1.5rem;
    font-size: 0.625rem;
  }

  .home-capabilities__header {
    gap: 2rem;
  }

  .home-capabilities__title {
    font-size: 2.75rem;
  }

  .home-capabilities__header :deep(.ge-button) {
    width: fit-content;
  }

  .home-capabilities__console {
    margin-top: 3rem;
  }

  .home-capabilities__console-rail span:last-child {
    display: inline;
  }

  .home-capabilities__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    padding: 1rem;
    background: var(--ge-bg-deep);
  }
}

@media (min-width: 75rem) {
  .home-capabilities__header {
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: end;
  }

  .home-capabilities__grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1px;
    padding: 1px 0;
    background: var(--ge-border-muted);
  }
}

@media (prefers-reduced-motion: reduce) {
  .home-capabilities__handoff-signal::after {
    animation: none;
    transform: translate(225%, -50%);
  }
}
</style>
