<script setup lang="ts">
import {
  useDocumentVisibility,
  useElementVisibility,
  usePreferredReducedMotion
} from '@vueuse/core'
import {
  buildContactPayload,
  contactLimits,
  createContactForm,
  engagementOptions,
  timingOptions,
  validateContactForm,
  type ContactErrors,
  type ContactField
} from '~/utils/contact'

type TransmissionState = 'idle' | 'pending' | 'success' | 'error'

type ApiResponse = {
  ok?: boolean
  code?: string
  message?: string
  errors?: Record<string, string>
}

const fieldOrder: ContactField[] = [
  'name',
  'email',
  'company',
  'location',
  'engagementType',
  'projectSummary',
  'timing',
  'budgetContext'
]

const terminalRoot = ref<HTMLElement | null>(null)
const errorSummary = ref<HTMLElement | null>(null)
const form = reactive(createContactForm())
const errors = ref<ContactErrors>({})
const transmissionState = ref<TransmissionState>('idle')
const transmissionMessage = ref('Channel ready. Required fields are marked.')
const submissionToken = ref<string | null>(null)

const documentVisibility = useDocumentVisibility()
const terminalVisible = useElementVisibility(terminalRoot, { threshold: 0.05 })
const reducedMotion = usePreferredReducedMotion()
const isMotionActive = computed(() => (
  reducedMotion.value !== 'reduce'
  && documentVisibility.value === 'visible'
  && terminalVisible.value
))

const errorEntries = computed(() => fieldOrder
  .filter(field => errors.value[field])
  .map(field => ({ field, message: errors.value[field] as string })))

const statusLabel = computed(() => {
  if (transmissionState.value === 'pending') return 'Transmitting'
  if (transmissionState.value === 'success') return 'Received'
  if (transmissionState.value === 'error') return 'Interrupted'
  return 'Channel ready'
})

function fieldDescription(field: ContactField, instructionId?: string) {
  const ids = []
  if (instructionId) ids.push(instructionId)
  if (errors.value[field]) ids.push(`${field}-error`)
  return ids.length ? ids.join(' ') : undefined
}

function focusField(field: ContactField) {
  document.getElementById(field)?.focus()
}

function markEdited(field: ContactField) {
  if (errors.value[field]) {
    const nextErrors = { ...errors.value }
    delete nextErrors[field]
    errors.value = nextErrors
  }

  if (transmissionState.value === 'error') {
    transmissionState.value = 'idle'
    transmissionMessage.value = 'Channel ready. Required fields are marked.'
  }

  submissionToken.value = null
}

function createSubmissionToken() {
  if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
    return crypto.randomUUID()
  }

  return `${Date.now().toString(36)}-${Math.random().toString(36).slice(2)}-${Math.random().toString(36).slice(2)}`
}

function applyServerErrors(serverErrors: Record<string, string>) {
  const nextErrors: ContactErrors = {}

  for (const field of fieldOrder) {
    if (typeof serverErrors[field] === 'string') nextErrors[field] = serverErrors[field]
  }

  errors.value = nextErrors
}

async function submitEnquiry() {
  if (transmissionState.value === 'pending') return

  errors.value = validateContactForm(form)
  if (errorEntries.value.length) {
    transmissionState.value = 'error'
    transmissionMessage.value = 'Check the highlighted fields before transmitting.'
    await nextTick()
    errorSummary.value?.focus()
    return
  }

  submissionToken.value ??= createSubmissionToken()
  transmissionState.value = 'pending'
  transmissionMessage.value = 'Secure channel open. Transmitting enquiry now.'

  try {
    const response = await fetch('/api/contact.php', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(buildContactPayload(form, submissionToken.value)),
      cache: 'no-store',
      credentials: 'same-origin'
    })

    const result = await response.json().catch(() => ({})) as ApiResponse

    if (response.ok && result.ok) {
      transmissionState.value = 'success'
      transmissionMessage.value = result.message || 'Your enquiry has been received.'
      return
    }

    if (response.status === 422 && result.errors) {
      applyServerErrors(result.errors)
      transmissionMessage.value = 'Check the highlighted fields before transmitting.'
      transmissionState.value = 'error'
      await nextTick()
      errorSummary.value?.focus()
      return
    }

    transmissionState.value = 'error'
    transmissionMessage.value = result.message || 'The transmission could not be completed. Please retry.'
  } catch {
    transmissionState.value = 'error'
    transmissionMessage.value = 'The communications channel is unavailable. Please retry shortly.'
  }
}

