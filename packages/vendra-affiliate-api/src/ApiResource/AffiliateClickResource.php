<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\Post;
use Misaf\VendraAffiliateApi\Http\Requests\RecordReferralVisitRequest;
use Misaf\VendraAffiliateApi\State\RecordReferralVisitProcessor;

#[ApiResource(
    shortName: 'AffiliateClick',
    operations: [
        new Post(
            uriTemplate: '/marketing/affiliate-clicks',
            status: 204,
            output: false,
            processor: RecordReferralVisitProcessor::class,
            rules: RecordReferralVisitRequest::class,
            middleware: 'throttle:60,1',
        ),
    ],
    mcp: [
        'record_affiliate_visit' => new McpTool(
            description: 'Record a validated visit for an active affiliate referral code.',
            input: self::class,
            processor: RecordReferralVisitProcessor::class,
            validate: true,
            rules: RecordReferralVisitRequest::RULES,
        ),
    ],
)]
final class AffiliateClickResource
{
    public string $code = '';

    public ?string $landingUrl = null;
}
