<?php

declare(strict_types=1);

use GalacticEvil\Contact\ApiResponse;
use GalacticEvil\Contact\ContactHandler;
use GalacticEvil\Contact\ContactValidator;
use GalacticEvil\Contact\EmailRenderer;
use GalacticEvil\Contact\FileRateLimiter;
use GalacticEvil\Contact\FileSubmissionStore;
use GalacticEvil\Contact\NativeMailTransport;
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('Pragma: no-cache');
header('X-Content-Type-Options: nosniff');

/** @param array<string, mixed> $body */
function respond(ApiResponse $response, array $body = []): never
{
    http_response_code($response->status);

    foreach ($response->headers as $name => $value) {
        header($name . ': ' . $value);
    }

    echo json_encode($body !== [] ? $body : $response->body(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function environment(string $name, string $default = ''): string
{
    $value = getenv($name);
    return is_string($value) && $value !== '' ? $value : $default;
}

function environmentInteger(string $name, int $default, int $minimum, int $maximum): int
{
    $value = filter_var(getenv($name), FILTER_VALIDATE_INT);
    return is_int($value) && $value >= $minimum && $value <= $maximum ? $value : $default;
}

$sourceDirectory = environment('CONTACT_SOURCE_DIR', dirname(__DIR__) . '/src');

try {
    require_once $sourceDirectory . '/bootstrap.php';
} catch (Throwable $exception) {
    error_log('Contact endpoint bootstrap failed: ' . $exception::class);
    http_response_code(503);
    echo '{"ok":false,"code":"service_unavailable","message":"The transmission could not be completed. Please retry shortly."}';
    exit;
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
if ($method !== 'POST') {
    header('Allow: POST');
    respond(new ApiResponse(405, 'method_not_allowed', 'Submit enquiries using POST.'));
}

$contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
if ($contentType !== 'application/json') {
    respond(new ApiResponse(415, 'unsupported_media_type', 'Submit the enquiry as application/json.'));
}

$maximumBodyBytes = 20_000;
$contentLength = filter_var($_SERVER['CONTENT_LENGTH'] ?? null, FILTER_VALIDATE_INT);
if (is_int($contentLength) && $contentLength > $maximumBodyBytes) {
    respond(new ApiResponse(413, 'payload_too_large', 'The enquiry is too large to process.'));
}

$rawBody = file_get_contents('php://input', false, null, 0, $maximumBodyBytes + 1);
if (!is_string($rawBody) || strlen($rawBody) > $maximumBodyBytes) {
    respond(new ApiResponse(413, 'payload_too_large', 'The enquiry is too large to process.'));
}

try {
    $decodedPayload = json_decode($rawBody, false, 32, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    respond(new ApiResponse(400, 'invalid_json', 'The enquiry could not be read as valid JSON.'));
}

if (!is_object($decodedPayload)) {
    respond(new ApiResponse(400, 'invalid_payload', 'The enquiry must be a JSON object.'));
}

$payload = get_object_vars($decodedPayload);

$stateDirectory = environment('CONTACT_STATE_DIR', sys_get_temp_dir() . '/galactic-evil-contact');

try {
    $handler = new ContactHandler(
        new ContactValidator(),
        new FileRateLimiter(
            $stateDirectory . '/rate-limit',
            environmentInteger('CONTACT_RATE_LIMIT_MAX', 5, 1, 100),
            environmentInteger('CONTACT_RATE_LIMIT_WINDOW', 900, 10, 86400),
        ),
        new FileSubmissionStore(
            $stateDirectory . '/submissions',
            environmentInteger('CONTACT_SUBMISSION_TTL', 86400, 60, 604800),
        ),
        new EmailRenderer(),
        new NativeMailTransport(),
        environment('CONTACT_RECIPIENT'),
        environment('CONTACT_FROM'),
    );

    respond($handler->handle($payload, $_SERVER['REMOTE_ADDR'] ?? 'unknown'));
} catch (Throwable $exception) {
    error_log('Contact endpoint failed: ' . $exception::class);
    respond(new ApiResponse(
        503,
        'service_unavailable',
        'The transmission could not be completed. Please retry shortly.',
    ));
}
