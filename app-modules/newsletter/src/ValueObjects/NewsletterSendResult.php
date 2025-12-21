<?php

declare(strict_types=1);

namespace Misaf\Newsletter\ValueObjects;

final class NewsletterSendResult
{
    public function __construct(
        public readonly int $queuedCount,
        public readonly bool $isDryRun = false,
        public readonly ?string $message = null,
        public readonly array $metadata = []
    ) {}

    public static function success(int $queuedCount, bool $isDryRun = false): self
    {
        $message = $isDryRun
            ? "Dry run completed. {$queuedCount} emails would be queued."
            : "Successfully queued {$queuedCount} emails.";

        return new self(
            queuedCount: $queuedCount,
            isDryRun: $isDryRun,
            message: $message
        );
    }

    public function toArray(): array
    {
        return [
            'queued'    => $this->queuedCount,
            'dry_run'   => $this->isDryRun,
            'message'   => $this->message,
            'metadata'  => $this->metadata,
        ];
    }
}