function resetForm() {
  Object.assign(form, createContactForm())
  errors.value = {}
  submissionToken.value = null
  transmissionState.value = 'idle'
  transmissionMessage.value = 'Channel ready. Required fields are marked.'
  nextTick(() => focusField('name'))
}
</script>

<template>
  <section
    ref="terminalRoot"
    class="contact-terminal"
    :class="[`contact-terminal--${transmissionState}`, { 'is-motion-active': isMotionActive }]"
    :data-motion="isMotionActive ? 'active' : 'paused'"
    aria-labelledby="contact-terminal-title"
  >
    <header class="contact-terminal__masthead">
      <div class="contact-terminal__identity">
        <span class="contact-terminal__antenna" aria-hidden="true"><i /></span>
        <div>
          <span>Earth operations // Secure communications</span>
          <h2 id="contact-terminal-title">Project enquiry terminal</h2>
        </div>
      </div>

      <div class="contact-terminal__signal" aria-hidden="true">
        <span v-for="index in 13" :key="index" :style="{ '--signal-index': index }" />
      </div>

      <div class="contact-terminal__state" aria-hidden="true">
        <span class="contact-terminal__state-light" />
        <span>{{ statusLabel }}</span>
      </div>
    </header>

    <div class="contact-terminal__viewport">
      <div class="contact-terminal__channel-rail" aria-hidden="true">
        <span>CH-07</span>
        <i />
        <span>RX/TX</span>
      </div>

      <div class="contact-terminal__screen">
        <div class="contact-terminal__readout" role="status" aria-live="polite">
          <span class="contact-terminal__prompt" aria-hidden="true">&gt;</span>
          <span>{{ transmissionMessage }}</span>
          <span v-if="transmissionState === 'idle'" class="contact-terminal__cursor" aria-hidden="true" />
        </div>

        <div v-if="transmissionState === 'success'" class="contact-terminal__success" tabindex="-1">
          <span class="contact-terminal__success-mark" aria-hidden="true">
            <UIcon name="i-lucide-check" />
          </span>
          <div>
            <GELabel tone="success">Transmission complete</GELabel>
            <h3>Enquiry received.</h3>
            <p>
              Your project context is in the queue. Rory will review it and respond using the email address you provided.
            </p>
            <GEButton variant="quiet" @click="resetForm">
              <template #leading><UIcon name="i-lucide-rotate-ccw" /></template>
              Send another enquiry
            </GEButton>
          </div>
        </div>

        <form
          v-else
          class="contact-form"
          novalidate
          :aria-busy="transmissionState === 'pending'"
          @submit.prevent="submitEnquiry"
        >
          <div
            v-if="errorEntries.length"
            ref="errorSummary"
            class="contact-form__error-summary"
            role="alert"
            tabindex="-1"
          >
            <UIcon name="i-lucide-triangle-alert" aria-hidden="true" />
            <div>
              <h3>Transmission check required</h3>
              <ul>
                <li v-for="entry in errorEntries" :key="entry.field">
                  <a :href="`#${entry.field}`" @click.prevent="focusField(entry.field)">
                    {{ entry.message }}
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="contact-form__trap" aria-hidden="true">
            <label for="website">Website</label>
            <input id="website" v-model="form.website" name="website" type="text" tabindex="-1" autocomplete="off">
          </div>

          <fieldset class="contact-form__fieldset">
            <legend><span>01</span> Contact coordinates</legend>
            <div class="contact-form__grid">
              <div class="contact-form__field">
                <label for="name">Name <span aria-hidden="true">*</span></label>
                <input
                  id="name"
                  v-model="form.name"
                  name="name"
                  type="text"
                  autocomplete="name"
                  :maxlength="contactLimits.name"
                  required
                  :aria-invalid="errors.name ? 'true' : undefined"
                  :aria-describedby="fieldDescription('name')"
                  @input="markEdited('name')"
                >
                <p v-if="errors.name" id="name-error" class="contact-form__error">{{ errors.name }}</p>
              </div>

              <div class="contact-form__field">
                <label for="email">Email <span aria-hidden="true">*</span></label>
                <input
                  id="email"
                  v-model="form.email"
                  name="email"
                  type="email"
                  inputmode="email"
                  autocomplete="email"
                  :maxlength="contactLimits.email"
                  required
                  :aria-invalid="errors.email ? 'true' : undefined"
                  :aria-describedby="fieldDescription('email')"
                  @input="markEdited('email')"
                >
                <p v-if="errors.email" id="email-error" class="contact-form__error">{{ errors.email }}</p>
              </div>

              <div class="contact-form__field">
                <label for="company">Company or organization <span>(optional)</span></label>
                <input
                  id="company"
                  v-model="form.company"
                  name="company"
                  type="text"
                  autocomplete="organization"
                  :maxlength="contactLimits.company"
                  :aria-invalid="errors.company ? 'true' : undefined"
                  :aria-describedby="fieldDescription('company')"
                  @input="markEdited('company')"
                >
                <p v-if="errors.company" id="company-error" class="contact-form__error">{{ errors.company }}</p>
              </div>

              <div class="contact-form__field">
                <label for="location">Location or timezone <span>(optional)</span></label>
                <input
                  id="location"
                  v-model="form.location"
                  name="location"
                  type="text"
                  autocomplete="off"
                  :maxlength="contactLimits.location"
                  placeholder="e.g. New York / ET"
                  :aria-invalid="errors.location ? 'true' : undefined"
                  :aria-describedby="fieldDescription('location')"
                  @input="markEdited('location')"
                >
                <p v-if="errors.location" id="location-error" class="contact-form__error">{{ errors.location }}</p>
              </div>
            </div>
          </fieldset>

          <fieldset class="contact-form__fieldset">
            <legend><span>02</span> Mission briefing</legend>
            <div class="contact-form__grid">
              <div class="contact-form__field">
                <label for="engagementType">Engagement type <span aria-hidden="true">*</span></label>
                <select
                  id="engagementType"
                  v-model="form.engagementType"
                  name="engagementType"
                  required
                  :aria-invalid="errors.engagementType ? 'true' : undefined"
                  :aria-describedby="fieldDescription('engagementType')"
                  @change="markEdited('engagementType')"
                >
                  <option value="" disabled>Select an engagement</option>
                  <option v-for="option in engagementOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
                <p v-if="errors.engagementType" id="engagementType-error" class="contact-form__error">{{ errors.engagementType }}</p>
              </div>

              <div class="contact-form__field">
                <label for="timing">Approximate timing <span aria-hidden="true">*</span></label>
                <select
                  id="timing"
                  v-model="form.timing"
                  name="timing"
                  required
                  :aria-invalid="errors.timing ? 'true' : undefined"
                  :aria-describedby="fieldDescription('timing')"
                  @change="markEdited('timing')"
                >
                  <option value="" disabled>Select a timeframe</option>
                  <option v-for="option in timingOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
                <p v-if="errors.timing" id="timing-error" class="contact-form__error">{{ errors.timing }}</p>
              </div>

              <div class="contact-form__field contact-form__field--wide">
                <div class="contact-form__label-row">
                  <label for="projectSummary">Project summary <span aria-hidden="true">*</span></label>
                  <span aria-hidden="true">{{ form.projectSummary.length }}/{{ contactLimits.projectSummary }}</span>
                </div>
                <p id="projectSummary-instructions" class="contact-form__instruction">
                  Share the situation, constraints, and senior technical help you need.
                </p>
                <textarea
                  id="projectSummary"
                  v-model="form.projectSummary"
                  name="projectSummary"
                  rows="7"
                  :maxlength="contactLimits.projectSummary"
                  required
                  :aria-invalid="errors.projectSummary ? 'true' : undefined"
                  :aria-describedby="fieldDescription('projectSummary', 'projectSummary-instructions')"
                  @input="markEdited('projectSummary')"
                />
                <p v-if="errors.projectSummary" id="projectSummary-error" class="contact-form__error">{{ errors.projectSummary }}</p>
              </div>

              <div class="contact-form__field contact-form__field--wide">
                <label for="budgetContext">Budget context <span>(optional)</span></label>
                <p id="budgetContext-instructions" class="contact-form__instruction">
                  A range or procurement constraint is enough. Leave this blank if it is not established.
                </p>
                <textarea
                  id="budgetContext"
                  v-model="form.budgetContext"
                  name="budgetContext"
                  rows="3"
                  :maxlength="contactLimits.budgetContext"
                  :aria-invalid="errors.budgetContext ? 'true' : undefined"
                  :aria-describedby="fieldDescription('budgetContext', 'budgetContext-instructions')"
                  @input="markEdited('budgetContext')"
                />
                <p v-if="errors.budgetContext" id="budgetContext-error" class="contact-form__error">{{ errors.budgetContext }}</p>
              </div>
            </div>
          </fieldset>

          <footer class="contact-form__footer">
            <p>
              Submitted details are used only to review and respond to this enquiry.
            </p>
            <GEButton type="submit" size="large" :disabled="transmissionState === 'pending'">
              <template #leading>
                <UIcon :name="transmissionState === 'pending' ? 'i-lucide-loader-circle' : 'i-lucide-send'" />
              </template>
              {{ transmissionState === 'pending' ? 'Transmitting' : transmissionState === 'error' ? 'Retry transmission' : 'Transmit enquiry' }}
            </GEButton>
          </footer>
        </form>
      </div>
    </div>

    <footer class="contact-terminal__telemetry" aria-hidden="true">
      <span>Protocol // JSON-01</span>
      <span>Storage // None local</span>
      <span>Relay // Same origin</span>
    </footer>
  </section>
