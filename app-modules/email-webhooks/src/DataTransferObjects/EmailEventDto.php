<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooks\DataTransferObjects;

abstract class EmailEventDto
{
    /**
     * @param list<string> $to Recipient email addresses
     * @param string $from Sender email address
     * @param string $subject Email subject
     * @param string $emailId Unique email ID from provider
     * @param string $createdAt When the event occurred
     * @param array<string, mixed> $originalPayload Original webhook payload
     * @param string $type Event type ('email.sent', 'email.bounced', 'email.complained', 'email.failed')
     * @param string $provider Provider name ('resend', etc.)
     * @param BounceEventDto|null $bounce Bounce data (can be BounceEventDto, or null)
     */
    public function __construct(
        public array $to,
        public string $from,
        public string $subject,
        public string $emailId,
        public string $createdAt,
        public array $originalPayload,
        public string $type,
        public string $provider,
        public ?BounceEventDto $bounce = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return static
     */
    abstract public static function fromArray(array $data): static;

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
        return [
            'to'               => $this->to,
            'from'             => $this->from,
            'subject'          => $this->subject,
            'email_id'         => $this->emailId,
            'created_at'       => $this->createdAt,
            'original_payload' => $this->originalPayload,
            'type'             => $this->type,
            'provider'         => $this->provider,
            'bounce'           => $this->bounce?->toArray(),
        ];
    }
}
