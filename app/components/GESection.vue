<script setup lang="ts">
type SectionElement = 'section' | 'div' | 'aside'
type SectionSpace = 'compact' | 'default' | 'spacious'
type SectionSurface = 'transparent' | 'surface' | 'deep'

withDefaults(defineProps<{
  as?: SectionElement
  space?: SectionSpace
  surface?: SectionSurface
  ruled?: boolean
}>(), {
  as: 'section',
  space: 'default',
  surface: 'transparent',
  ruled: false
})
</script>

<template>
  <component
    :is="as"
    class="ge-section"
    :class="[
      `ge-section--${space}`,
      `ge-section--${surface}`,
      { 'ge-section--ruled': ruled }
    ]"
  >
    <slot />
  </component>
</template>

<style scoped>
.ge-section {
  width: 100%;
}

.ge-section--compact {
  padding-block: 2.5rem;
}

.ge-section--default {
  padding-block: 4rem;
}

.ge-section--spacious {
  padding-block: 5rem;
}

.ge-section--surface {
  background: var(--ge-surface);
}

.ge-section--deep {
  background: var(--ge-bg-deep);
}

.ge-section--ruled {
  border-block: 1px solid var(--ge-border-muted);
}

@media (min-width: 48rem) {
  .ge-section--compact {
    padding-block: 3rem;
  }

  .ge-section--default {
    padding-block: 4.5rem;
  }

  .ge-section--spacious {
    padding-block: 6.5rem;
  }
}
</style>