</template>

<style scoped>
.contact-terminal {
  position: relative;
  border-block: 1px solid var(--ge-border);
  background: var(--ge-surface);
  box-shadow: var(--ge-panel-shadow);
}

.contact-terminal::before,
.contact-terminal::after {
  content: "";
  position: absolute;
  z-index: 2;
  width: 1rem;
  height: 1rem;
  pointer-events: none;
}

.contact-terminal::before {
  top: -1px;
  left: 0;
  border-top: 2px solid var(--ge-accent-info);
  border-left: 2px solid var(--ge-accent-info);
}

.contact-terminal::after {
  right: 0;
  bottom: -1px;
  border-right: 2px solid var(--ge-accent-primary);
  border-bottom: 2px solid var(--ge-accent-primary);
}

.contact-terminal__masthead {
  display: grid;
  grid-template-columns: 1fr auto;
  align-items: center;
  gap: 0.75rem;
  min-height: 4.5rem;
  padding: 0.875rem 1rem;
  border-bottom: 1px solid var(--ge-border);
  background: var(--ge-surface-raised);
  box-shadow: inset 0 -1px var(--ge-bg-deep), inset 0 1px rgb(255 255 255 / 0.04);
}

.contact-terminal__identity {
  display: flex;
  min-width: 0;
  align-items: center;
  gap: 0.75rem;
}

