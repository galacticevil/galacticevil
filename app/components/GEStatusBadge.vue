<script setup lang="ts">
type Status = 'available' | 'limited' | 'unavailable' | 'info' | 'neutral'

withDefaults(defineProps<{
  label: string
  status?: Status
}>(), {
  status: 'neutral'
})
</script>

<template>
  <span class="ge-status ge-telemetry" :class="`ge-status--${status}`">
    <span class="ge-status__indicator" aria-hidden="true" />
    <span>{{ label }}</span>
  </span>
</template>

<style scoped>
.ge-status {
  display: inline-flex;
  min-height: 1.75rem;
  align-items: center;
  gap: 0.5rem;
  padding: 0.25rem 0.5rem;
  border: 1px solid var(--ge-border-muted);
  border-radius: var(--ge-radius-xs);
  background: var(--ge-bg-deep);
  color: var(--ge-text-muted);
  white-space: nowrap;
}

.ge-status__indicator {
  width: 0.375rem;
  height: 0.375rem;
  flex: 0 0 auto;
  background: currentColor;
  box-shadow: 0 0 0 1px var(--ge-bg-deep);
}

.ge-status--available {
  border-color: var(--ge-accent-success);
  color: var(--ge-accent-success);
}

.ge-status--limited {
  border-color: var(--ge-accent-primary);
  color: var(--ge-accent-primary);
}

.ge-status--unavailable {
  border-color: var(--ge-accent-danger);
  color: var(--ge-accent-danger);
}

.ge-status--info {
  border-color: var(--ge-accent-info);
  color: var(--ge-accent-info);
}
</style>
