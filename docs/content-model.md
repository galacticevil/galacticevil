# Content model

Content starts as typed local TypeScript. Do not invent client names, testimonials, outcomes, dates, links, or metrics.

## Site configuration

```ts
interface SiteConfig {
  brandName: string
  personName: string
  domain: string
  email?: string
  location: string
  operatingRegions: string[]
  availabilityStatus: 'available' | 'limited' | 'unavailable'
  linkedinUrl?: string
  githubUrl?: string
  resumeUrl?: string
}
```

Unconfirmed links remain absent and must not render.

## Capability

```ts
interface Capability {
  id: string
  order: number
  title: string
  shortTitle?: string
  summary: string
  description: string
  icon: string
  accent: 'orange' | 'cyan' | 'violet' | 'green'
  services: string[]
  primaryEngagement: boolean
}
```

## Mission file

```ts
interface MissionFile {
  slug: string
  missionId: string
  title: string
  clientName?: string
  clientDisplayName: string
  confidential: boolean
  sector: string
  role: string
  engagementType: string
  summary: string
  situation: string[]
  constraints: string[]
  actions: string[]
  outcomes: string[]
  technologies: string[]
  featured: boolean
  order: number
  coverImage: string
  socialImage: string
  publishedAt?: string
}
```

Confidential work uses a truthful generic display name. Outcomes may be qualitative when verified metrics are unavailable.

## Supporting models

Add typed models for navigation, engagement models, technology groups, testimonials, and page metadata. Testimonials require confirmed publication permission. Technology presentation uses categories such as primary, experienced, and working knowledge—not percentage skill bars.

Every route defines a unique title, description, canonical path, and social image. Mission metadata derives from its source record.

## Homepage sequence

1. Hero
2. Capabilities
3. Selected mission files
4. Evidence and experience
5. Engagement models
6. About Rory
7. Contact
8. Footer

