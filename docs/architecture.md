# Technical architecture

## Frontend

- Nuxt, Vue 3, and TypeScript
- Static generation of all public routes
- Nuxt UI only where accessible interaction primitives save meaningful work
- Tailwind CSS for layout, spacing, visibility, and responsive utilities
- CSS variables as the single source of truth for brand tokens
- VueUse for visibility, reduced motion, document state, and animation lifecycle
- Typed local data initially; Nuxt Content remains optional later

No persistent Node process is required in production. Do not make essential content or metadata client-only.

## Production shape

```text
SureSupport / Apache
├── statically generated HTML, CSS, JavaScript, images, and fonts
└── api/
    └── minimal PHP endpoints
```

PHP initially handles contact and enquiry submission, validation, honeypot checks, rate limiting, spam mitigation, email delivery, safe logging, and structured JSON responses. Secrets never enter the frontend bundle or repository.

## Local development

Use Docker Compose for PHP/Apache and a development mail catcher or safe local mail sink. Nuxt runs its normal development server with a same-origin development proxy for `/api/*`.

Provide three workflows:

1. Live Nuxt development with PHP available through Docker.
2. Static generation verification.
3. A production-like Apache preview containing generated output plus PHP.

The production-like preview must test direct nested-route loads, static asset paths, metadata, custom errors, and PHP integration.

## Proposed application layout

```text
assets/css/
components/{brand,navigation,hero,scene,mission,capabilities,ui}/
composables/
data/
docs/
layouts/
pages/
public/{icons,images,scenes,social}/
php/
tests/{unit,e2e}/
```

The scaffold may adjust paths to match current Nuxt conventions, but must document any departure.

## Rendering constraints

Do not introduce:

- a production Node server
- essential Nitro APIs
- a runtime database
- a PWA or service worker
- metadata added only after hydration
- unofficial prerender machinery when native Nuxt generation suffices

All known mission routes must be generated deterministically. Unknown slugs must produce an appropriate not-found result rather than an empty client screen.

