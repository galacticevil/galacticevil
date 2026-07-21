<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

use InvalidArgumentException;

final readonly class EmailMessage
{
    public function __construct(
        public string $recipient,
        public string $sender,
        public string $replyTo,
        public string $subject,
        public string $plainText,
        public string $html,
    ) {
    }
}

interface MailTransport
{
    public function send(EmailMessage $message): bool;
}

final class EmailRenderer
{
    /** @var array<string, string> */
    private const ENGAGEMENT_LABELS = [
        'fractional-cto' => 'Fractional CTO',
        'contract-engineering' => 'Contract Engineering',
        'architecture-advisory' => 'Architecture & Advisory',
        'project-rescue' => 'Project Rescue',
        'not-sure' => 'Not sure yet',
    ];

    /** @var array<string, string> */
    private const TIMING_LABELS = [
        'as-soon-as-practical' => 'As soon as practical',
        'one-to-three-months' => 'Within 1-3 months',
        'three-to-six-months' => 'Within 3-6 months',
        'exploring' => 'Exploring for later',
    ];

    public function render(ContactSubmission $submission, string $recipient, string $sender): EmailMessage
    {
        $fields = [
            'Name' => $submission->name,
            'Email' => $submission->email,
            'Company or organization' => $submission->company ?: 'Not provided',
            'Location or timezone' => $submission->location ?: 'Not provided',
            'Engagement type' => self::ENGAGEMENT_LABELS[$submission->engagementType],
            'Approximate timing' => self::TIMING_LABELS[$submission->timing],
            'Budget context' => $submission->budgetContext ?: 'Not provided',
            'Project summary' => $submission->projectSummary,
        ];

        $plainParts = ['New Galactic Evil project enquiry', str_repeat('=', 34), ''];
        $htmlRows = '';

        foreach ($fields as $label => $value) {
            $plainParts[] = $label . ":\n" . $value . "\n";
            $htmlRows .= sprintf(
                '<tr><th align="left" valign="top">%s</th><td>%s</td></tr>',
                $this->escape($label),
                nl2br($this->escape($value), false),
            );
        }

        $html = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>New project enquiry</title></head>'
            . '<body><h1>New Galactic Evil project enquiry</h1><table cellpadding="8" cellspacing="0" border="1">'
            . $htmlRows . '</table></body></html>';

        return new EmailMessage(
            $recipient,
            $sender,
            $submission->email,
            '[Galactic Evil] New project enquiry',
            implode("\n", $plainParts),
            $html,
        );
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }
}

final class NativeMailTransport implements MailTransport
{
    public function send(EmailMessage $message): bool
    {
        $this->assertSafeAddress($message->recipient, 'recipient');
        $this->assertSafeAddress($message->sender, 'sender');
        $this->assertSafeAddress($message->replyTo, 'reply-to');
        $boundary = 'ge-' . bin2hex(random_bytes(16));
        $headers = [
            'From: Galactic Evil Website <' . $message->sender . '>',
            'Reply-To: ' . $message->replyTo,
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'X-Content-Type-Options: nosniff',
        ];
        $body = '--' . $boundary . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n"
            . $this->normalizeCrlf($message->plainText) . "\r\n"
            . '--' . $boundary . "\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n"
            . $this->normalizeCrlf($message->html) . "\r\n"
            . '--' . $boundary . "--\r\n";

        return mail(
            $message->recipient,
            $message->subject,
            $body,
            implode("\r\n", $headers),
        );
    }

    private function assertSafeAddress(string $address, string $name): void
    {
        if (preg_match('/[\r\n]/', $address) === 1 || filter_var($address, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException(sprintf('Invalid contact %s configuration.', $name));
        }
    }

    private function normalizeCrlf(string $value): string
    {
        return str_replace("\n", "\r\n", str_replace(["\r\n", "\r"], "\n", $value));
    }
}
