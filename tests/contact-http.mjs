import assert from 'node:assert/strict'

const apiUrl = process.env.CONTACT_API_URL || 'http://127.0.0.1:8081/api/contact.php'
const mailpitUrl = process.env.MAILPIT_URL || 'http://127.0.0.1:8025'

async function responseJson(response) {
  const body = await response.json()
  assert.equal(response.headers.get('cache-control')?.includes('no-store'), true)
  return body
}

async function mailpitMessages() {
  const response = await fetch(`${mailpitUrl}/api/v1/messages`, { cache: 'no-store' })
  assert.equal(response.ok, true, 'Mailpit API must be available')
  return response.json()
}

async function waitForMailCount(expected) {
  for (let attempt = 0; attempt < 20; attempt++) {
    const messages = await mailpitMessages()
    if (messages.total >= expected) return messages
    await new Promise(resolve => setTimeout(resolve, 100))
  }

  throw new Error(`Mailpit did not reach ${expected} messages`)
}

const token = crypto.randomUUID()
const validPayload = {
  name: 'Local Verification',
  email: 'sender@example.test',
  company: 'Galactic Evil Test',
  location: 'Johannesburg / SAST',
  engagementType: 'contract-engineering',
  projectSummary: 'This local-only enquiry verifies the complete Apache to Mailpit contact delivery path.',
  timing: 'one-to-three-months',
  budgetContext: 'Not established for this local verification.',
  website: '',
  submissionToken: token
}

const baseline = (await mailpitMessages()).total

const methodResponse = await fetch(apiUrl)
assert.equal(methodResponse.status, 405)
assert.equal(methodResponse.headers.get('allow'), 'POST')
assert.equal((await responseJson(methodResponse)).code, 'method_not_allowed')

const contentTypeResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'text/plain' },
  body: '{}'
})
assert.equal(contentTypeResponse.status, 415)
assert.equal((await responseJson(contentTypeResponse)).code, 'unsupported_media_type')

const malformedJsonResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: '{'
})
assert.equal(malformedJsonResponse.status, 400)
assert.equal((await responseJson(malformedJsonResponse)).code, 'invalid_json')

const validationResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({})
})
assert.equal(validationResponse.status, 422)
const validationBody = await responseJson(validationResponse)
assert.equal(validationBody.code, 'validation_failed')
assert.equal(typeof validationBody.errors.email, 'string')

const honeypotResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ ...validPayload, website: 'https://spam.example' })
})
assert.equal(honeypotResponse.status, 202)
assert.equal((await responseJson(honeypotResponse)).code, 'accepted')
assert.equal((await mailpitMessages()).total, baseline)

const successResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(validPayload)
})
assert.equal(successResponse.status, 202)
assert.equal((await responseJson(successResponse)).code, 'accepted')

const delivered = await waitForMailCount(baseline + 1)
assert.equal(delivered.messages[0]?.Subject, '[Galactic Evil] New project enquiry')

const duplicateResponse = await fetch(apiUrl, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(validPayload)
})
assert.equal(duplicateResponse.status, 200)
assert.equal((await responseJson(duplicateResponse)).code, 'already_received')
assert.equal((await mailpitMessages()).total, baseline + 1)

console.log('PASS contact HTTP protocol and Mailpit delivery')
