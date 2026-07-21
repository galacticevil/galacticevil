<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

final readonly class ContactSubmission
{
    public function __construct(
        public string $name,
        public string $email,
        public string $company,
        public string $location,
        public string $engagementType,
        public string $projectSummary,
        public string $timing,
        public string $budgetContext,
        public string $submissionToken,
    ) {
    }
}

final readonly class ValidationResult
{
    /** @param array<string, string> $errors */
    public function __construct(
        public ?ContactSubmission $submission,
        public array $errors = [],
    ) {
    }

    public function isValid(): bool
    {
        return $this->submission !== null && $this->errors === [];
    }
}

final class ContactValidator
{
    /** @var list<string> */
    private const ENGAGEMENT_TYPES = [
        'fractional-cto',
        'contract-engineering',
        'architecture-advisory',
        'project-rescue',
        'not-sure',
    ];

    /** @var list<string> */
    private const TIMINGS = [
        'as-soon-as-practical',
        'one-to-three-months',
        'three-to-six-months',
        'exploring',
    ];

    /** @param array<string, mixed> $payload */
    public function honeypotIsFilled(array $payload): bool
    {
        if (!array_key_exists('website', $payload)) {
            return false;
        }

        return !is_string($payload['website']) || trim($payload['website']) !== '';
    }

    /** @param array<string, mixed> $payload */
    public function validate(array $payload): ValidationResult
    {
        $errors = [];

        $name = $this->singleLine($payload, 'name', $errors);
        $email = $this->singleLine($payload, 'email', $errors);
        $company = $this->singleLine($payload, 'company', $errors);
        $location = $this->singleLine($payload, 'location', $errors);
        $engagementType = $this->singleLine($payload, 'engagementType', $errors);
        $projectSummary = $this->multiLine($payload, 'projectSummary', $errors);
        $timing = $this->singleLine($payload, 'timing', $errors);
        $budgetContext = $this->multiLine($payload, 'budgetContext', $errors);
        $submissionToken = $this->singleLine($payload, 'submissionToken', $errors);

        $this->validateLength($name, 'name', 2, 100, 'Enter your name using at least 2 characters.', $errors);

        if ($email === '') {
            $errors['email'] = 'Enter your email address.';
        } elseif ($this->length($email) > 254 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Enter a valid email address.';
        }

        $this->validateOptionalMaximum($company, 'company', 120, 'Keep the organization name under 120 characters.', $errors);
        $this->validateOptionalMaximum($location, 'location', 120, 'Keep the location or timezone under 120 characters.', $errors);

        if (!in_array($engagementType, self::ENGAGEMENT_TYPES, true)) {
            $errors['engagementType'] = 'Select the type of engagement you have in mind.';
        }

        $this->validateLength(
            $projectSummary,
            'projectSummary',
            40,
            4000,
            'Share at least 40 characters so there is enough context to respond.',
            $errors,
        );

        if (!in_array($timing, self::TIMINGS, true)) {
            $errors['timing'] = 'Select an approximate timing.';
        }

        $this->validateOptionalMaximum($budgetContext, 'budgetContext', 500, 'Keep the budget context under 500 characters.', $errors);

        if (preg_match('/\A[A-Za-z0-9-]{24,80}\z/', $submissionToken) !== 1) {
            $errors['submissionToken'] = 'The submission could not be identified. Refresh and try again.';
        }

        if ($errors !== []) {
            return new ValidationResult(null, $errors);
        }

        return new ValidationResult(new ContactSubmission(
            $name,
            $email,
            $company,
            $location,
            $engagementType,
            $projectSummary,
            $timing,
            $budgetContext,
            $submissionToken,
        ));
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, string> $errors
     */
    private function singleLine(array $payload, string $field, array &$errors): string
    {
        $value = $this->stringValue($payload, $field, $errors);
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\r\n\x00-\x1F\x7F]/u', $value) === 1) {
            $errors[$field] = 'Remove line breaks or control characters from this field.';
            return '';
        }

        return preg_replace('/[ \t]+/u', ' ', trim($value)) ?? '';
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, string> $errors
     */
    private function multiLine(array $payload, string $field, array &$errors): string
    {
        $value = $this->stringValue($payload, $field, $errors);
        if ($value === '') {
            return '';
        }

        if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', $value) === 1) {
            $errors[$field] = 'Remove control characters from this field.';
            return '';
        }

        return trim(str_replace(["\r\n", "\r"], "\n", $value));
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, string> $errors
     */
    private function stringValue(array $payload, string $field, array &$errors): string
    {
        if (!array_key_exists($field, $payload)) {
            return '';
        }

        if (!is_string($payload[$field]) || preg_match('//u', $payload[$field]) !== 1) {
            $errors[$field] = 'Enter valid text for this field.';
            return '';
        }

        return $payload[$field];
    }

    /** @param array<string, string> $errors */
    private function validateLength(
        string $value,
        string $field,
        int $minimum,
        int $maximum,
        string $minimumMessage,
        array &$errors,
    ): void {
        $length = $this->length($value);

        if ($length < $minimum) {
            $errors[$field] = $minimumMessage;
        } elseif ($length > $maximum) {
            $errors[$field] = sprintf('Keep this field under %d characters.', $maximum);
        }
    }

    /** @param array<string, string> $errors */
    private function validateOptionalMaximum(
        string $value,
        string $field,
        int $maximum,
        string $message,
        array &$errors,
    ): void {
        if ($this->length($value) > $maximum) {
            $errors[$field] = $message;
        }
    }

    private function length(string $value): int
    {
        $matches = [];
        return preg_match_all('/./us', $value, $matches);
    }
}
