<script setup lang="ts">
const pageDescription = 'Contact Rory Cottle about fractional CTO leadership, contract engineering, architecture advisory, or project rescue.'

useSeoMeta({
  title: 'Contact | Galactic Evil',
  description: pageDescription,
  ogTitle: 'Open a Project Channel | Galactic Evil',
  ogDescription: pageDescription,
  ogType: 'website'
})
</script>

<template>
  <div class="contact-page">
    <section class="contact-intro" aria-labelledby="contact-title">
      <GEContainer class="contact-intro__layout">
        <div class="contact-intro__heading">
          <GELabel tone="primary">Galactic Evil // Communications</GELabel>
          <h1 id="contact-title">Open a project channel.</h1>
        </div>

        <div class="contact-intro__briefing">
          <p>
            Share the technical challenge, team context, or delivery pressure in front of you. Enough detail to understand the situation is more useful than a polished brief.
          </p>
          <div class="contact-intro__routing">
            <span>Primary routing</span>
            <strong>US startups and product teams</strong>
            <small>South African and international enquiries are welcome.</small>
          </div>
        </div>
      </GEContainer>
    </section>

    <div class="contact-workspace">
      <GEContainer class="contact-workspace__layout">
        <ContactTerminalForm />

        <aside class="contact-brief" aria-labelledby="contact-brief-title">
          <header>
            <span aria-hidden="true">BRF-01</span>
            <div>
              <GELabel tone="info">Useful signal</GELabel>
              <h2 id="contact-brief-title">What helps at first contact</h2>
            </div>
          </header>

          <ol>
            <li>
              <span aria-hidden="true">01</span>
              <p><strong>The situation</strong> What is changing, blocked, or at risk.</p>
            </li>
            <li>
              <span aria-hidden="true">02</span>
              <p><strong>The environment</strong> Product, platform, team, and relevant constraints.</p>
            </li>
            <li>
              <span aria-hidden="true">03</span>
              <p><strong>The decision</strong> What senior technical contribution would make progress possible.</p>
            </li>
          </ol>

          <div class="contact-brief__service-link">
            <span>Need to compare service paths first?</span>
            <NuxtLink to="/capabilities">
              Review capabilities
              <UIcon name="i-lucide-arrow-right" aria-hidden="true" />
            </NuxtLink>
          </div>

          <p class="contact-brief__privacy">
            Enquiry details are transmitted directly to the contact service. They are not cached or retained in browser storage.
          </p>
        </aside>
      </GEContainer>
    </div>
  </div>
</template>

<style scoped>
.contact-page {
  min-height: calc(100vh - var(--ge-header-height));
  background: var(--ge-bg);
}

.contact-intro {
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid var(--ge-border-muted);
  background:
    linear-gradient(90deg, transparent 49.9%, rgb(52 65 86 / 0.18) 50%, transparent 50.1%),
    var(--ge-bg-deep);
}

.contact-intro::after {
  content: "";
  position: absolute;
  right: 0;
  bottom: 1rem;
  width: min(30vw, 24rem);
  height: 1px;
  background: var(--ge-accent-primary);
  opacity: 0.45;
}

.contact-intro__layout {
  display: grid;
  gap: 2rem;
  padding-top: 3rem;
  padding-bottom: 3rem;
}

.contact-intro__heading h1 {
  max-width: 12ch;
  margin: 1rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 2.5rem;
  font-weight: 600;
  line-height: 1.05;
  text-wrap: balance;
}

.contact-intro__briefing {
  max-width: 42rem;
}

.contact-intro__briefing > p {
  margin: 0;
  color: var(--ge-text-body);
  font-size: 1.0625rem;
  line-height: 1.7;
}

.contact-intro__routing {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 0.25rem 0.75rem;
  padding-top: 1rem;
  margin-top: 1.25rem;
  border-top: 1px solid var(--ge-border-muted);
}

