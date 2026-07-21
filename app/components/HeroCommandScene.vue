<script setup lang="ts">
import {
  useDocumentVisibility,
  useElementVisibility,
  usePreferredReducedMotion
} from '@vueuse/core'

const sceneRoot = ref<HTMLElement | null>(null)
const isSceneVisible = useElementVisibility(sceneRoot, { threshold: 0.08 })
const documentVisibility = useDocumentVisibility()
const reducedMotion = usePreferredReducedMotion()

const motionState = computed(() => {
  if (reducedMotion.value === 'reduce') return 'reduced'
  if (documentVisibility.value !== 'visible') return 'document-hidden'
  if (!isSceneVisible.value) return 'offscreen'
  return 'active'
})

const isMotionActive = computed(() => motionState.value === 'active')
</script>

<template>
  <div
    ref="sceneRoot"
    class="command-scene"
    :class="{ 'is-motion-active': isMotionActive }"
    :data-motion="motionState"
    role="img"
    aria-label="Placeholder orbital command scene showing Earth, station instruments, and a radar console"
  >
    <div class="scene__caption" aria-hidden="true">
      <span>Orbital relay // Visual placeholder</span>
      <span>Feed 03</span>
    </div>

    <div class="scene__layer scene__backdrop" aria-hidden="true">
      <div class="scene__stars">
        <span v-for="star in 18" :key="star" />
      </div>
      <div class="scene__orbit scene__orbit--outer" />
      <div class="scene__orbit scene__orbit--inner" />
      <div class="scene__planet">
        <span class="scene__planet-shadow" />
        <span class="scene__planet-marker" />
      </div>
      <div class="scene__moon" />
    </div>

    <div class="scene__layer scene__structure" aria-hidden="true">
      <span class="scene__beam scene__beam--top" />
      <span class="scene__beam scene__beam--left" />
      <span class="scene__beam scene__beam--right" />
      <span class="scene__brace scene__brace--left" />
      <span class="scene__brace scene__brace--right" />
    </div>

    <div class="scene__layer scene__interfaces" aria-hidden="true">
      <div class="scene__monitor">
        <span class="scene__monitor-title">Orbital telemetry</span>
        <span class="scene__monitor-line scene__monitor-line--long" />
        <span class="scene__monitor-line" />
        <span class="scene__monitor-line scene__monitor-line--short" />
      </div>

      <div class="scene__radar">
        <span class="scene__radar-ring scene__radar-ring--inner" />
        <span class="scene__radar-axis scene__radar-axis--horizontal" />
        <span class="scene__radar-axis scene__radar-axis--vertical" />
        <span class="scene__radar-sweep" />
        <span class="scene__radar-contact" />
      </div>

      <div class="scene__signal">
        <span />
        <span />
        <span />
        <span />
      </div>

      <span class="scene__beacon" />
      <span class="scene__scan-line" />

      <div class="scene__craft">
        <span />
      </div>
    </div>

    <div class="scene__layer scene__foreground" aria-hidden="true">
      <div class="scene__console">
        <div class="scene__console-cluster">
          <span class="scene__console-light scene__console-light--primary" />
          <span class="scene__console-light" />
          <span class="scene__console-light" />
        </div>
        <div class="scene__console-display">
          <span />
          <span />
          <span />
        </div>
        <div class="scene__insignia">
          <span />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.command-scene {
  position: relative;
  isolation: isolate;
  width: 100%;
  min-height: 27rem;
  overflow: hidden;
  border-block: 1px solid var(--ge-border);
  background: var(--ge-bg-deep);
  box-shadow:
    inset 0 1px rgb(255 255 255 / 0.04),
    inset 0 0 0 1px rgb(3 6 11 / 0.7),
    0 1.5rem 4rem rgb(3 6 11 / 0.35);
}

.scene__caption {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  z-index: 8;
  display: flex;
  min-height: 2.5rem;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding-inline: 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  background: var(--ge-surface);
  color: var(--ge-text-muted);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
  font-weight: 600;
  text-transform: uppercase;
}

.scene__layer {
  position: absolute;
  inset: 2.5rem 0 0;
}

.scene__backdrop {
  overflow: hidden;
  background: #050914;
}

.scene__stars span {
  position: absolute;
  width: 2px;
  height: 2px;
  background: var(--ge-text-muted);
  opacity: 0.55;
}

