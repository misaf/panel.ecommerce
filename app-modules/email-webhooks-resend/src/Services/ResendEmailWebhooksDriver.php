<?php

declare(strict_types=1);

namespace Misaf\EmailWebhooksResend\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;
use Misaf\EmailWebhooks\Services\EmailWebhooksDriver;
use Misaf\EmailWebhooksResend\DataTransferObjects\ResendEventDto;

final class ResendEmailWebhooksDriver extends EmailWebhooksDriver
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    protected function validatePayload(array $payload): array
    {
        $validator = Validator::make($payload, [
            'data'                => 'bail|required|array',
            'data.to'             => 'bail|required|array|min:1|max:100',
            'data.to.*'           => 'bail|required|email:rfc,strict,spoof,filter,filter_unicode|max:255',
            'data.from'           => 'bail|required|email:rfc,strict,spoof,filter,filter_unicode|max:255',
            'data.bounce'         => 'bail|required_if:type,email.bounced,email.complained|nullable|array',
            'data.bounce.type'    => 'bail|required_with:data.bounce|string|in:Permanent,Temporary',
            'data.bounce.message' => 'bail|required_with:data.bounce|string|max:1000',
            'data.bounce.subType' => 'bail|required_with:data.bounce|string|max:255',
            'data.subject'        => 'bail|required|string|min:1|max:255',
            'data.email_id'       => 'bail|required|string|min:1',
            'data.created_at'     => 'bail|required|string|date',
            'type'                => 'bail|required|string|in:email.sent,email.bounced,email.complained,email.failed',
        ]);

        try {
            return $validator->validate();
        } catch (ValidationException $e) {
            throw new InvalidArgumentException(
                'Invalid Resend webhook payload: ' . $e->getMessage(),
            );
        }
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
     * @return EmailEventDto
     */
    protected function createEventFromPayload(array $payload): EmailEventDto
    {
        return ResendEventDto::fromArray($payload);
    }

    /**
     * @return string
     */
    protected function getProviderName(): string
    {
        $provider = Config::string('services.email.webhooks.resend.webhook_name', 'resend');

        return $provider;
    }
}
