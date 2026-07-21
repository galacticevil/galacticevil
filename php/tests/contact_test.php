<?php

declare(strict_types=1);

use GalacticEvil\Contact\ContactHandler;
use GalacticEvil\Contact\ContactSubmission;
use GalacticEvil\Contact\ContactValidator;
use GalacticEvil\Contact\EmailMessage;
use GalacticEvil\Contact\EmailRenderer;
use GalacticEvil\Contact\FileRateLimiter;
use GalacticEvil\Contact\FileSubmissionStore;
use GalacticEvil\Contact\MailTransport;

require_once '/var/www/contact-src/bootstrap.php';

final class FakeTransport implements MailTransport
{
    public int $sendCount = 0;
    public ?EmailMessage $lastMessage = null;

    public function __construct(private readonly bool $result = true)
    {
    }

    public function send(EmailMessage $message): bool
    {
        $this->sendCount++;
        $this->lastMessage = $message;
        return $this->result;
    }
}

/** @return array<string, string> */
function validPayload(string $token = '9c6a5041-72cb-4e4e-a6c2-b98b9b74ab5c'): array
{
    return [
        'name' => 'Rory Cottle',
        'email' => 'rory@example.test',
        'company' => '',
        'location' => 'Johannesburg / SAST',
        'engagementType' => 'fractional-cto',
        'projectSummary' => 'We need senior technical leadership while preparing a product team for its next stage.',
        'timing' => 'one-to-three-months',
        'budgetContext' => '',
        'website' => '',
        'submissionToken' => $token,
    ];
}

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function temporaryDirectory(): string
{
    $directory = sys_get_temp_dir() . '/ge-contact-tests-' . bin2hex(random_bytes(6));
    mkdir($directory, 0700, true);
    return $directory;
}

function removeDirectory(string $directory): void
{
    if (!is_dir($directory)) {
        return;
    }

    $items = scandir($directory) ?: [];
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . '/' . $item;
        is_dir($path) ? removeDirectory($path) : unlink($path);
    }

    rmdir($directory);
}

/** @param callable(): void $test */
function runTest(string $name, callable $test): void
{
    try {
        $test();
        echo "PASS {$name}\n";
    } catch (Throwable $exception) {
        fwrite(STDERR, "FAIL {$name}: {$exception->getMessage()}\n");
        $GLOBALS['failures']++;
    }
}

/**
 * @param FakeTransport $transport
 */
function handler(string $directory, FakeTransport $transport, int $rateMaximum = 5): ContactHandler
{
    return new ContactHandler(
        new ContactValidator(),
        new FileRateLimiter($directory . '/rate', $rateMaximum, 900),
        new FileSubmissionStore($directory . '/submissions'),
        new EmailRenderer(),
        $transport,
        'contact@galactic-evil.test',
        'website@galactic-evil.test',
    );
}

$GLOBALS['failures'] = 0;

runTest('validator accepts and normalizes a valid payload', function (): void {
    $payload = validPayload();
    $payload['name'] = '  Rory   Cottle  ';
    $result = (new ContactValidator())->validate($payload);

    assertTrue($result->isValid(), 'Expected payload to be valid.');
    assertTrue($result->submission instanceof ContactSubmission, 'Expected a typed submission.');
    assertTrue($result->submission->name === 'Rory Cottle', 'Expected single-line whitespace normalization.');
});

runTest('validator rejects malformed fields and header injection', function (): void {
    $payload = validPayload();
    $payload['email'] = "rory@example.test\r\nBcc: injected@example.test";
    $payload['engagementType'] = 'unknown';
    $payload['projectSummary'] = 'Too short';
    $result = (new ContactValidator())->validate($payload);

    assertTrue(!$result->isValid(), 'Expected payload to be rejected.');
    assertTrue(isset($result->errors['email']), 'Expected email error.');
    assertTrue(isset($result->errors['engagementType']), 'Expected engagement error.');
    assertTrue(isset($result->errors['projectSummary']), 'Expected summary error.');
});

runTest('honeypot is accepted silently without sending mail', function (): void {
    $directory = temporaryDirectory();
    try {
        $transport = new FakeTransport();
        $payload = validPayload();
        $payload['website'] = 'https://spam.example';
        $response = handler($directory, $transport)->handle($payload, '127.0.0.1');

        assertTrue($response->status === 202 && $response->ok, 'Expected generic accepted response.');
        assertTrue($transport->sendCount === 0, 'Honeypot must not send mail.');
    } finally {
        removeDirectory($directory);
    }
});

runTest('duplicate submission token sends exactly once', function (): void {
    $directory = temporaryDirectory();
    try {
        $transport = new FakeTransport();
        $handler = handler($directory, $transport);
        $first = $handler->handle(validPayload(), '127.0.0.1');
        $second = $handler->handle(validPayload(), '127.0.0.1');

        assertTrue($first->status === 202, 'Expected first request to be accepted.');
        assertTrue($second->status === 200 && $second->code === 'already_received', 'Expected idempotent duplicate response.');
        assertTrue($transport->sendCount === 1, 'Duplicate must not send another message.');
    } finally {
        removeDirectory($directory);
    }
});

runTest('rate limiter blocks a second unique submission in the window', function (): void {
    $directory = temporaryDirectory();
    try {
        $transport = new FakeTransport();
        $handler = handler($directory, $transport, 1);
        $first = $handler->handle(validPayload(), '127.0.0.1');
        $second = $handler->handle(validPayload('35e7fe35-827a-4382-8c90-5506c1550ec1'), '127.0.0.1');

        assertTrue($first->status === 202, 'Expected first request to be accepted.');
        assertTrue($second->status === 429, 'Expected second request to be rate limited.');
        assertTrue(isset($second->headers['Retry-After']), 'Expected Retry-After header.');
        assertTrue($transport->sendCount === 1, 'Rate-limited request must not send mail.');
    } finally {
        removeDirectory($directory);
    }
});

runTest('transport failure is retryable and does not mark token complete', function (): void {
    $directory = temporaryDirectory();
    try {
        $transport = new FakeTransport(false);
        $handler = handler($directory, $transport);
        $first = $handler->handle(validPayload(), '127.0.0.1');
        $second = $handler->handle(validPayload(), '127.0.0.1');

        assertTrue($first->status === 503 && $second->status === 503, 'Expected retryable service failure.');
        assertTrue($transport->sendCount === 2, 'Failed token must remain retryable.');
    } finally {
        removeDirectory($directory);
    }
});

runTest('email renderer escapes HTML while retaining plain-text content', function (): void {
    $payload = validPayload();
    $payload['name'] = '<script>alert(1)</script>';
    $submission = (new ContactValidator())->validate($payload)->submission;
    assertTrue($submission instanceof ContactSubmission, 'Expected valid typed submission.');

    $message = (new EmailRenderer())->render(
        $submission,
        'contact@galactic-evil.test',
        'website@galactic-evil.test',
    );

    assertTrue(!str_contains($message->html, '<script>'), 'HTML body must escape submitted markup.');
    assertTrue(str_contains($message->html, '&lt;script&gt;'), 'HTML body should preserve escaped content.');
    assertTrue(str_contains($message->plainText, '<script>alert(1)</script>'), 'Plain text should retain submitted text safely.');
});

exit($GLOBALS['failures'] === 0 ? 0 : 1);