.contact-intro__routing span,
.contact-intro__routing small {
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.contact-intro__routing span {
  color: var(--ge-accent-success);
}

.contact-intro__routing strong {
  color: var(--ge-text-heading);
  font-size: 0.875rem;
  font-weight: 600;
}

.contact-intro__routing small {
  grid-column: 1 / -1;
  line-height: 1.5;
  text-transform: none;
}

.contact-workspace {
  position: relative;
  padding-block: 2.5rem 4rem;
  background:
    linear-gradient(90deg, rgb(52 65 86 / 0.09) 1px, transparent 1px) 0 0 / 4rem 100%,
    var(--ge-bg);
}

.contact-workspace__layout {
  display: grid;
  gap: 2rem;
}

.contact-brief {
  align-self: start;
  min-width: 0;
}

.contact-brief > header {
  display: grid;
  grid-template-columns: 2.5rem 1fr;
  gap: 0.875rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--ge-border);
}

.contact-brief > header > span {
  display: grid;
  height: 2.5rem;
  place-items: center;
  border: 1px solid var(--ge-border);
  background: var(--ge-surface);
  color: var(--ge-accent-primary);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
}

.contact-brief h2 {
  margin: 0.5rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 1.125rem;
  font-weight: 600;
  line-height: 1.25;
}

.contact-brief ol {
  padding: 0;
  margin: 0;
  list-style: none;
}

.contact-brief li {
  display: grid;
  grid-template-columns: 2rem 1fr;
  gap: 0.875rem;
  padding-block: 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
}

.contact-brief li > span {
  padding-top: 0.125rem;
  color: var(--ge-accent-info);
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
}

.contact-brief li p {
  margin: 0;
  color: var(--ge-text-muted);
  font-size: 0.875rem;
  line-height: 1.6;
}

.contact-brief li strong {
  display: block;
  margin-bottom: 0.25rem;
  color: var(--ge-text-body);
  font-weight: 600;
}

.contact-brief__service-link {
  display: grid;
  gap: 0.5rem;
  padding: 1rem;
  margin-top: 1.25rem;
  border-left: 2px solid var(--ge-accent-primary);
  background: var(--ge-surface);
}

.contact-brief__service-link > span {
  color: var(--ge-text-muted);
  font-size: 0.75rem;
}

.contact-brief__service-link a {
  display: inline-flex;
  min-height: 2.75rem;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  color: var(--ge-accent-info);
  font-family: var(--ge-font-telemetry);
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none;
  text-transform: uppercase;
}

.contact-brief__service-link a :deep(svg) {
  width: 1rem;
  height: 1rem;
}

.contact-brief__privacy {
  margin: 1.25rem 0 0;
  color: var(--ge-text-muted);
  font-size: 0.75rem;
  line-height: 1.6;
}

@media (hover: hover) {
  .contact-brief__service-link a:hover {
    color: var(--ge-text-heading);
  }
}

@media (min-width: 48rem) {
  .contact-intro__layout {
    padding-top: 4rem;
    padding-bottom: 4rem;
  }

  .contact-intro__heading h1 {
    font-size: 3.5rem;
  }

  .contact-intro__routing {
    grid-template-columns: auto 1fr;
  }

  .contact-intro__routing small {
    grid-column: 2;
  }

  .contact-workspace {
    padding-block: 3rem 5rem;
  }

  .contact-workspace__layout {
    gap: 2.5rem;
  }

  .contact-brief {
    order: -1;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(17rem, 0.7fr);
    gap: 0 2rem;
  }

  .contact-brief > header,
  .contact-brief ol {
    grid-column: 1;
  }

  .contact-brief__service-link,
  .contact-brief__privacy {
    grid-column: 2;
  }

  .contact-brief__service-link {
    grid-row: 1 / span 2;
    align-self: start;
    margin-top: 0;
  }
}

@media (min-width: 64rem) {
  .contact-intro__layout {
    grid-template-columns: minmax(0, 0.9fr) minmax(28rem, 0.75fr);
    align-items: end;
  }

  .contact-intro__briefing {
    justify-self: end;
  }

  .contact-workspace__layout {
    grid-template-columns: minmax(15rem, 0.42fr) minmax(0, 1fr);
    align-items: start;
    gap: 3rem;
  }

  .contact-brief {
    position: sticky;
    top: 2rem;
    display: block;
  }

  .contact-brief__service-link {
    margin-top: 1.25rem;
  }
}

@media (max-width: 47.999rem) {
  .contact-workspace__layout > :deep(.contact-terminal) {
    margin-inline: -1.25rem;
  }
}
</style>
