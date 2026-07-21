export type CapabilityAccent = 'orange' | 'cyan' | 'violet' | 'green'

export interface Capability {
  id: string
  order: number
  title: string
  shortTitle?: string
  summary: string
  description: string
  icon: string
  accent: CapabilityAccent
  services: string[]
  primaryEngagement: boolean
  scope: string[]
  idealSituations: string[]
  likelyDeliverables: string[]
  contribution: string
}

export interface EngagementModel {
  id: string
  order: number
  title: string
  summary: string
  bestFor: string
}

export const capabilities = [
  {
    id: 'fractional-cto',
    order: 1,
    title: 'Fractional CTO',
    summary: 'Senior technical leadership without an immediate permanent executive hire.',
    description: 'Technical direction and engineering leadership for startups and product teams navigating growth, delivery, or change.',
    icon: 'i-lucide-orbit',
    accent: 'orange',
    services: ['Technical direction', 'Engineering leadership', 'Delivery planning'],
    primaryEngagement: true,
    scope: [
      'Set technical direction and near-term engineering priorities',
      'Align architecture, product constraints, and delivery plans',
      'Support engineering leadership, hiring decisions, and team structure',
      'Improve technical decision-making and delivery cadence'
    ],
    idealSituations: [
      'A founder-led team needs experienced technical leadership before making a permanent executive hire',
      'An existing engineering team needs clearer direction, priorities, or leadership support',
      'The company is between technical leaders or preparing for a new stage of product and team growth'
    ],
    likelyDeliverables: [
      'Technical priorities and decision records',
      'Current-state architecture and delivery risk assessment',
      'Planning, communication, and engineering operating approach',
      'Recommendations for technical roles and responsibilities'
    ],
    contribution: 'Rory works alongside founders and engineering leads, taking responsibility for technical judgment while staying close enough to delivery to keep recommendations practical.'
  },
  {
    id: 'contract-engineering',
    order: 2,
    title: 'Contract Engineering',
    summary: 'Hands-on senior engineering for software that needs experienced delivery.',
    description: 'Focused implementation and technical contribution within an existing product, platform, or engineering team.',
    icon: 'i-lucide-code-xml',
    accent: 'cyan',
    services: ['Product engineering', 'Technical delivery', 'Team collaboration'],
    primaryEngagement: true,
    scope: [
      'Implement product and platform work within an existing codebase',
      'Own complex features, integrations, and technical improvements',
      'Review, refactor, and strengthen maintainability where needed',
      'Collaborate with product, design, and engineering contributors'
    ],
    idealSituations: [
      'A team needs an experienced engineer for a defined product or platform initiative',
      'Delivery needs additional senior capacity without lowering the technical bar',
      'The work benefits from an engineer who can contribute in code and guide technical decisions'
    ],
    likelyDeliverables: [
      'Production code and appropriate automated tests',
      'Technical designs and implementation decision notes',
      'Code reviews and targeted refactoring recommendations',
      'Handover documentation and shared team context'
    ],
    contribution: 'Rory joins the team workflow, owns agreed technical work, communicates tradeoffs early, and leaves decisions and implementation context understandable to the people maintaining the system.'
  },
  {
    id: 'architecture-advisory',
    order: 3,
    title: 'Architecture & Advisory',
    summary: 'Clear system decisions for teams facing complexity, risk, or consequential change.',
    description: 'Independent architecture review and practical technical guidance grounded in product and delivery realities.',
    icon: 'i-lucide-network',
    accent: 'violet',
    services: ['Architecture review', 'Technical strategy', 'Decision support'],
    primaryEngagement: false,
    scope: [
      'Review existing systems, boundaries, dependencies, and constraints',
      'Evaluate architecture options and their practical tradeoffs',
      'Shape staged technical direction around product priorities',
      'Support consequential build, buy, migrate, or integration decisions'
    ],
    idealSituations: [
      'A team is approaching a major platform or architecture decision',
      'An existing system is accumulating complexity and the next step is unclear',
      'Founders or engineering leaders need an independent technical perspective'
    ],
    likelyDeliverables: [
      'Current-state architecture assessment',
      'Options and tradeoff record',
      'Target architecture or staged technical plan',
      'Risk, dependency, and decision notes'
    ],
    contribution: 'Rory turns technical ambiguity into explicit options, facilitates the decisions that matter, and keeps the resulting direction grounded in delivery capacity and product needs.'
  },
  {
    id: 'project-rescue',
    order: 4,
    title: 'Project Rescue',
    summary: 'Calm technical intervention when delivery or system stability has gone off course.',
    description: 'Structured assessment and hands-on recovery planning for difficult software projects and strained teams.',
    icon: 'i-lucide-life-buoy',
    accent: 'green',
    services: ['System assessment', 'Delivery recovery', 'Stabilization planning'],
    primaryEngagement: false,
    scope: [
      'Assess technical, delivery, and team constraints without assigning blame',
      'Identify immediate risks and the decisions blocking progress',
      'Prioritize stabilization, recovery, and maintainability work',
      'Provide targeted implementation or technical leadership during recovery'
    ],
    idealSituations: [
      'Delivery is slipping and the underlying causes are disputed or unclear',
      'System reliability or maintainability is obstructing normal product work',
      'A handover, leadership change, or stalled initiative has exposed technical uncertainty'
    ],
    likelyDeliverables: [
      'Recovery assessment and priority map',
      'Technical risk register and decision log',
      'Staged stabilization or recovery plan',
      'Targeted implementation, review, or leadership support'
    ],
    contribution: 'Rory brings an independent, calm assessment, works with the existing team to separate urgent issues from background noise, and helps turn the agreed recovery path into executable work.'
  }
] satisfies Capability[]

export const engagementModels = [
  {
    id: 'fractional-leadership',
    order: 1,
    title: 'Fractional leadership',
    summary: 'Ongoing part-time technical leadership with an agreed operating cadence and decision scope.',
    bestFor: 'Teams that need sustained technical direction without immediately adding a permanent executive.'
  },
  {
    id: 'contract-delivery',
    order: 2,
    title: 'Contract delivery',
    summary: 'Embedded senior engineering for an agreed period, initiative, or area of technical ownership.',
    bestFor: 'Teams that need hands-on delivery capacity and experienced technical judgment.'
  },
  {
    id: 'fixed-scope-advisory',
    order: 3,
    title: 'Fixed-scope advisory',
    summary: 'A bounded review or decision process with clear questions, inputs, and expected artifacts.',
    bestFor: 'Architecture choices, system reviews, and focused technical planning.'
  },
  {
    id: 'recovery-engagement',
    order: 4,
    title: 'Recovery engagement',
    summary: 'An initial assessment followed by an agreed stabilization or recovery scope.',
    bestFor: 'Projects where causes, priorities, and the safest next actions need to be established.'
  }
] satisfies EngagementModel[]
