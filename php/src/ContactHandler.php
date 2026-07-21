<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

use Throwable;

final readonly class ContactHandler
{
    public function __construct(
        private ContactValidator $validator,
        private FileRateLimiter $rateLimiter,
        private FileSubmissionStore $submissionStore,
        private EmailRenderer $renderer,
        private MailTransport $transport,
        private string $recipient,
        private string $sender,
    ) {
    }

    /** @param array<string, mixed> $payload */
    public function handle(array $payload, string $clientKey): ApiResponse
    {
        if ($this->validator->honeypotIsFilled($payload)) {
            return $this->acceptedResponse();
        }

        $validation = $this->validator->validate($payload);
        if (!$validation->isValid() || $validation->submission === null) {
            return new ApiResponse(
                422,
                'validation_failed',
                'Check the highlighted fields before transmitting.',
                errors: $validation->errors,
            );
        }

        $submission = $validation->submission;

        try {
            $response = $this->submissionStore->execute(
                $submission->submissionToken,
                function () use ($submission, $clientKey): ApiResponse {
                    $rateLimit = $this->rateLimiter->consume($clientKey);
                    if (!$rateLimit->allowed) {
                        return new ApiResponse(
                            429,
                            'rate_limited',
                            'The channel is busy. Please wait before trying again.',
                            headers: ['Retry-After' => (string) $rateLimit->retryAfter],
                        );
                    }

                    $message = $this->renderer->render($submission, $this->recipient, $this->sender);
                    if (!$this->transport->send($message)) {
                        error_log('Contact submission transport returned failure.');
                        return $this->failureResponse();
                    }

                    return $this->acceptedResponse();
                },
            );

            return $response ?? new ApiResponse(
                200,
                'already_received',
                'Your enquiry has already been received.',
                true,
            );
        } catch (Throwable $exception) {
            error_log('Contact submission failed: ' . $exception::class);
            return $this->failureResponse();
        }
    }

    private function acceptedResponse(): ApiResponse
    {
        return new ApiResponse(202, 'accepted', 'Your enquiry has been received.', true);
    }

    private function failureResponse(): ApiResponse
    {
        return new ApiResponse(
            503,
            'service_unavailable',
            'The transmission could not be completed. Please retry shortly.',
        );
    }
}
