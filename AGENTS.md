# Galactic Evil repository instructions

The existing project-mirror `AGENTS.md` remains untouched.

## Objective

Build Galactic Evil, Rory Cottle's professional portfolio, according to the approved project documents in `/docs`.

## Required architecture

Use Nuxt, Vue 3, TypeScript, selective Nuxt UI, Tailwind CSS, VueUse, static generation, and minimal PHP. Production must not require Node. Do not add PWA behaviour, a runtime database, or essential Nitro endpoints without approval.

## Working rules

- Read relevant docs before changing code.
- Keep each task to its requested vertical slice.
- Inspect existing patterns before adding new ones.
- Use typed props, emits, composables, and content; avoid `any`.
- Prefix reusable brand primitives with `GE`; do not prefix every page component.
- Use CSS for responsive layout and JavaScript only for behavioural needs.
- Treat CSS variables as the brand-token source of truth.
- Wrap or theme Nuxt UI so stock styling is not visible.
- Preserve accessibility, reduced motion, static metadata, and mobile content parity.
- Do not invent client facts, outcomes, testimonials, contact details, or profile links.
- Do not add dependencies for trivial problems.
- Do not redesign approved brand decisions.

## Versioning and commits

- Use Conventional Commits for every commit: `<type>(optional-scope): <imperative summary>`.
- Preferred types are `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `build`, `ci`, `chore`, and `revert`.
- Keep commits focused. Do not combine unrelated work merely to reduce commit count.
- Mark breaking changes with `!` in the header and a `BREAKING CHANGE:` footer.
- Follow Semantic Versioning for releases: patch for backward-compatible fixes, minor for backward-compatible functionality, and major for breaking changes.
- While the project remains below `1.0.0`, treat incompatible public behaviour or content-model changes as deliberate versioning decisions rather than silently breaking them.
- Do not change the package version or create release tags unless the task explicitly includes a release.
- When suggesting a commit, include the exact Conventional Commit message in the completion report.

## Required task loop

1. Inspect instructions and relevant files.
2. State a short plan for substantial changes.
3. Implement only the scoped work.
4. Run applicable checks.
5. Inspect relevant viewport states for UI changes.
6. Report changed files, decisions, verification, and unresolved concerns.

## Definition of done

Work is done only when applicable type, lint, unit, generation, and smoke checks pass; the result is accessible and responsive; direct generated routes work; and no unapproved server or design assumptions were introduced.
