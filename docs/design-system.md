# Visual and responsive system

## Creative direction

A polished modern portfolio wearing the visual language of a lost 1990s SVGA science-fiction adventure game: painted environments, console panels, orbital diagrams, sparse pixel details, understated texture, and quiet ambient life.

The browser viewport is the environment. There is no continuous outer page border. Internal navigation, scene windows, telemetry strips, monitors, and panels provide framing.

The approved desktop concept remains the visual north star. Do not replace it with generic SaaS cards, neon cyberpunk, terminal cosplay, or stock Material styling.

## Device compositions

- Desktop: orbital command headquarters, panoramic scene, layered environment, peripheral telemetry.
- Tablet: reduced command station, content above a wide scenic panel, fewer animated layers.
- Mobile: secure field terminal, single column, content before artwork, portrait crop, full-width actions, minimal ambient motion.

Use CSS for layout changes. JavaScript viewport checks are reserved for behaviour such as animation cost or canvas resolution. Essential content must never be hidden on mobile.

## Hero hierarchy

1. Brand and navigation
2. `GALACTIC EVIL // EARTH OPERATIONS`
3. Headline
4. Professional introduction
5. International availability
6. Primary and secondary calls to action
7. Scene
8. Credibility or operational telemetry

Desktop uses the full working headline. Mobile may use “Technical leadership for ambitious software,” with the planetary phrase moved to supporting copy.

## Working tokens

```css
:root {
  color-scheme: dark;
  --ge-bg: #070b13;
  --ge-bg-deep: #03060b;
  --ge-surface: #0d1420;
  --ge-surface-raised: #111b2a;
  --ge-surface-active: #1a283a;
  --ge-border: #344156;
  --ge-border-muted: #202b3c;
  --ge-text-heading: #e2cfab;
  --ge-text-body: #c7b79a;
  --ge-text-muted: #8d95a3;
  --ge-accent-primary: #ff6a3d;
  --ge-accent-info: #4fd1c5;
  --ge-accent-success: #93d65a;
  --ge-accent-secondary: #a06cd5;
  --ge-accent-danger: #f04444;
  --ge-radius-xs: 2px;
  --ge-radius-sm: 4px;
  --ge-radius-md: 8px;
}
```

Provisional type families:

- Display: Tektur
- Body: IBM Plex Sans
- Telemetry: IBM Plex Mono

Use an 8px spacing rhythm with 4px optical adjustments. Text containers stay constrained while scenes and environmental backgrounds may extend edge to edge.

Panels should feel inset into machinery: restrained radii, structural keylines, hard inset shadows, one-pixel highlights, and occasional clipped corners. Glows strengthen during interaction rather than remaining permanently bright.

## Scene construction

Prefer layered assets over one monolithic desktop image:

- backdrop: stars, planet, distant structures
- structure: window, bulkheads, console
- interfaces: monitor, lights, radar
- rare events: passing craft or static interruption
- foreground: insignia and controls

Every layer needs a useful static state. Provide dedicated mobile and desktop artwork or crops; do not make phones download a full panoramic scene unnecessarily.