.scene__stars span:nth-child(1) { top: 8%; left: 9%; }
.scene__stars span:nth-child(2) { top: 18%; left: 27%; width: 1px; height: 1px; }
.scene__stars span:nth-child(3) { top: 11%; left: 52%; }
.scene__stars span:nth-child(4) { top: 27%; left: 71%; width: 1px; height: 1px; }
.scene__stars span:nth-child(5) { top: 39%; left: 13%; }
.scene__stars span:nth-child(6) { top: 48%; left: 38%; width: 1px; height: 1px; }
.scene__stars span:nth-child(7) { top: 58%; left: 83%; }
.scene__stars span:nth-child(8) { top: 69%; left: 23%; width: 1px; height: 1px; }
.scene__stars span:nth-child(9) { top: 78%; left: 59%; }
.scene__stars span:nth-child(10) { top: 16%; left: 89%; }
.scene__stars span:nth-child(11) { top: 33%; left: 46%; width: 1px; height: 1px; }
.scene__stars span:nth-child(12) { top: 72%; left: 93%; width: 1px; height: 1px; }
.scene__stars span:nth-child(13) { top: 51%; left: 66%; }
.scene__stars span:nth-child(14) { top: 86%; left: 7%; }
.scene__stars span:nth-child(15) { top: 6%; left: 39%; width: 1px; height: 1px; }
.scene__stars span:nth-child(16) { top: 63%; left: 48%; width: 1px; height: 1px; }
.scene__stars span:nth-child(17) { top: 24%; left: 17%; }
.scene__stars span:nth-child(18) { top: 43%; left: 94%; width: 1px; height: 1px; }

.scene__planet {
  position: absolute;
  top: 16%;
  right: -31%;
  width: 102%;
  aspect-ratio: 1;
  overflow: hidden;
  border: 2px solid #355269;
  border-radius: 50%;
  background: #173344;
  box-shadow:
    inset 1.25rem 0 #1e4653,
    inset -4.5rem -1.5rem #08101b,
    0 0 0 1px rgb(79 209 197 / 0.12),
    0 0 3rem rgb(79 209 197 / 0.08);
}

.scene__planet-shadow {
  position: absolute;
  top: -8%;
  right: -1%;
  width: 66%;
  height: 116%;
  border-left: 1px solid #436478;
  border-radius: 50%;
  background: #060b13;
}

.scene__planet-marker {
  position: absolute;
  top: 28%;
  left: 31%;
  width: 0.5rem;
  height: 0.5rem;
  border: 1px solid var(--ge-accent-info);
  background: var(--ge-bg-deep);
  box-shadow: 0 0 0 0.25rem rgb(79 209 197 / 0.08);
}

.scene__moon {
  position: absolute;
  top: 23%;
  left: 11%;
  width: 6%;
  min-width: 1.25rem;
  aspect-ratio: 1;
  border: 1px solid var(--ge-border);
  border-radius: 50%;
  background: #283141;
  box-shadow: inset -0.4rem -0.25rem #111722;
}

.scene__orbit {
  position: absolute;
  z-index: 2;
  border: 1px solid var(--ge-border);
  border-radius: 50%;
  opacity: 0.72;
}

.scene__orbit--outer {
  top: 37%;
  right: -27%;
  width: 118%;
  height: 34%;
  border-right-color: var(--ge-accent-info);
  transform: rotate(-16deg);
}

.scene__orbit--inner {
  top: 48%;
  right: -11%;
  width: 86%;
  height: 22%;
  border-left-color: var(--ge-accent-secondary);
  transform: rotate(13deg);
}

.scene__structure {
  z-index: 4;
  pointer-events: none;
}

.scene__beam,
.scene__brace {
  position: absolute;
  display: block;
  background: var(--ge-surface-raised);
  box-shadow: inset 0 1px rgb(255 255 255 / 0.04), inset 0 0 0 1px var(--ge-border-muted);
}

.scene__beam--top {
  top: 0;
  right: 0;
  left: 0;
  height: 0.875rem;
  border-bottom: 1px solid var(--ge-border);
}

.scene__beam--left,
.scene__beam--right {
  top: 0;
  bottom: 19%;
  width: 1rem;
}

.scene__beam--left { left: 0; border-right: 1px solid var(--ge-border); }
.scene__beam--right { right: 0; border-left: 1px solid var(--ge-border); }

.scene__brace {
  bottom: 17%;
  width: 22%;
  height: 3rem;
}

.scene__brace--left {
  left: -2%;
  clip-path: polygon(0 0, 74% 0, 100% 100%, 0 100%);
}

.scene__brace--right {
  right: -2%;
  clip-path: polygon(26% 0, 100% 0, 100% 100%, 0 100%);
}

.scene__interfaces {
  z-index: 5;
}

