<?php

declare(strict_types=1);

namespace GalacticEvil\Contact;

use Closure;
use RuntimeException;

final class FileSubmissionStore
{
    private Closure $clock;

    public function __construct(
        private readonly string $directory,
        private readonly int $timeToLive = 86400,
        ?callable $clock = null,
    ) {
        $this->clock = Closure::fromCallable($clock ?? time(...));
    }

    /** @param callable(): ApiResponse $operation */
    public function execute(string $token, callable $operation): ?ApiResponse
    {
        $this->ensureDirectory();
        $path = $this->directory . '/' . hash('sha256', $token) . '.lock';
        $handle = fopen($path, 'c+');

        if ($handle === false) {
            throw new RuntimeException('Unable to open submission state.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock submission state.');
            }

            $now = (int) ($this->clock)();
            $contents = trim((string) stream_get_contents($handle));
            $acceptedAt = ctype_digit($contents) ? (int) $contents : 0;

            if ($acceptedAt > $now - $this->timeToLive) {
                return null;
            }

            $response = $operation();
            if ($response->ok) {
                rewind($handle);
                ftruncate($handle, 0);

                if (fwrite($handle, (string) $now) === false) {
                    throw new RuntimeException('Unable to write submission state.');
                }

                fflush($handle);
                chmod($path, 0600);
            }

            return $response;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function ensureDirectory(): void
    {
        if (!is_dir($this->directory) && !mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            throw new RuntimeException('Unable to create submission directory.');
        }
    }
}
