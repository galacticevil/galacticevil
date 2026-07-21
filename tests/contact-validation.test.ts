import assert from 'node:assert/strict'
import test from 'node:test'
import {
  buildContactPayload,
  createContactForm,
  validateContactForm,
  type ContactFormValues
} from '../app/utils/contact.ts'

function validForm(): ContactFormValues {
  return {
    ...createContactForm(),
    name: 'Rory Cottle',
    email: 'rory@example.test',
    engagementType: 'fractional-cto',
    projectSummary: 'We need senior technical leadership while preparing a product team for its next stage.',
    timing: 'one-to-three-months'
  }
}

test('accepts a complete enquiry with optional fields omitted', () => {
  assert.deepEqual(validateContactForm(validForm()), {})
})

test('reports every missing required field in one pass', () => {
  const errors = validateContactForm(createContactForm())

  assert.deepEqual(Object.keys(errors), [
    'name',
    'email',
    'engagementType',
    'projectSummary',
    'timing'
  ])
})

test('rejects malformed email, short context, and unsupported options', () => {
  const values = validForm()
  values.email = 'not-an-email'
  values.engagementType = 'unsupported' as ContactFormValues['engagementType']
  values.projectSummary = 'Too short.'
  values.timing = 'unsupported' as ContactFormValues['timing']

  const errors = validateContactForm(values)

  assert.match(errors.email ?? '', /valid email/)
  assert.match(errors.engagementType ?? '', /Select/)
  assert.match(errors.projectSummary ?? '', /40 characters/)
  assert.match(errors.timing ?? '', /Select/)
})

test('normalizes surrounding whitespace in the submission payload', () => {
  const values = validForm()
  values.name = '  Rory Cottle  '
  values.company = '  Galactic Evil  '

  const payload = buildContactPayload(values, '9c6a5041-72cb-4e4e-a6c2-b98b9b74ab5c')

  assert.equal(payload.name, 'Rory Cottle')
  assert.equal(payload.company, 'Galactic Evil')
  assert.equal(payload.submissionToken, '9c6a5041-72cb-4e4e-a6c2-b98b9b74ab5c')
})
