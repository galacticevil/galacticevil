<template>
  <section class="home-hero" aria-labelledby="home-hero-title">
    <GEContainer class="home-hero__layout">
      <div class="home-hero__content">
        <GELabel>Galactic Evil // Earth operations</GELabel>

        <h1 id="home-hero-title" class="home-hero__title">
          <span class="home-hero__title-desktop">
            Technical leadership for ambitious software and unstable planetary conditions.
          </span>
          <span class="home-hero__title-mobile">
            Technical leadership for ambitious software.
          </span>
        </h1>

        <p class="home-hero__intro">
          I'm Rory Cottle, a senior software engineer and former CTO helping startups and product teams design systems, guide engineering teams, rescue difficult projects, and ship dependable software.
          <span class="home-hero__mobile-planetary">Unstable planetary conditions included.</span>
        </p>

        <div class="home-hero__availability">
          <GEStatusBadge label="Available" status="available" />
          <p>Based in South Africa and working remotely with companies in the United States and internationally.</p>
        </div>

        <div class="home-hero__actions">
          <GEButton to="/contact" size="large">
            <template #leading>
              <UIcon name="i-lucide-arrow-up-right" />
            </template>
            Discuss a project
          </GEButton>
          <GEButton to="/capabilities" variant="secondary" size="large">
            View capabilities
            <template #trailing>
              <UIcon name="i-lucide-arrow-right" />
            </template>
          </GEButton>
        </div>

        <p class="home-hero__tone-note">The name is dramatic. The engineering is not.</p>
      </div>

      <HeroCommandScene class="home-hero__scene" />

      <dl class="home-hero__telemetry" aria-label="Operational profile">
        <div>
          <dt>Primary function</dt>
          <dd>Senior software engineer</dd>
        </div>
        <div>
          <dt>Leadership record</dt>
          <dd>Former CTO</dd>
        </div>
        <div>
          <dt>Operating range</dt>
          <dd>US + international remote</dd>
        </div>
      </dl>
    </GEContainer>
  </section>
</template>

<style scoped>
.home-hero {
  position: relative;
  overflow: hidden;
  background: var(--ge-bg);
}

.home-hero::before {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  left: max(1.25rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  width: 1px;
  background: var(--ge-border-muted);
  opacity: 0.45;
}

.home-hero__layout {
  position: relative;
  display: grid;
  grid-template-areas:
    "content"
    "scene"
    "telemetry";
  gap: 2rem;
  padding-top: 3rem;
  padding-bottom: 0;
}

.home-hero__content {
  z-index: 2;
  grid-area: content;
  max-width: 46rem;
}

.home-hero__title {
  max-width: 17ch;
  margin: 1rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 2.25rem;
  font-weight: 600;
  line-height: 1.08;
  text-wrap: balance;
}

.home-hero__title-desktop {
  display: none;
}

.home-hero__title-mobile {
  display: inline;
}

.home-hero__intro {
  max-width: 42rem;
  margin: 1.25rem 0 0;
  color: var(--ge-text-body);
  font-size: 1rem;
  line-height: 1.7;
}

.home-hero__mobile-planetary {
  display: inline;
}

.home-hero__availability {
  display: grid;
  justify-items: start;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.home-hero__availability p {
  max-width: 38rem;
  margin: 0;
  color: var(--ge-text-muted);
  font-size: 0.9375rem;
  line-height: 1.6;
}

.home-hero__actions {
  display: grid;
  gap: 0.75rem;
  margin-top: 1.75rem;
}

.home-hero__actions :deep(.ge-button) {
  width: 100%;
}

.home-hero__tone-note {
  margin: 1rem 0 0;
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  line-height: 1.5;
  text-transform: uppercase;
}

.home-hero__scene {
  grid-area: scene;
}

.home-hero__telemetry {
  display: grid;
  grid-area: telemetry;
  gap: 0;
  margin: 0;
  border-top: 1px solid var(--ge-border);
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
}

.home-hero__telemetry div {
  display: grid;
  grid-template-columns: minmax(7.5rem, 0.75fr) 1fr;
  gap: 1rem;
  padding: 0.875rem 0;
}

.home-hero__telemetry div + div {
  border-top: 1px solid var(--ge-border-muted);
}

.home-hero__telemetry dt,
.home-hero__telemetry dd {
  margin: 0;
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  line-height: 1.5;
  text-transform: uppercase;
}

.home-hero__telemetry dt {
  color: var(--ge-text-muted);
}

.home-hero__telemetry dd {
  color: var(--ge-text-body);
}

@media (min-width: 48rem) {
  .home-hero::before {
    left: max(2rem, calc((100% - var(--ge-content-max)) / 2 + 3rem));
  }

  .home-hero__layout {
    gap: 3rem;
    padding-top: 4rem;
    padding-bottom: 0;
  }

  .home-hero__title {
    max-width: 18ch;
    font-size: 3.75rem;
  }

  .home-hero__title-desktop {
    display: inline;
  }

  .home-hero__title-mobile,
  .home-hero__mobile-planetary {
    display: none;
  }

  .home-hero__intro {
    margin-top: 1.5rem;
    font-size: 1.125rem;
  }

  .home-hero__availability {
    grid-template-columns: auto minmax(0, 1fr);
    align-items: start;
    gap: 1rem;
  }

  .home-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2rem;
  }

  .home-hero__actions :deep(.ge-button) {
    width: auto;
  }

  .home-hero__telemetry {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .home-hero__telemetry div {
    display: block;
    padding: 1rem;
  }

  .home-hero__telemetry div:first-child {
    padding-left: 0;
  }

  .home-hero__telemetry div + div {
    border-top: 0;
    border-left: 1px solid var(--ge-border-muted);
  }

  .home-hero__telemetry dd {
    margin-top: 0.375rem;
  }
}

@media (min-width: 75rem) {
  .home-hero__layout {
    min-height: calc(100svh - var(--ge-header-height));
    grid-template-columns: minmax(0, 0.88fr) minmax(34rem, 1.12fr);
    grid-template-rows: 1fr auto;
    grid-template-areas:
      "content scene"
      "telemetry scene";
    column-gap: 4rem;
    row-gap: 2rem;
    align-items: center;
    padding-top: 2rem;
    padding-bottom: 0;
  }

  .home-hero::after {
    content: "";
    position: absolute;
    top: 14%;
    bottom: 9%;
    left: 50%;
    width: 1px;
    background: var(--ge-border-muted);
  }

  .home-hero__title {
    max-width: 18ch;
    font-size: 3rem;
  }

  .home-hero__content {
    align-self: center;
  }

  .home-hero__scene {
    align-self: stretch;
  }

  .home-hero__telemetry {
    align-self: end;
  }
}

@media (min-width: 96rem) {
  .home-hero__layout {
    column-gap: 5rem;
  }
}
</style>
