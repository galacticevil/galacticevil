<script setup lang="ts">
import {
  useDocumentVisibility,
  useElementVisibility,
  usePreferredReducedMotion
} from '@vueuse/core'
import { capabilities, engagementModels } from '~/data/capabilities'

const primaryCapabilities = capabilities.filter(capability => capability.primaryEngagement)
const supportingCapabilities = capabilities.filter(capability => !capability.primaryEngagement)

const heroRoot = ref<HTMLElement | null>(null)
const isHeroVisible = useElementVisibility(heroRoot, { threshold: 0.08 })
const documentVisibility = useDocumentVisibility()
const reducedMotion = usePreferredReducedMotion()

const isMotionActive = computed(() => (
  reducedMotion.value !== 'reduce'
  && documentVisibility.value === 'visible'
  && isHeroVisible.value
))

const pageDescription = 'Fractional CTO leadership, contract engineering, architecture advisory, and project rescue for US startups and international product teams.'

useSeoMeta({
  title: 'Capabilities | Galactic Evil',
  description: pageDescription,
  ogTitle: 'Technical Leadership and Engineering Capabilities | Galactic Evil',
  ogDescription: pageDescription,
  ogType: 'website'
})
</script>

<template>
  <div class="capabilities-page">
    <section
      ref="heroRoot"
      class="capabilities-hero"
      :class="{ 'is-motion-active': isMotionActive }"
      :data-motion="isMotionActive ? 'active' : 'paused'"
      aria-labelledby="capabilities-title"
    >
      <GEContainer class="capabilities-hero__layout">
        <header class="capabilities-hero__header">
          <div>
            <GELabel>Galactic Evil // Technical operations</GELabel>
            <h1 id="capabilities-title" class="capabilities-hero__title">
              Senior technical capability, matched to the work.
            </h1>
          </div>

          <div class="capabilities-hero__briefing">
            <p>
              Fractional leadership, hands-on engineering, architecture guidance, and project recovery for startups and product teams.
            </p>
            <p class="capabilities-hero__regions">
              Built primarily for companies in the United States, with South African and international teams equally welcome.
            </p>
            <div class="capabilities-hero__actions">
              <GEButton to="/contact" size="large">
                <template #leading>
                  <UIcon name="i-lucide-arrow-up-right" />
                </template>
                Discuss a project
              </GEButton>
              <GEButton to="/capabilities#primary-paths" variant="quiet" size="large">
                Review service paths
                <template #trailing>
                  <UIcon name="i-lucide-arrow-down" />
                </template>
              </GEButton>
            </div>
          </div>
        </header>

        <nav class="capabilities-directory" aria-label="Capability directory">
          <div class="capabilities-directory__rail" aria-hidden="true">
            <span>Function directory // 04</span>
            <span class="capabilities-directory__signal"><i /></span>
            <span>Routing ready</span>
          </div>
          <ol>
            <li v-for="capability in capabilities" :key="capability.id">
              <NuxtLink :to="`/capabilities#${capability.id}`">
                <span class="capabilities-directory__index" aria-hidden="true">
                  {{ capability.order.toString().padStart(2, '0') }}
                </span>
                <UIcon :name="capability.icon" aria-hidden="true" />
                <span class="capabilities-directory__name">{{ capability.title }}</span>
                <span class="capabilities-directory__type">
                  {{ capability.primaryEngagement ? 'Primary path' : 'Supporting service' }}
                </span>
              </NuxtLink>
            </li>
          </ol>
        </nav>
      </GEContainer>
    </section>

    <div class="capabilities-main">
      <GESection id="primary-paths" space="spacious">
        <GEContainer>
          <header class="capabilities-section-heading">
            <GELabel tone="primary">Primary engagement paths</GELabel>
            <div>
              <h2>Leadership and delivery, directly embedded.</h2>
              <p>
                These are the strongest routes for teams that need sustained technical ownership, hands-on delivery, or both.
              </p>
            </div>
          </header>

          <div class="capabilities-stack">
            <CapabilityDetail
              v-for="capability in primaryCapabilities"
              :key="capability.id"
              :capability="capability"
            />
          </div>
        </GEContainer>
      </GESection>

      <GESection space="spacious" surface="deep" ruled>
        <GEContainer>
          <header class="capabilities-section-heading">
            <GELabel tone="secondary">Supporting technical services</GELabel>
            <div>
              <h2>Focused intervention when the decision or recovery matters most.</h2>
              <p>
                Architecture advisory and project rescue provide clear, bounded support without reducing difficult work to a generic report.
              </p>
            </div>
          </header>

          <div class="capabilities-stack">
            <CapabilityDetail
              v-for="capability in supportingCapabilities"
              :key="capability.id"
              :capability="capability"
            />
          </div>
        </GEContainer>
      </GESection>

      <GESection space="default" ruled class="engagement-models">
        <GEContainer>
          <header class="engagement-models__header">
            <div>
              <GELabel tone="success">Engagement models // Flexible by design</GELabel>
              <h2 id="engagement-models-title">A practical shape for the work.</h2>
            </div>
            <p>
              The engagement structure follows the problem: ongoing leadership, embedded delivery, a bounded advisory scope, or a recovery-focused assignment.
            </p>
          </header>

          <ol class="engagement-models__grid" aria-labelledby="engagement-models-title">
            <li v-for="model in engagementModels" :key="model.id">
              <GEPanel as="article" padding="none" clipped>
                <header class="engagement-model__header">
                  <span aria-hidden="true">{{ model.order.toString().padStart(2, '0') }}</span>
                  <span>{{ model.title }}</span>
                </header>
                <div class="engagement-model__body">
                  <p>{{ model.summary }}</p>
                  <dl>
                    <dt>Best suited to</dt>
                    <dd>{{ model.bestFor }}</dd>
                  </dl>
                </div>
              </GEPanel>
            </li>
          </ol>
        </GEContainer>
      </GESection>

      <GESection space="spacious" surface="deep" class="capabilities-contact">
        <GEContainer class="capabilities-contact__layout">
          <div>
            <GELabel tone="info">Next transmission // Project context</GELabel>
            <h2>Bring the difficult part into the open.</h2>
            <p>
              Share the product, platform, team, or delivery problem in front of you. Start with the context, constraints, and decision that needs senior technical attention.
            </p>
          </div>

          <div class="capabilities-contact__action">
            <p>Remote from South Africa to the United States and internationally.</p>
            <GEButton to="/contact" size="large">
              <template #leading>
                <UIcon name="i-lucide-radio" />
              </template>
              Discuss a project
              <template #trailing>
                <UIcon name="i-lucide-arrow-right" />
              </template>
            </GEButton>
          </div>
        </GEContainer>
      </GESection>
    </div>
  </div>
