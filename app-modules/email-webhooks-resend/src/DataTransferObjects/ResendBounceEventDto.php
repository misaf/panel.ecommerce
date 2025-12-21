<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\DataTransferObjects;

use Misaf\EmailWebhooks\DataTransferObjects\BounceEventDto;

class ResendBounceEventDto extends BounceEventDto
{
    /**
     * @param string $type Bounce type ('Permanent', 'Temporary')
     * @param string $message
     * @param string $subType Bounce sub-type (e.g., 'OnAccountSuppressionList')
     */
    public function __construct(
        string $type,
        string $message,
        string $subType,
    ) {
        parent::__construct(
            type: $type,
            message: $message,
            subType: $subType,
        );
    }

    /**
     * @param array{
     *   type: string,
     *   message: string,
     *   subType: string
     * } $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new self(
            type: $data['type'],
            message: $data['message'],
            subType: $data['subType'],
        );
    }

    /**
     * @return array{
     *   type: string,
     *   message: string,
     *   subType: string
     * }
     */
    public function toArray(): array
    {
        return [
            'type'    => $this->type,
            'message' => $this->message,
            'subType' => $this->subType,
        ];
    }
}
