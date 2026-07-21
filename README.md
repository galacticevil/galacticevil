# Galactic Evil portfolio

Rory Cottle's statically generated Nuxt portfolio, with a minimal PHP contact endpoint for conventional Apache hosting.

## Frontend development

Requirements: Node 22.22.2 and npm 11.18.0.

```bash
npm install
cp .env.example .env
docker compose up --build -d
npm run dev
```

Nuxt runs at `http://localhost:3000` and proxies the same-origin `/api/*` path to Apache at `http://localhost:8081`. Captured development mail is available in Mailpit at `http://localhost:8025`.

No local submission uses a real recipient: the example `.test` addresses terminate inside Mailpit.

## Verification

```bash
npm run test:frontend
npm run test:php
npm run test:http
npm run typecheck
npm run generate
docker compose exec php php -l /var/www/html/api/contact.php
```

Inspect Apache health directly at `http://localhost:8081/api/health.php`. Stop the local services with `docker compose down`.

## Contact endpoint

`POST /api/contact.php` accepts JSON only. It validates all fields, silently discards a hidden honeypot, rate limits by a one-way hash of the remote address, and prevents repeat delivery using an in-memory browser token backed by a short-lived filesystem marker. The filesystem state contains timestamps and hashes only, never enquiry content.

PHP renders plain-text and escaped HTML email alternatives and sends them through the host's `mail()` implementation. The Docker image redirects `mail()` to Mailpit through `msmtp`; production can use SureSupport's standard mail transport without changing application code.

## Production deployment

Generate the static site with `npm run generate`, deploy `.output/public/` to the web root, and deploy `php/public/contact.php` as `/api/contact.php`. Place `php/src/` outside the public web root and set `CONTACT_SOURCE_DIR` to its absolute path.

Configure these values in the hosting environment, never in committed files:

```text
CONTACT_RECIPIENT=confirmed-recipient@example.com
CONTACT_FROM=website-sender@the-deployed-domain.example
CONTACT_SOURCE_DIR=/absolute/path/outside/public/contact-src
CONTACT_STATE_DIR=/absolute/path/to/private-writable/contact-state
CONTACT_RATE_LIMIT_MAX=5
CONTACT_RATE_LIMIT_WINDOW=900
CONTACT_SUBMISSION_TTL=86400
```

`CONTACT_STATE_DIR` must exist outside the public web root or be creatable by the PHP process. The PHP user needs read/write access; directories are restricted to mode `0700` and state files to `0600`. Confirm the production sender and recipient, host mail policy, SPF/DKIM alignment, and a live end-to-end delivery before launch.

## Project direction

The approved direction is a responsive professional portfolio presented through the visual language of a lost 1990s SVGA science-fiction adventure game. The requirements and phased plan live in [`docs/`](docs/).
