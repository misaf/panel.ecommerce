<?php

declare(strict_types=1);

namespace Misaf\VendraDeliveryApi\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\McpTool;
use ApiPlatform\Metadata\Post;
use Misaf\VendraDeliveryApi\Http\Requests\QuoteDeliveryRequest;
use Misaf\VendraDeliveryApi\State\QuoteDeliveryProcessor;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * Prices a dropped pin. The response tells the storefront which band the
 * address falls in, what it costs, and whether the studio must quote it by
 * hand instead.
 */
#[ApiResource(
    shortName: 'DeliveryQuote',
    operations: [
        new Post(
            uriTemplate: '/delivery/quotes',
            status: 200,
            processor: QuoteDeliveryProcessor::class,
            rules: QuoteDeliveryRequest::class,
            middleware: 'throttle:60,1',
        ),
    ],
    mcp: [
        'quote_delivery' => new McpTool(
            description: 'Price a delivery to a latitude and longitude.',
            input: self::class,
            processor: QuoteDeliveryProcessor::class,
            validate: true,
            rules: QuoteDeliveryRequest::RULES,
        ),
    ],
)]
final class DeliveryQuoteResource
{
    public float $latitude = 0.0;

    public float $longitude = 0.0;

    /**
     * The configured name converter maps camelCase wire names onto snake_case
     * PHP properties, so every multi-word input carries an explicit serialized
     * name and stays camelCase on both sides.
     */
    #[SerializedName('currencyCode')]
    public ?string $currencyCode = null;
}
