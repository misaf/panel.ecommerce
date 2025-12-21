<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\DataTransferObjects;

use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;

class ResendEventDto extends EmailEventDto
{
    /**
     * @param array{
     *   data: array{
     *     to: list<string>,
     *     from: string,
     *     subject: string,
     *     email_id: string,
     *     created_at: string,
     *     bounce?: array{
     *       type: string,
     *       message: string,
     *       subType: string
     *     }
     *   },
     *   type: string
     * } $payload
     */
    public function __construct(array $payload)
    {
        parent::__construct(
            to: $payload['data']['to'],
            from: $payload['data']['from'],
            subject: $payload['data']['subject'],
            emailId: $payload['data']['email_id'],
            createdAt: $payload['data']['created_at'],
            originalPayload: $payload,
            type: $payload['type'],
            provider: 'resend',
            bounce: isset($payload['data']['bounce']) ? ResendBounceEventDto::fromArray($payload['data']['bounce']) : null,
        );
    }

    /**
     * @param array{
     *   data: array{
     *     to: list<string>,
     *     from: string,
     *     subject: string,
     *     email_id: string,
     *     created_at: string,
     *     bounce?: array{
     *       type: string,
     *       message: string,
     *       subType: string
     *     }
     *   },
     *   type: string
     * } $payload
     * @return static
     */
    public static function fromArray(array $payload): static
    {
        return new self($payload);
    }

    /**
     * @return array{
     *   to: list<string>,
     *   from: string,
     *   subject: string,
     *   email_id: string,
     *   created_at: string,
     *   original_payload: array<string, mixed>,
     *   type: string,
     *   provider: string,
     *   bounce?: array{
     *     type: string,
     *     message: string,
     *     subType: string
     *   }|null,
     * } $payload
     */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