.scene__monitor {
  position: absolute;
  top: 12%;
  left: 8%;
  display: grid;
  width: 34%;
  max-width: 9rem;
  gap: 0.375rem;
  padding: 0.625rem;
  border: 1px solid var(--ge-border);
  background: rgb(7 11 19 / 0.88);
  box-shadow: inset 0 0 0 1px var(--ge-bg-deep);
}

.scene__monitor-title {
  color: var(--ge-accent-info);
  font-family: var(--ge-font-telemetry);
  font-size: 0.5rem;
  text-transform: uppercase;
}

.scene__monitor-line {
  display: block;
  width: 67%;
  height: 2px;
  background: var(--ge-border);
}

.scene__monitor-line--long { width: 100%; background: var(--ge-accent-info); }
.scene__monitor-line--short { width: 42%; }

.scene__radar {
  position: absolute;
  bottom: 17%;
  left: 9%;
  width: 29%;
  max-width: 8rem;
  aspect-ratio: 1;
  overflow: hidden;
  border: 1px solid var(--ge-accent-info);
  border-radius: 50%;
  background: #071216;
  box-shadow: inset 0 0 0 0.4rem var(--ge-bg-deep);
}

.scene__radar-ring,
.scene__radar-axis,
.scene__radar-sweep,
.scene__radar-contact {
  position: absolute;
  display: block;
}

.scene__radar-ring--inner {
  inset: 24%;
  border: 1px solid var(--ge-border);
  border-radius: 50%;
}

.scene__radar-axis--horizontal {
  top: 50%;
  right: 8%;
  left: 8%;
  height: 1px;
  background: var(--ge-border-muted);
}

.scene__radar-axis--vertical {
  top: 8%;
  bottom: 8%;
  left: 50%;
  width: 1px;
  background: var(--ge-border-muted);
}

.scene__radar-sweep {
  top: 50%;
  left: 50%;
  width: 42%;
  height: 1px;
  background: var(--ge-accent-info);
  transform-origin: left center;
  animation: ge-radar-sweep 12s linear infinite;
  animation-play-state: paused;
}

.scene__radar-contact {
  top: 31%;
  right: 24%;
  width: 0.25rem;
  height: 0.25rem;
  background: var(--ge-accent-primary);
}

.scene__signal {
  position: absolute;
  top: 14%;
  right: 8%;
  display: flex;
  height: 2.75rem;
  align-items: end;
  gap: 0.25rem;
  padding: 0.5rem;
  border: 1px solid var(--ge-border-muted);
  background: rgb(7 11 19 / 0.8);
}

.scene__signal span {
  width: 0.25rem;
  background: var(--ge-accent-success);
}

.scene__signal span:nth-child(1) { height: 25%; }
.scene__signal span:nth-child(2) { height: 45%; }
.scene__signal span:nth-child(3) { height: 68%; }
.scene__signal span:nth-child(4) { height: 100%; }

.scene__beacon {
  position: absolute;
  top: 11%;
  right: 27%;
  width: 0.5rem;
  height: 0.5rem;
  border: 1px solid var(--ge-accent-primary);
  background: var(--ge-accent-primary);
  animation: ge-beacon 2.8s steps(2, end) infinite;
  animation-play-state: paused;
}

.scene__scan-line {
  position: absolute;
  top: 19%;
  right: 8%;
  left: 8%;
  height: 1px;
  background: var(--ge-accent-info);
  opacity: 0;
}

.scene__craft {
  position: absolute;
  top: 29%;
  left: -12%;
  display: none;
  width: 2.75rem;
  height: 0.75rem;
  background: var(--ge-text-muted);
  clip-path: polygon(0 45%, 62% 0, 100% 35%, 67% 68%, 28% 100%);
  opacity: 0;
}

.scene__craft span {
  position: absolute;
  top: 40%;
  left: 8%;
  width: 0.5rem;
  height: 2px;
  background: var(--ge-accent-primary);
}

.scene__foreground {
  z-index: 6;
  pointer-events: none;
}

.scene__console {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  display: grid;
  height: 20%;
  min-height: 5rem;
  grid-template-columns: 1fr 1.4fr 1fr;
  align-items: center;
  gap: 1rem;
  padding: 0.875rem 1.5rem;
  border-top: 1px solid var(--ge-border);
  background: var(--ge-surface-raised);
  box-shadow: inset 0 1px rgb(255 255 255 / 0.04), inset 0 1rem 2rem rgb(3 6 11 / 0.28);
}

