<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Tests\Helpers;

use Misaf\EmailWebhooksResend\DataTransferObjects\ResendBounceEventDto;

final class TestResendBounceEventDto extends ResendBounceEventDto
{
    /**
     * @param array{
     *   type?: string,
     *   message?: string,
     *   subType?: string
     * } $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            type: $data['type'] ?? 'Permanent',
            message: $data['message'] ?? 'Test bounce message',
            subType: $data['subType'] ?? 'General',
        );
    }
}
