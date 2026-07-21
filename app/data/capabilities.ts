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
    primaryEngagement: true
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
    primaryEngagement: false
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
    primaryEngagement: false
  },
  {
    id: 'project-rescue',
    order: 4,
    title: 'Project Rescue',
    summary: 'Calm technical intervention when delivery or system stability has gone off course.',
    description: 'Structured assessment and hands-on recovery planning for difficult software projects and strained teams.',
    icon: 'i-lucide-life-buoy',
    accent: 'green',
    services: ['System assessment', 'Delivery recovery', 'Stabilisation planning'],
    primaryEngagement: false
  }
] satisfies Capability[]
