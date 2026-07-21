<script setup lang="ts">
import type { Capability } from '~/data/capabilities'

const props = defineProps<{
  capability: Capability
}>()

const indexLabel = computed(() => props.capability.order.toString().padStart(2, '0'))
</script>

<template>
  <GEPanel
    :id="capability.id"
    as="article"
    padding="none"
    clipped
    class="capability-detail"
    :class="[
      `capability-detail--${capability.accent}`,
      { 'capability-detail--primary': capability.primaryEngagement }
    ]"
    :aria-labelledby="`${capability.id}-title`"
  >
    <header class="capability-detail__rail">
      <div class="capability-detail__identity">
        <span class="capability-detail__index" aria-hidden="true">{{ indexLabel }}</span>
        <UIcon :name="capability.icon" class="capability-detail__icon" aria-hidden="true" />
        <span>{{ capability.primaryEngagement ? 'Primary commercial path' : 'Supporting technical service' }}</span>
      </div>

      <ul class="capability-detail__services" :aria-label="`${capability.title} operating areas`">
        <li v-for="service in capability.services" :key="service">
          {{ service }}
        </li>
      </ul>
    </header>

    <div class="capability-detail__body">
      <div class="capability-detail__overview">
        <h3 :id="`${capability.id}-title`" class="capability-detail__title">
          {{ capability.title }}
        </h3>
        <p class="capability-detail__summary">{{ capability.summary }}</p>
        <p class="capability-detail__description">{{ capability.description }}</p>

        <section class="capability-detail__contribution" :aria-labelledby="`${capability.id}-contribution`">
          <h4 :id="`${capability.id}-contribution`">How Rory contributes</h4>
          <p>{{ capability.contribution }}</p>
        </section>
      </div>

      <div class="capability-detail__matrix">
        <section :aria-labelledby="`${capability.id}-scope`">
          <h4 :id="`${capability.id}-scope`">Scope</h4>
          <ul>
            <li v-for="item in capability.scope" :key="item">{{ item }}</li>
          </ul>
        </section>

        <section :aria-labelledby="`${capability.id}-situations`">
          <h4 :id="`${capability.id}-situations`">Ideal engagement situations</h4>
          <ul>
            <li v-for="item in capability.idealSituations" :key="item">{{ item }}</li>
          </ul>
        </section>

        <section :aria-labelledby="`${capability.id}-deliverables`">
          <h4 :id="`${capability.id}-deliverables`">Likely deliverables</h4>
          <ul>
            <li v-for="item in capability.likelyDeliverables" :key="item">{{ item }}</li>
          </ul>
        </section>
      </div>
    </div>
  </GEPanel>
</template>

<style scoped>
.capability-detail {
  --capability-accent: var(--ge-text-muted);
  scroll-margin-top: calc(var(--ge-header-height) + 1.5rem);
}

.capability-detail--orange {
  --capability-accent: var(--ge-accent-primary);
}

.capability-detail--cyan {
  --capability-accent: var(--ge-accent-info);
}

.capability-detail--violet {
  --capability-accent: var(--ge-accent-secondary);
}

.capability-detail--green {
  --capability-accent: var(--ge-accent-success);
}

.capability-detail__rail {
  display: grid;
  gap: 0.875rem;
  padding: 0.875rem 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capability-detail__identity {
  display: flex;
  min-width: 0;
  align-items: center;
  gap: 0.75rem;
}

.capability-detail__index,
.capability-detail--primary .capability-detail__identity span:last-child {
  color: var(--capability-accent);
}

.capability-detail__icon {
  width: 1rem;
  height: 1rem;
  flex: 0 0 auto;
  color: var(--ge-text-body);
}

.capability-detail__services {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
  margin: 0;
  padding: 0;
  list-style: none;
}

.capability-detail__services li {
  position: relative;
  padding-left: 0.75rem;
}

.capability-detail__services li::before {
  content: "";
  position: absolute;
  top: 0.4rem;
  left: 0;
  width: 0.25rem;
  height: 0.25rem;
  background: var(--capability-accent);
}

.capability-detail__body {
  border-left: 3px solid var(--capability-accent);
}

.capability-detail__overview {
  padding: 1.5rem 1.25rem;
}

.capability-detail__title {
  margin: 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 1.75rem;
  font-weight: 600;
  line-height: 1.2;
  text-wrap: balance;
}

.capability-detail__summary {
  margin: 1rem 0 0;
  color: var(--ge-text-body);
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.6;
}

.capability-detail__description {
  margin: 0.875rem 0 0;
  color: var(--ge-text-muted);
  font-size: 0.9375rem;
  line-height: 1.7;
}

.capability-detail__contribution {
  margin-top: 1.5rem;
  padding-top: 1.25rem;
  border-top: 1px solid var(--ge-border-muted);
}

.capability-detail__contribution h4,
.capability-detail__matrix h4 {
  margin: 0;
  color: var(--capability-accent);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capability-detail__contribution p {
  margin: 0.75rem 0 0;
  color: var(--ge-text-body);
  font-size: 0.875rem;
  line-height: 1.7;
}

.capability-detail__matrix {
  display: grid;
  border-top: 1px solid var(--ge-border-muted);
}

.capability-detail__matrix section {
  padding: 1.25rem;
}

.capability-detail__matrix section + section {
  border-top: 1px solid var(--ge-border-muted);
}

.capability-detail__matrix ul {
  display: grid;
  gap: 0.75rem;
  margin: 1rem 0 0;
  padding: 0;
  list-style: none;
}

.capability-detail__matrix li {
  position: relative;
  padding-left: 1rem;
  color: var(--ge-text-muted);
  font-size: 0.875rem;
  line-height: 1.55;
}

.capability-detail__matrix li::before {
  content: "";
  position: absolute;
  top: 0.55rem;
  left: 0;
  width: 0.375rem;
  height: 0.375rem;
  border: 1px solid var(--capability-accent);
}

@media (min-width: 48rem) {
  .capability-detail__rail {
    grid-template-columns: minmax(15rem, auto) minmax(0, 1fr);
    align-items: center;
    padding-inline: 1.5rem;
  }

  .capability-detail__services {
    justify-content: flex-end;
  }

  .capability-detail__overview {
    padding: 2rem;
  }

  .capability-detail__title {
    font-size: 2rem;
  }

  .capability-detail__matrix {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .capability-detail__matrix section {
    padding: 1.5rem;
  }

  .capability-detail__matrix section + section {
    border-top: 0;
    border-left: 1px solid var(--ge-border-muted);
  }

  .capability-detail__matrix section:last-child {
    grid-column: 1 / -1;
    border-top: 1px solid var(--ge-border-muted);
    border-left: 0;
  }
}

@media (min-width: 64rem) {
  .capability-detail__matrix {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .capability-detail__matrix section:last-child {
    grid-column: auto;
    border-top: 0;
    border-left: 1px solid var(--ge-border-muted);
  }
}

@media (min-width: 75rem) {
  .capability-detail__body {
    display: grid;
    grid-template-columns: minmax(19rem, 0.72fr) minmax(0, 1.58fr);
  }

  .capability-detail__overview {
    padding: 2.25rem;
  }

  .capability-detail__matrix {
    border-top: 0;
    border-left: 1px solid var(--ge-border-muted);
  }

  .capability-detail__matrix section {
    padding: 2.25rem 1.5rem;
  }
}
</style>