.contact-terminal__identity > div {
  min-width: 0;
}

.contact-terminal__identity span,
.contact-terminal__state,
.contact-terminal__telemetry,
.contact-terminal__channel-rail,
.contact-terminal__readout {
  font-family: var(--ge-font-telemetry);
  font-size: 0.6875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.contact-terminal__identity > div > span {
  display: block;
  overflow: hidden;
  color: var(--ge-text-muted);
  text-overflow: ellipsis;
  white-space: nowrap;
}

.contact-terminal__identity h2 {
  margin: 0.25rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 1rem;
  font-weight: 600;
  line-height: 1.25;
  text-transform: uppercase;
}

.contact-terminal__antenna {
  position: relative;
  display: grid;
  width: 2rem;
  height: 2rem;
  flex: 0 0 auto;
  place-items: center;
  border: 1px solid var(--ge-border);
  background: var(--ge-bg-deep);
}

.contact-terminal__antenna::before,
.contact-terminal__antenna::after {
  content: "";
  position: absolute;
  border: 1px solid var(--ge-accent-info);
  border-radius: 50%;
}

.contact-terminal__antenna::before {
  width: 0.5rem;
  height: 0.5rem;
}

.contact-terminal__antenna::after {
  width: 1.125rem;
  height: 1.125rem;
  border-block-color: transparent;
}

.contact-terminal__antenna i {
  width: 2px;
  height: 2px;
  background: var(--ge-accent-info);
}

.contact-terminal__signal {
  display: none;
  height: 2rem;
  align-items: center;
  gap: 3px;
  padding-inline: 0.75rem;
  border-inline: 1px solid var(--ge-border-muted);
}

.contact-terminal__signal span {
  width: 2px;
  height: 0.5rem;
  background: var(--ge-accent-info);
  opacity: 0.42;
  transform-origin: center;
}

.contact-terminal__signal span:nth-child(3n) {
  height: 1.25rem;
}

.contact-terminal__signal span:nth-child(4n) {
  height: 0.875rem;
}

.contact-terminal__signal span:nth-child(5n) {
  height: 1.5rem;
}

.contact-terminal.is-motion-active .contact-terminal__signal span {
  animation: signal-wave 2.8s steps(4, end) infinite;
  animation-delay: calc(var(--signal-index) * -90ms);
}

.contact-terminal__state {
  display: inline-flex;
  align-items: center;
  justify-self: end;
  gap: 0.5rem;
  color: var(--ge-accent-info);
}

.contact-terminal__state-light {
  width: 0.5rem;
  height: 0.5rem;
  border: 1px solid currentColor;
  background: currentColor;
  box-shadow: 0 0 8px currentColor;
}

.contact-terminal--pending .contact-terminal__state {
  color: var(--ge-accent-primary);
}

.contact-terminal--success .contact-terminal__state {
  color: var(--ge-accent-success);
}

.contact-terminal--error .contact-terminal__state {
  color: var(--ge-accent-danger);
}

.contact-terminal.is-motion-active.contact-terminal--pending .contact-terminal__state-light {
  animation: status-pulse 1s steps(2, end) infinite;
}

.contact-terminal__viewport {
  position: relative;
  display: grid;
  background:
    linear-gradient(rgb(79 209 197 / 0.025) 1px, transparent 1px) 0 0 / 100% 4px,
    var(--ge-bg-deep);
}

.contact-terminal__channel-rail {
  display: none;
  padding: 1rem 0.5rem;
  color: var(--ge-text-muted);
  writing-mode: vertical-rl;
}

.contact-terminal__channel-rail i {
  width: 1px;
  min-height: 5rem;
  flex: 1;
  margin-block: 0.75rem;
  background: var(--ge-border);
}

.contact-terminal__screen {
  min-width: 0;
}

.contact-terminal__readout {
  display: flex;
  min-height: 3.25rem;
  align-items: center;
  gap: 0.625rem;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid var(--ge-border-muted);
  color: var(--ge-accent-info);
  line-height: 1.5;
}

.contact-terminal--error .contact-terminal__readout {
  color: var(--ge-accent-danger);
}

.contact-terminal--success .contact-terminal__readout {
  color: var(--ge-accent-success);
}

.contact-terminal__prompt {
  color: var(--ge-accent-primary);
}

.contact-terminal__cursor {
  width: 0.45rem;
  height: 0.875rem;
  flex: 0 0 auto;
  background: currentColor;
  opacity: 0.75;
}

.contact-terminal.is-motion-active .contact-terminal__cursor {
  animation: cursor-blink 1.2s steps(2, end) infinite;
}

.contact-form {
  position: relative;
  padding: 1rem;
}

.contact-form__trap {
  position: absolute;
  width: 1px;
  height: 1px;
  overflow: hidden;
  clip: rect(0 0 0 0);
}

.contact-form__error-summary {
  display: grid;
  grid-template-columns: 1.25rem 1fr;
  gap: 0.75rem;
  padding: 1rem;
  margin-bottom: 1.5rem;
  border: 1px solid var(--ge-accent-danger);
  background: rgb(240 68 68 / 0.08);
  color: var(--ge-text-body);
}

.contact-form__error-summary > :deep(svg) {
  width: 1.25rem;
  height: 1.25rem;
  color: var(--ge-accent-danger);
}

.contact-form__error-summary h3 {
  margin: 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 0.9375rem;
  text-transform: uppercase;
}

.contact-form__error-summary ul {
  padding-left: 1.25rem;
  margin: 0.625rem 0 0;
}

.contact-form__error-summary li + li {
  margin-top: 0.375rem;
}

.contact-form__error-summary a {
  color: var(--ge-accent-danger);
  font-size: 0.875rem;
}

.contact-form__fieldset {
  min-width: 0;
  padding: 0;
  margin: 0;
  border: 0;
}

.contact-form__fieldset + .contact-form__fieldset {
  padding-top: 1.5rem;
  margin-top: 1.75rem;
  border-top: 1px solid var(--ge-border-muted);
}

.contact-form__fieldset legend {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0;
  margin-bottom: 1.25rem;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
}

.contact-form__fieldset legend span {
  display: grid;
  width: 1.75rem;
  height: 1.75rem;
  place-items: center;
  border: 1px solid var(--ge-border);
  color: var(--ge-accent-primary);
  font-family: var(--ge-font-telemetry);
  font-size: 0.625rem;
}

.contact-form__grid {
  display: grid;
  gap: 1.125rem;
}

.contact-form__field {
  min-width: 0;
}

.contact-form__field label,
.contact-form__label-row > span {
  color: var(--ge-text-body);
  font-family: var(--ge-font-telemetry);
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}

.contact-form__field label > span {
  color: var(--ge-text-muted);
  font-size: 0.6875rem;
}

.contact-form__field label > span[aria-hidden='true'] {
  color: var(--ge-accent-primary);
}

.contact-form__label-row {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 1rem;
}

.contact-form__label-row > span {
  color: var(--ge-text-muted);
  font-size: 0.625rem;
}

.contact-form__instruction {
  margin: 0.375rem 0 0;
  color: var(--ge-text-muted);
  font-size: 0.8125rem;
  line-height: 1.5;
}

.contact-form input,
.contact-form select,
.contact-form textarea {
  display: block;
  width: 100%;
  min-height: 3rem;
  padding: 0.75rem;
  margin-top: 0.5rem;
  border: 1px solid var(--ge-border);
  border-radius: var(--ge-radius-xs);
  background: var(--ge-surface);
  color: var(--ge-text-heading);
  caret-color: var(--ge-accent-info);
  box-shadow: inset 0 2px 8px rgb(3 6 11 / 0.55), inset 0 1px var(--ge-bg-deep);
  transition: border-color 140ms ease, background-color 140ms ease, box-shadow 140ms ease;
}

.contact-form select {
  color-scheme: dark;
}

.contact-form textarea {
  min-height: 6.5rem;
  resize: vertical;
  line-height: 1.6;
}

.contact-form textarea#projectSummary {
  min-height: 10rem;
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
  color: var(--ge-text-muted);
  opacity: 0.7;
}

