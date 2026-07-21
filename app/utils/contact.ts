export const engagementOptions = [
  { value: 'fractional-cto', label: 'Fractional CTO' },
  { value: 'contract-engineering', label: 'Contract Engineering' },
  { value: 'architecture-advisory', label: 'Architecture & Advisory' },
  { value: 'project-rescue', label: 'Project Rescue' },
  { value: 'not-sure', label: 'Not sure yet' }
] as const

export const timingOptions = [
  { value: 'as-soon-as-practical', label: 'As soon as practical' },
  { value: 'one-to-three-months', label: 'Within 1-3 months' },
  { value: 'three-to-six-months', label: 'Within 3-6 months' },
  { value: 'exploring', label: 'Exploring for later' }
] as const

export type EngagementType = typeof engagementOptions[number]['value']
export type ProjectTiming = typeof timingOptions[number]['value']

export type ContactFormValues = {
  name: string
  email: string
  company: string
  location: string
  engagementType: '' | EngagementType
  projectSummary: string
  timing: '' | ProjectTiming
  budgetContext: string
  website: string
}

export type ContactField = keyof Omit<ContactFormValues, 'website'>
export type ContactErrors = Partial<Record<ContactField, string>>

export type ContactPayload = ContactFormValues & {
  submissionToken: string
}

export const contactLimits = {
  name: 100,
  email: 254,
  company: 120,
  location: 120,
  projectSummary: 4000,
  budgetContext: 500
} as const

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const engagementValues = new Set<string>(engagementOptions.map(option => option.value))
const timingValues = new Set<string>(timingOptions.map(option => option.value))

export function createContactForm(): ContactFormValues {
  return {
    name: '',
    email: '',
    company: '',
    location: '',
    engagementType: '',
    projectSummary: '',
    timing: '',
    budgetContext: '',
    website: ''
  }
}

export function validateContactForm(values: ContactFormValues): ContactErrors {
  const errors: ContactErrors = {}
  const name = values.name.trim()
  const email = values.email.trim()
  const summary = values.projectSummary.trim()

  if (name.length < 2) {
    errors.name = 'Enter your name using at least 2 characters.'
  } else if (name.length > contactLimits.name) {
    errors.name = `Keep your name under ${contactLimits.name} characters.`
  }

  if (!email) {
    errors.email = 'Enter your email address.'
  } else if (email.length > contactLimits.email || !emailPattern.test(email)) {
    errors.email = 'Enter a valid email address.'
  }

  if (values.company.trim().length > contactLimits.company) {
    errors.company = `Keep the organization name under ${contactLimits.company} characters.`
  }

  if (values.location.trim().length > contactLimits.location) {
    errors.location = `Keep the location or timezone under ${contactLimits.location} characters.`
  }

  if (!engagementValues.has(values.engagementType)) {
    errors.engagementType = 'Select the type of engagement you have in mind.'
  }

  if (summary.length < 40) {
    errors.projectSummary = 'Share at least 40 characters so there is enough context to respond.'
  } else if (summary.length > contactLimits.projectSummary) {
    errors.projectSummary = `Keep the project summary under ${contactLimits.projectSummary} characters.`
  }

  if (!timingValues.has(values.timing)) {
    errors.timing = 'Select an approximate timing.'
  }

  if (values.budgetContext.trim().length > contactLimits.budgetContext) {
    errors.budgetContext = `Keep the budget context under ${contactLimits.budgetContext} characters.`
  }

  return errors
}

export function buildContactPayload(values: ContactFormValues, submissionToken: string): ContactPayload {
  return {
    name: values.name.trim(),
    email: values.email.trim(),
    company: values.company.trim(),
    location: values.location.trim(),
    engagementType: values.engagementType,
    projectSummary: values.projectSummary.trim(),
    timing: values.timing,
    budgetContext: values.budgetContext.trim(),
    website: values.website,
    submissionToken
  }
}
