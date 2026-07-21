<script setup lang="ts">
defineProps<{
  eyebrow: string
  title: string
  description: string
}>()
</script>

<template>
  <GESection space="spacious">
    <GEContainer>
      <GELabel>{{ eyebrow }}</GELabel>
      <h1 class="ge-title mt-4">{{ title }}</h1>
      <p class="ge-copy ge-copy--lead mt-6">{{ description }}</p>
    </GEContainer>
  </GESection>
</template>