.contact-form input:hover,
.contact-form select:hover,
.contact-form textarea:hover {
  border-color: var(--ge-text-muted);
}

.contact-form input:focus,
.contact-form select:focus,
.contact-form textarea:focus {
  border-color: var(--ge-accent-info);
  background: var(--ge-surface-raised);
  box-shadow: inset 0 2px 8px rgb(3 6 11 / 0.55), 0 0 14px rgb(79 209 197 / 0.09);
}

.contact-form [aria-invalid='true'] {
  border-color: var(--ge-accent-danger);
}

.contact-form__error {
  margin: 0.5rem 0 0;
  color: var(--ge-accent-danger);
  font-size: 0.75rem;
  line-height: 1.5;
}

.contact-form__footer {
  display: grid;
  gap: 1rem;
  padding-top: 1.5rem;
  margin-top: 1.75rem;
  border-top: 1px solid var(--ge-border);
}

.contact-form__footer p {
  margin: 0;
  color: var(--ge-text-muted);
  font-size: 0.8125rem;
  line-height: 1.5;
}

.contact-form__footer :deep(.ge-button) {
  width: 100%;
}

.contact-terminal--pending .contact-form {
  opacity: 0.72;
}

.contact-terminal--pending :deep(.ge-button__icon svg) {
  animation: transmitter-spin 1s steps(8, end) infinite;
}

