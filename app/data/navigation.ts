export interface NavigationItem {
  label: string
  to: string
}

export const primaryNavigation: readonly NavigationItem[] = [
  { label: 'Capabilities', to: '/capabilities' },
  { label: 'Mission Files', to: '/mission-files' },
  { label: 'About', to: '/about' },
  { label: 'Technology', to: '/technology' },
  { label: 'Contact', to: '/contact' }
]
