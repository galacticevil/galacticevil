<script setup lang="ts">
type ContainerElement = 'div' | 'section' | 'article' | 'header' | 'footer'
type ContainerWidth = 'wide' | 'content' | 'reading'

withDefaults(defineProps<{
  as?: ContainerElement
  width?: ContainerWidth
}>(), {
  as: 'div',
  width: 'wide'
})
</script>

<template>
  <component :is="as" class="ge-container" :class="`ge-container--${width}`">
    <slot />
  </component>
</template>

<style scoped>
.ge-container {
  width: 100%;
  margin-inline: auto;
  padding-inline: 1.25rem;
}

.ge-container--wide {
  max-width: var(--ge-content-max);
}

.ge-container--content {
  max-width: 72rem;
}

.ge-container--reading {
  max-width: calc(var(--ge-content-reading) + 2.5rem);
}

@media (min-width: 48rem) {
  .ge-container {
    padding-inline: 2rem;
  }

  .ge-container--reading {
    max-width: calc(var(--ge-content-reading) + 4rem);
  }
}

@media (min-width: 64rem) {
  .ge-container {
    padding-inline: 3rem;
  }

  .ge-container--reading {
    max-width: calc(var(--ge-content-reading) + 6rem);
  }
}
</style>
