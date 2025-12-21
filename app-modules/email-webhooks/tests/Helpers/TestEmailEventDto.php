<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\Tests\Helpers;

use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;

final class TestEmailEventDto extends EmailEventDto
{
    /**
     * @param array{
     *   to?: list<string>,
     *   from?: string,
     *   subject?: string,
     *   email_id?: string,
     *   created_at?: string,
     *   original_payload?: array<string, mixed>,
     *   type?: string,
     *   provider?: string,
     *   bounce?: array{
     *     type?: string,
     *     message?: string,
     *     subType?: string
     *   }
     * } $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        $bounce = null;
        if (isset($data['bounce'])) {
            $bounce = TestBounceEventDto::fromArray($data['bounce']);
        }

        return new static(
            to: $data['to'] ?? ['test@example.com'],
            from: $data['from'] ?? 'sender@example.com',
            subject: $data['subject'] ?? 'Test Email',
            emailId: $data['email_id'] ?? 'test-email-123',
            createdAt: $data['created_at'] ?? '2024-01-01T12:00:00Z',
            originalPayload: $data['original_payload'] ?? ['test' => 'data'],
            type: $data['type'] ?? 'email.sent',
            provider: $data['provider'] ?? 'test-provider',
            bounce: $bounce,
        );
    }
}
