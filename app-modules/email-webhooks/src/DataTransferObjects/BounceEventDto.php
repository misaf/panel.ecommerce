<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\DataTransferObjects;

abstract class BounceEventDto
{
    /**
     * @param string $type Bounce type ('Permanent', 'Temporary')
     * @param string $message
     * @param string $subType Bounce sub-type (e.g., 'OnAccountSuppressionList')
     */
    public function __construct(
        public string $type,
        public string $message,
        public string $subType,
    ) {}

    /**
     * @param array{
     *   type: string,
     *   message: string,
     *   subType: string
     * } $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;

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
