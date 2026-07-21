<script setup lang="ts">
type PanelElement = 'div' | 'section' | 'article' | 'aside'
type PanelTreatment = 'inset' | 'raised'
type PanelTone = 'neutral' | 'primary' | 'info' | 'success' | 'secondary' | 'danger'
type PanelPadding = 'none' | 'small' | 'medium' | 'large'

withDefaults(defineProps<{
  as?: PanelElement
  treatment?: PanelTreatment
  tone?: PanelTone
  padding?: PanelPadding
  clipped?: boolean
}>(), {
  as: 'div',
  treatment: 'inset',
  tone: 'neutral',
  padding: 'medium',
  clipped: false
})
</script>

<template>
  <component
    :is="as"
    class="ge-panel"
    :class="[
      `ge-panel--${treatment}`,
      `ge-panel--${tone}`,
      `ge-panel--padding-${padding}`,
      { 'ge-panel--clipped': clipped }
    ]"
  >
    <div class="ge-panel__surface">
      <slot />
    </div>
  </component>
</template>

<style scoped>
.ge-panel {
  --ge-panel-border-color: var(--ge-border);
  --ge-panel-background: var(--ge-surface);
  position: relative;
  padding: 1px;
  border-radius: var(--ge-radius-sm);
  background: var(--ge-panel-border-color);
  box-shadow: var(--ge-panel-shadow);
}

.ge-panel__surface {
  min-height: 100%;
  border-radius: calc(var(--ge-radius-sm) - 1px);
  background: var(--ge-panel-background);
  box-shadow: inset 0 1px rgb(255 255 255 / 0.035), inset 0 0 28px rgb(3 6 11 / 0.22);
}

.ge-panel--raised {
  --ge-panel-background: var(--ge-surface-raised);
}

.ge-panel--primary {
  --ge-panel-border-color: var(--ge-accent-primary);
}

.ge-panel--info {
  --ge-panel-border-color: var(--ge-accent-info);
}

.ge-panel--success {
  --ge-panel-border-color: var(--ge-accent-success);
}

.ge-panel--secondary {
  --ge-panel-border-color: var(--ge-accent-secondary);
}

.ge-panel--danger {
  --ge-panel-border-color: var(--ge-accent-danger);
}

.ge-panel--padding-none .ge-panel__surface {
  padding: 0;
}

.ge-panel--padding-small .ge-panel__surface {
  padding: 1rem;
}

.ge-panel--padding-medium .ge-panel__surface {
  padding: 1.25rem;
}

.ge-panel--padding-large .ge-panel__surface {
  padding: 1.5rem;
}

.ge-panel--clipped,
.ge-panel--clipped .ge-panel__surface {
  border-radius: 0;
  clip-path: polygon(0 0, calc(100% - 0.75rem) 0, 100% 0.75rem, 100% 100%, 0 100%);
}

@media (min-width: 48rem) {
  .ge-panel--padding-medium .ge-panel__surface {
    padding: 1.5rem;
  }

  .ge-panel--padding-large .ge-panel__surface {
    padding: 2rem;
  }
}
</style>