.contact-terminal__success {
  display: grid;
  min-height: 30rem;
  grid-template-columns: auto minmax(0, 34rem);
  place-content: center;
  gap: 1.5rem;
  padding: 3rem 1.25rem;
}

.contact-terminal__success-mark {
  display: grid;
  width: 3rem;
  height: 3rem;
  place-items: center;
  border: 1px solid var(--ge-accent-success);
  background: rgb(147 214 90 / 0.08);
  color: var(--ge-accent-success);
  box-shadow: inset 0 0 16px rgb(147 214 90 / 0.08);
}

.contact-terminal__success-mark :deep(svg) {
  width: 1.5rem;
  height: 1.5rem;
}

.contact-terminal__success h3 {
  margin: 0.75rem 0 0;
  color: var(--ge-text-heading);
  font-family: var(--ge-font-display);
  font-size: 1.5rem;
}

.contact-terminal__success p {
  margin: 0.75rem 0 1.5rem;
  color: var(--ge-text-muted);
  line-height: 1.7;
}

.contact-terminal__telemetry {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1.5rem;
  justify-content: space-between;
  padding: 0.625rem 1rem;
  border-top: 1px solid var(--ge-border);
  background: var(--ge-surface-raised);
  color: var(--ge-text-muted);
}

@keyframes signal-wave {
  0%, 100% { opacity: 0.3; transform: scaleY(0.55); }
  50% { opacity: 0.75; transform: scaleY(1); }
}

