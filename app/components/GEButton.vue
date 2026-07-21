<script setup lang="ts">
type ButtonVariant = 'primary' | 'secondary' | 'quiet' | 'danger'
type ButtonSize = 'small' | 'medium' | 'large'

const props = withDefaults(defineProps<{
  to?: string
  type?: 'button' | 'submit' | 'reset'
  variant?: ButtonVariant
  size?: ButtonSize
  block?: boolean
  disabled?: boolean
}>(), {
  type: 'button',
  variant: 'primary',
  size: 'medium',
  block: false,
  disabled: false
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()

const classes = computed(() => [
  'ge-button',
  `ge-button--${props.variant}`,
  `ge-button--${props.size}`,
  { 'ge-button--block': props.block }
])

function handleClick(event: MouseEvent) {
  if (props.disabled) {
    event.preventDefault()
    event.stopImmediatePropagation()
    return
  }

  emit('click', event)
}
</script>

<template>
  <NuxtLink
    v-if="to"
    :to="to"
    :class="classes"
    :aria-disabled="disabled ? 'true' : undefined"
    :tabindex="disabled ? -1 : undefined"
    @click="handleClick"
  >
    <span v-if="$slots.leading" class="ge-button__icon" aria-hidden="true">
      <slot name="leading" />
    </span>
    <span class="ge-button__label"><slot /></span>
    <span v-if="$slots.trailing" class="ge-button__icon" aria-hidden="true">
      <slot name="trailing" />
    </span>
  </NuxtLink>

  <button
    v-else
    :type="type"
    :class="classes"
    :disabled="disabled"
    @click="handleClick"
  >
    <span v-if="$slots.leading" class="ge-button__icon" aria-hidden="true">
      <slot name="leading" />
    </span>
    <span class="ge-button__label"><slot /></span>
    <span v-if="$slots.trailing" class="ge-button__icon" aria-hidden="true">
      <slot name="trailing" />
    </span>
  </button>
</template>

<style scoped>
.ge-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.625rem;
  border: 1px solid transparent;
  border-radius: var(--ge-radius-xs);
  font-family: var(--ge-font-telemetry);
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  text-decoration: none;
  text-transform: uppercase;
  box-shadow: inset 0 1px rgb(255 255 255 / 0.12);
  cursor: pointer;
  transition: background-color 140ms ease, border-color 140ms ease, color 140ms ease, box-shadow 140ms ease;
}

.ge-button--small {
  min-height: 2.75rem;
  padding: 0.625rem 0.875rem;
}

.ge-button--medium {
  min-height: var(--ge-control-height);
  padding: 0.75rem 1.125rem;
}

.ge-button--large {
  min-height: 3.25rem;
  padding: 0.875rem 1.375rem;
}

.ge-button--block {
  width: 100%;
}

.ge-button--primary {
  border-color: var(--ge-accent-primary);
  background: var(--ge-accent-primary);
  color: var(--ge-bg-deep);
}

.ge-button--secondary {
  border-color: var(--ge-accent-info);
  background: var(--ge-surface-raised);
  color: var(--ge-text-heading);
}

.ge-button--quiet {
  border-color: var(--ge-border);
  background: var(--ge-surface);
  color: var(--ge-text-body);
}

.ge-button--danger {
  border-color: var(--ge-accent-danger);
  background: var(--ge-surface);
  color: var(--ge-accent-danger);
}

.ge-button[aria-disabled='true'],
.ge-button:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.ge-button__icon {
  display: inline-flex;
  flex: 0 0 auto;
  width: 1rem;
  height: 1rem;
  align-items: center;
  justify-content: center;
}

.ge-button__icon :deep(svg) {
  width: 100%;
  height: 100%;
}

@media (hover: hover) {
  .ge-button--primary:not(:disabled, [aria-disabled='true']):hover {
    border-color: var(--ge-text-heading);
    background: var(--ge-text-heading);
    box-shadow: inset 0 1px rgb(255 255 255 / 0.18), 0 0 18px rgb(255 106 61 / 0.2);
  }

  .ge-button--secondary:not(:disabled, [aria-disabled='true']):hover,
  .ge-button--quiet:not(:disabled, [aria-disabled='true']):hover {
    border-color: var(--ge-accent-info);
    background: var(--ge-surface-active);
    color: var(--ge-accent-info);
    box-shadow: 0 0 16px rgb(79 209 197 / 0.14);
  }

  .ge-button--danger:not(:disabled, [aria-disabled='true']):hover {
    background: var(--ge-accent-danger);
    color: var(--ge-bg-deep);
  }
}

@media (prefers-reduced-motion: reduce) {
  .ge-button {
    transition: none;
  }
}
</style>