</template>

<style scoped>
.capabilities-page {
  background: var(--ge-bg);
}

.capabilities-hero {
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
}

.capabilities-hero::before {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  left: max(1.25rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  width: 1px;
  background: var(--ge-border-muted);
  opacity: 0.5;
}

.capabilities-hero__layout {
  position: relative;
  padding-top: 3.5rem;
  padding-bottom: 0;
}

.capabilities-hero__header {
  display: grid;
  gap: 2rem;
}

.capabilities-hero__title {
  max-width: 16ch;
  margin: 1rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 2.5rem;
  font-weight: 600;
  line-height: 1.08;
  text-wrap: balance;
}

.capabilities-hero__briefing {
  max-width: 42rem;
}

.capabilities-hero__briefing > p {
  margin: 0;
  color: var(--ge-text-body);
  font-size: 1.0625rem;
  line-height: 1.7;
}

.capabilities-hero__briefing .capabilities-hero__regions {
  margin-top: 1rem;
  color: var(--ge-text-muted);
  font-size: 0.9375rem;
}

.capabilities-hero__actions {
  display: grid;
  gap: 0.75rem;
  margin-top: 1.75rem;
}

.capabilities-hero__actions :deep(.ge-button) {
  width: 100%;
}

.capabilities-directory {
  margin-top: 3rem;
  border-top: 1px solid var(--ge-border);
  background: var(--ge-surface);
}

.capabilities-directory__rail {
  display: grid;
  min-height: 2.75rem;
  grid-template-columns: auto minmax(2rem, 1fr) auto;
  align-items: center;
  gap: 1rem;
  padding-inline: 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.5625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capabilities-directory__signal {
  position: relative;
  height: 1px;
  overflow: hidden;
  background: var(--ge-border-muted);
}

.capabilities-directory__signal::after {
  content: "";
  position: absolute;
  top: -1px;
  left: 0;
  width: 14%;
  height: 3px;
  background: var(--ge-accent-info);
  animation: ge-directory-signal 7.5s steps(8, end) infinite;
  animation-play-state: paused;
}

.capabilities-directory__signal i {
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

.capabilities-hero.is-motion-active .capabilities-directory__signal::after {
  animation-play-state: running;
}

.capabilities-directory ol {
  display: grid;
  gap: 1px;
  margin: 0;
  padding: 1px 0 0;
  background: var(--ge-border-muted);
  list-style: none;
}

.capabilities-directory li {
  min-width: 0;
  background: var(--ge-bg-deep);
}

.capabilities-directory a {
  display: grid;
  min-height: 4.75rem;
  grid-template-columns: auto auto minmax(0, 1fr);
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  color: var(--ge-text-body);
  text-decoration: none;
  transition: background-color 140ms ease, color 140ms ease;
}

.capabilities-directory a > svg {
  width: 1rem;
  height: 1rem;
  color: var(--ge-text-muted);
}

.capabilities-directory__index,
.capabilities-directory__type {
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.capabilities-directory__index {
  color: var(--ge-accent-info);
}

.capabilities-directory__name {
  min-width: 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 0.9375rem;
  font-weight: 600;
  line-height: 1.3;
}

.capabilities-directory__type {
  grid-column: 3;
  color: var(--ge-text-muted);
}

.capabilities-main {
  display: block;
}

.capabilities-section-heading {
  display: grid;
  gap: 1.25rem;
}

.capabilities-section-heading > div {
  max-width: 52rem;
}

.capabilities-section-heading h2,
.engagement-models__header h2,
.capabilities-contact h2 {
  margin: 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 2rem;
  font-weight: 600;
  line-height: 1.15;
  text-wrap: balance;
}

.capabilities-section-heading p,
.engagement-models__header > p,
.capabilities-contact p {
  max-width: 46rem;
  margin: 1rem 0 0;
  color: var(--ge-text-muted);
  font-size: 1rem;
  line-height: 1.7;
}

.capabilities-stack {
  display: grid;
  gap: 1.5rem;
  margin-top: 2.5rem;
}

.engagement-models {
  background: var(--ge-surface);
}

.engagement-models__header {
  display: grid;
  gap: 1.25rem;
}

.engagement-models__header h2 {
  margin-top: 1rem;
}

.engagement-models__header > p {
  margin-top: 0;
}

.engagement-models__grid {
  display: grid;
  gap: 1px;
  margin: 2.5rem 0 0;
  padding: 1px 0;
  background: var(--ge-border-muted);
  list-style: none;
}

.engagement-models__grid > li {
  min-width: 0;
  background: var(--ge-bg-deep);
}

.engagement-models__grid :deep(.ge-panel) {
  height: 100%;
}

.engagement-model__header {
  display: flex;
  min-height: 3rem;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
  color: var(--ge-text-heading);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  line-height: 1.4;
  text-transform: uppercase;
}

.engagement-model__header span:first-child {
  color: var(--ge-accent-success);
}

.engagement-model__body {
  padding: 1.25rem;
}

.engagement-models__grid article p {
  margin: 0;
  color: var(--ge-text-body);
  font-size: 0.875rem;
  line-height: 1.65;
}

.engagement-models__grid dl {
  margin: 1.25rem 0 0;
  padding-top: 1rem;
  border-top: 1px solid var(--ge-border-muted);
}

.engagement-models__grid dt {
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  text-transform: uppercase;
}

.engagement-models__grid dd {
  margin: 0.625rem 0 0;
  color: var(--ge-text-muted);
  font-size: 0.8125rem;
  line-height: 1.6;
}

.capabilities-contact {
  position: relative;
  overflow: hidden;
}

.capabilities-contact::before {
  content: "";
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  height: 1px;
  background: var(--ge-accent-info);
  opacity: 0.5;
}

.capabilities-contact__layout {
  position: relative;
  display: grid;
  gap: 2rem;
}

.capabilities-contact h2 {
  max-width: 20ch;
  margin-top: 1rem;
  font-size: 2.25rem;
}

.capabilities-contact__action {
  display: grid;
  align-content: end;
  justify-items: start;
  gap: 1.25rem;
}

.capabilities-contact__action p {
  margin: 0;
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  text-transform: uppercase;
}

.capabilities-contact__action :deep(.ge-button) {
  width: 100%;
}

@keyframes ge-directory-signal {
  0% { transform: translateX(-100%); opacity: 0; }
  12% { opacity: 0.8; }
  76% { opacity: 0.8; }
  88%, 100% { transform: translateX(715%); opacity: 0; }
}

@media (hover: hover) {
  .capabilities-directory a:hover {
    background: var(--ge-surface-active);
    color: var(--ge-accent-info);
  }
}

@media (min-width: 48rem) {
  .capabilities-hero::before {
    left: max(2rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  }

  .capabilities-hero__layout {
    padding-top: 4.5rem;
  }

  .capabilities-hero__title {
    font-size: 3.5rem;
  }

  .capabilities-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2rem;
  }

  .capabilities-hero__actions :deep(.ge-button) {
    width: auto;
  }

  .capabilities-directory {
    margin-top: 4rem;
  }

  .capabilities-directory ol {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .capabilities-directory li:nth-child(even) a {
    border-left: 1px solid var(--ge-border-muted);
  }

  .capabilities-section-heading {
    grid-template-columns: minmax(12rem, 0.36fr) minmax(0, 1fr);
    align-items: start;
    gap: 2rem;
  }

  .capabilities-section-heading h2,
  .engagement-models__header h2 {
    font-size: 2.75rem;
  }

  .capabilities-stack {
    gap: 2rem;
    margin-top: 3.5rem;
  }

  .engagement-models__header {
    grid-template-columns: minmax(0, 1fr) minmax(20rem, 0.8fr);
    align-items: end;
    gap: 3rem;
  }

  .engagement-models__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    padding: 1rem;
    background: var(--ge-bg-deep);
  }

  .capabilities-contact__layout {
    grid-template-columns: minmax(0, 1fr) minmax(18rem, auto);
    align-items: end;
    gap: 4rem;
  }

  .capabilities-contact h2 {
    font-size: 3rem;
  }

  .capabilities-contact__action {
    justify-items: end;
    text-align: right;
  }

  .capabilities-contact__action :deep(.ge-button) {
    width: auto;
  }
}

@media (min-width: 75rem) {
  .capabilities-hero__header {
    grid-template-columns: minmax(0, 1.1fr) minmax(28rem, 0.9fr);
    align-items: end;
    gap: 5rem;
  }

  .capabilities-hero__title {
    font-size: 4.25rem;
  }

  .capabilities-directory ol {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .capabilities-directory li + li a {
    border-left: 1px solid var(--ge-border-muted);
  }

  .engagement-models__grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1px;
    padding: 1px 0;
    background: var(--ge-border-muted);
  }
}

@media (prefers-reduced-motion: reduce) {
  .capabilities-directory__signal::after {
    animation: none;
    transform: translateX(310%);
  }

  .capabilities-directory a {
    transition: none;
  }
}
</style>
