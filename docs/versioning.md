# Commits and versioning

## Conventional Commits

Use this structure:

```text
<type>(optional-scope): <imperative summary>
```

Examples:

```text
feat(hero): add responsive command-deck composition
fix(navigation): preserve focus after closing mobile menu
docs(architecture): document static deployment assembly
chore(deps): update Nuxt UI
```

Use `!` and a `BREAKING CHANGE:` footer for incompatible changes. Keep each commit focused and explain meaningful motivation in the body when the summary alone is insufficient.

## Semantic Versioning

- Patch: backward-compatible fixes and internal corrections.
- Minor: backward-compatible functionality or meaningful content capabilities.
- Major: incompatible public behaviour, interfaces, deployment expectations, or content schemas.

During initial development below `1.0.0`, version changes remain intentional release decisions. Individual implementation tasks must not bump the package version or create tags unless release preparation is explicitly in scope.

The lockfile is committed. Dependency-only commits normally use `chore(deps): ...` unless the update directly delivers a user-visible fix or feature.
