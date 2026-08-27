<?php

declare(strict_types=1);

namespace Misaf\VendraInquiryApi\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\Post;
use Misaf\VendraInquiryApi\Http\Requests\SubmitInquiryRequest;
use Misaf\VendraInquiryApi\State\SubmitInquiryProcessor;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * The storefront contact form.
 *
 * Anyone may write in, so the operation is unauthenticated and throttled, and
 * it answers `204` — an enquiry is not a resource the sender may read back.
 */
#[ApiResource(
    shortName: 'Inquiry',
    operations: [
        new Post(
            uriTemplate: '/support/inquiries',
            status: 204,
            output: false,
            processor: SubmitInquiryProcessor::class,
            rules: SubmitInquiryRequest::class,
            middleware: 'throttle:10,1',
        ),
    ],
    mcp: [
        'submit_inquiry' => new McpTool(
            description: 'Send a contact enquiry to the studio.',
            input: self::class,
            processor: SubmitInquiryProcessor::class,
            validate: true,
            rules: SubmitInquiryRequest::RULES,
        ),
    ],
)]
final class InquiryResource
{
    public string $name = '';

    public string $email = '';

    public string $message = '';

    public ?string $phone = null;

    public ?string $occasion = null;

    /**
     * The configured name converter maps camelCase wire names onto snake_case
     * PHP properties, so every multi-word input carries an explicit serialized
     * name and stays camelCase on both sides.
     */
    #[SerializedName('preferredLocale')]
    public ?string $preferredLocale = null;
}
