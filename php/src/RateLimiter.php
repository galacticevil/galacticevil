<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

use Closure;
use RuntimeException;

final readonly class RateLimitResult
{
    public function __construct(
        public bool $allowed,
        public int $retryAfter = 0,
    ) {
    }
}

final class FileRateLimiter
{
    private Closure $clock;

    public function __construct(
        private readonly string $directory,
        private readonly int $maximumAttempts = 5,
        private readonly int $windowSeconds = 900,
        ?callable $clock = null,
    ) {
        $this->clock = Closure::fromCallable($clock ?? time(...));
    }

    public function consume(string $clientKey): RateLimitResult
    {
        $this->ensureDirectory();
        $path = $this->directory . '/' . hash('sha256', $clientKey) . '.json';
        $handle = fopen($path, 'c+');

        if ($handle === false) {
            throw new RuntimeException('Unable to open rate-limit state.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock rate-limit state.');
            }

            $now = (int) ($this->clock)();
            $contents = stream_get_contents($handle);
            $decoded = is_string($contents) && $contents !== '' ? json_decode($contents, true) : [];
            $attempts = is_array($decoded) ? array_values(array_filter(
                $decoded,
                fn (mixed $timestamp): bool => is_int($timestamp) && $timestamp > $now - $this->windowSeconds,
            )) : [];

            if (count($attempts) >= $this->maximumAttempts) {
                $oldest = min($attempts);
                return new RateLimitResult(false, max(1, $oldest + $this->windowSeconds - $now));
            }

            $attempts[] = $now;
            rewind($handle);
            ftruncate($handle, 0);

            if (fwrite($handle, json_encode($attempts, JSON_THROW_ON_ERROR)) === false) {
                throw new RuntimeException('Unable to write rate-limit state.');
            }

            fflush($handle);
            chmod($path, 0600);

            return new RateLimitResult(true);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function ensureDirectory(): void
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            throw new RuntimeException('Unable to create rate-limit directory.');
        }
    }
}
