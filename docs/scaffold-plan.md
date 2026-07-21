# Scaffold plan

The scaffold begins only after explicit user approval.

## Phase 1: foundation

1. Confirm the local Node/package-manager baseline and current stable compatible packages.
2. Create a Nuxt TypeScript application in the workspace without overwriting synced project material.
3. Add Nuxt UI, Tailwind integration, and VueUse.
4. Configure static generation and deterministic placeholder routes.
5. Add global tokens, typography hooks, and a minimal responsive shell.
6. Add typed placeholder data and route metadata helpers.
7. Establish formatting, linting, type checks, unit tests, and production generation.
8. Add Docker Compose for PHP/Apache plus a safe development mail path.
9. Add a production-like assembled Apache preview.
10. Verify commands and document the local workflow.

The foundation deliberately excludes final scene artwork and elaborate homepage design.

## Phase 2: design primitives

Implement and review `GEButton`, `GEPanel`, `GELabel`, `GEStatusBadge`, container/section primitives, typography, focus states, and panel treatments.

## Phase 3: responsive hero

Build header, content, CTAs, layered placeholder scene, telemetry, reduced-motion state, and lifecycle controls across desktop, tablet, and mobile. Compare visually with the approved desktop concept.

## Phase 4: first transition

Add structured capability data, responsive capability panels, and the transition out of the hero. This proves the design system works beyond a single scene.

## Later phases

- Mission files and remaining pages
- Final content and artwork
- SEO, structured data, sitemap, and social cards
- PHP form implementation and security hardening
- Accessibility, visual regression, and performance refinement
- SureSupport deployment package and staging verification

## Scaffold acceptance criteria

- Nuxt runs locally and generates successfully
- TypeScript, Nuxt UI, Tailwind, and VueUse are configured
- All placeholder routes are generated and directly loadable
- Brand tokens load globally
- Minimal shell works at mobile and desktop widths
- PHP/Apache starts locally through Docker
- Production-like preview serves generated routes and PHP together
- Type, lint, unit, generation, and smoke checks pass
- No production Node server is required
- No final visual design has been improvised prematurely

