<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

final readonly class ApiResponse
{
    /**
     * @param array<string, string> $errors
     * @param array<string, string> $headers
     */
    public function __construct(
        public int $status,
        public string $code,
        public string $message,
        public bool $ok = false,
        public array $errors = [],
        public array $headers = [],
    ) {
    }

    /** @return array{ok: bool, code: string, message: string, errors?: array<string, string>} */
    public function body(): array
    {
        $body = [
            'ok' => $this->ok,
            'code' => $this->code,
            'message' => $this->message,
        ];

        if ($this->errors !== []) {
            $body['errors'] = $this->errors;
        }

        return $body;
    }
}