.scene__console-cluster,
.scene__console-display {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.scene__console-light {
  width: 0.5rem;
  height: 0.5rem;
  border: 1px solid var(--ge-border);
  background: var(--ge-accent-success);
}

.scene__console-light--primary {
  background: var(--ge-accent-primary);
}

.scene__console-display {
  height: 2.25rem;
  justify-content: center;
  border: 1px solid var(--ge-border-muted);
  background: var(--ge-bg-deep);
}

.scene__console-display span {
  width: 18%;
  height: 2px;
  background: var(--ge-accent-info);
}

.scene__insignia {
  position: relative;
  justify-self: end;
  width: 2.25rem;
  height: 2.25rem;
  border: 1px solid var(--ge-border);
  transform: rotate(45deg);
}

.scene__insignia::before,
.scene__insignia::after,
.scene__insignia span {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  background: var(--ge-accent-primary);
  transform: translate(-50%, -50%);
}

.scene__insignia::before { width: 1rem; height: 2px; }
.scene__insignia::after { width: 2px; height: 1rem; }
.scene__insignia span { width: 0.3rem; height: 0.3rem; background: var(--ge-accent-info); }

.is-motion-active .scene__radar-sweep,
.is-motion-active .scene__beacon {
  animation-play-state: running;
}

@keyframes ge-radar-sweep {
  to { transform: rotate(1turn); }
}

@keyframes ge-beacon {
  0%, 34% { opacity: 0.25; }
  35%, 50% { opacity: 1; }
  51%, 100% { opacity: 0.25; }
}

@keyframes ge-star-pulse {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.85; }
}

@keyframes ge-orbit-outer {
  from { transform: rotate(-16deg); }
  to { transform: rotate(344deg); }
}

@keyframes ge-orbit-inner {
  from { transform: rotate(13deg); }
  to { transform: rotate(-347deg); }
}

@keyframes ge-console-light {
  0%, 68%, 100% { opacity: 0.32; }
  69%, 78% { opacity: 1; }
}

@keyframes ge-craft-pass {
  0%, 73% { transform: translateX(0); opacity: 0; }
  76% { opacity: 0.7; }
  91% { opacity: 0.7; }
  96%, 100% { transform: translateX(34rem); opacity: 0; }
}

@media (min-width: 48rem) {
  .command-scene {
    min-height: 25rem;
    aspect-ratio: 16 / 7;
  }

  .scene__planet {
    top: 2%;
    right: 4%;
    width: 56%;
  }

  .scene__orbit--outer {
    top: 29%;
    right: -2%;
    width: 72%;
  }

  .scene__orbit--inner {
    top: 44%;
    right: 8%;
    width: 55%;
  }

  .scene__monitor {
    top: 16%;
    left: 6%;
    width: 22%;
  }

  .scene__radar {
    left: 7%;
    width: 18%;
  }

  .scene__stars span:nth-child(3) {
    animation: ge-star-pulse 5.8s steps(3, end) infinite;
    animation-play-state: paused;
  }

  .scene__beacon {
    animation: none;
  }

  .scene__orbit--outer {
    animation: ge-orbit-outer 42s linear infinite;
    animation-play-state: paused;
  }

  .scene__console-light:nth-child(2) {
    animation: ge-console-light 4.6s steps(2, end) infinite;
    animation-play-state: paused;
  }

  .is-motion-active .scene__stars span:nth-child(3),
  .is-motion-active .scene__orbit--outer,
  .is-motion-active .scene__console-light:nth-child(2) {
    animation-play-state: running;
  }
}

@media (min-width: 75rem) {
  .command-scene {
    min-height: 42rem;
    aspect-ratio: auto;
    border: 1px solid var(--ge-border);
    clip-path: polygon(0 0, calc(100% - 1rem) 0, 100% 1rem, 100% 100%, 0 100%);
  }

  .scene__planet {
    top: 15%;
    right: -26%;
    width: 98%;
  }

  .scene__orbit--outer {
    top: 39%;
    right: -23%;
    width: 112%;
  }

  .scene__orbit--inner {
    top: 50%;
    right: -8%;
    width: 82%;
  }

  .scene__monitor {
    top: 13%;
    left: 7%;
    width: 28%;
  }

  .scene__radar {
    left: 8%;
    width: 26%;
  }

  .scene__orbit--inner {
    animation: ge-orbit-inner 51s linear infinite;
    animation-play-state: paused;
  }

  .scene__craft {
    display: block;
    animation: ge-craft-pass 29s linear infinite;
    animation-play-state: paused;
  }

  .is-motion-active .scene__orbit--inner,
  .is-motion-active .scene__craft {
    animation-play-state: running;
  }
}

@media (prefers-reduced-motion: reduce) {
  .command-scene * {
    animation: none !important;
  }
}
</style>
