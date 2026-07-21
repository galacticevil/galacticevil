# Quality standards

## Accessibility

- Semantic landmarks and one clear `h1` per page
- Logical headings, visible focus, skip link, and keyboard-operable navigation
- Descriptive controls, explicit form labels, and useful validation messages
- State is never communicated through colour alone
- Text remains usable at 200% zoom
- Decorative scene assets have empty alternative text or are CSS backgrounds
- Meaningful imagery has concise alternative text
- Minimum touch target 44px; 48px preferred

## Motion

Ambient animation should resemble classic SVGA scenes: sparse, asynchronous, and subordinate to reading.

- Desktop: four to six small continuous loops; one rare event at a time
- Tablet: two to four loops; rare events optional
- Mobile: one to two loops; rare events disabled
- Reduced motion: polished static scene, no decorative loops

Prefer CSS keyframes, sprite sheets, transforms, opacity, Intersection Observer, and VueUse. Pause animation off-screen and while the document is hidden. Avoid layout-changing motion and do not add a general animation library initially.

## SEO and social

Every public route must be statically rendered with unique titles, descriptions, canonical URLs, Open Graph metadata, and social imagery. Include sitemap, robots rules, semantic internal links, and structured data for Rory as a Person and Galactic Evil as the professional brand.

Social cards should ultimately be route-specific, particularly for mission files. No essential copy may exist only inside images.

## Performance

- Critical text renders before scene assets
- Reserve image dimensions to prevent layout shift
- Use AVIF/WebP where practical and dedicated breakpoint variants
- Lazy-load below-the-fold imagery
- No autoplay video
- Limit fonts and weights
- Avoid large icon packages
- Initialise scene animation only when visible
- Wide displays gain environment rather than endlessly larger text

## Verification

Required checks should cover type checking, linting, unit tests, static generation, generated-output validation, and end-to-end smoke tests.

E2E coverage should include direct navigation to generated routes, the mobile menu, keyboard navigation, reduced motion, contact validation, metadata, not-found behaviour, and PHP response handling.

