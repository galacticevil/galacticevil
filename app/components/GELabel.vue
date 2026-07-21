<script setup lang="ts">
type LabelElement = 'span' | 'p' | 'div'
type LabelTone = 'default' | 'muted' | 'primary' | 'info' | 'success' | 'secondary' | 'danger'

withDefaults(defineProps<{
  as?: LabelElement
  tone?: LabelTone
  marker?: boolean
}>(), {
  as: 'p',
  tone: 'info',
  marker: true
})
</script>

<template>
  <component :is="as" class="ge-label ge-telemetry" :class="`ge-label--${tone}`">
    <span v-if="marker" class="ge-label__marker" aria-hidden="true" />
    <slot />
  </component>
</template>

<style scoped>
.ge-label {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
  margin: 0;
  color: var(--ge-text-body);
}

.ge-label__marker {
  display: block;
  width: 1rem;
  height: 1px;
  background: currentColor;
}

.ge-label--muted {
  color: var(--ge-text-muted);
}

.ge-label--primary {
  color: var(--ge-accent-primary);
}

.ge-label--info {
  color: var(--ge-accent-info);
}

.ge-label--success {
  color: var(--ge-accent-success);
}

.ge-label--secondary {
  color: var(--ge-accent-secondary);
}

.ge-label--danger {
  color: var(--ge-accent-danger);
}
</style>