@keyframes cursor-blink {
  50% { opacity: 0; }
}

@keyframes status-pulse {
  50% { opacity: 0.3; box-shadow: none; }
}

@keyframes transmitter-spin {
  to { transform: rotate(360deg); }
}

@media (min-width: 48rem) {
  .contact-terminal {
    border: 1px solid var(--ge-border);
  }

  .contact-terminal__masthead {
    grid-template-columns: minmax(0, 1fr) auto auto;
    padding-inline: 1.25rem;
  }

  .contact-terminal__signal {
    display: flex;
  }

  .contact-terminal__viewport {
    grid-template-columns: 2.5rem minmax(0, 1fr);
  }

  .contact-terminal__channel-rail {
    display: flex;
    align-items: center;
    border-right: 1px solid var(--ge-border-muted);
  }

  .contact-terminal__readout,
  .contact-form {
    padding-inline: 1.5rem;
  }

  .contact-form {
    padding-block: 1.5rem;
  }

  .contact-form__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.25rem;
  }

  .contact-form__field--wide {
    grid-column: 1 / -1;
  }

  .contact-form__footer {
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
  }

  .contact-form__footer :deep(.ge-button) {
    width: auto;
  }
}

@media (max-width: 47.999rem) {
  .contact-terminal__masthead {
    grid-template-columns: minmax(0, 1fr);
  }

  .contact-terminal__state {
    width: calc(100% - 2.75rem);
    justify-content: flex-start;
    justify-self: end;
    padding-top: 0.625rem;
    border-top: 1px solid var(--ge-border-muted);
  }
}

@media (min-width: 64rem) {
  .contact-terminal__masthead {
    padding-inline: 1.5rem;
  }

  .contact-terminal__readout,
  .contact-form {
    padding-inline: 2rem;
  }

  .contact-form {
    padding-block: 2rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  .contact-terminal *,
  .contact-terminal *::before,
  .contact-terminal *::after {
    scroll-behavior: auto !important;
    animation-duration: 0.001ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.001ms !important;
  }
}
</style>
