<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraMultimediaApi\JsonApi\V1\Multimedia\MultimediaSchema;
use Misaf\VendraProductApi\JsonApi\V1\ProductCategories\ProductCategorySchema;
use Misaf\VendraProductApi\JsonApi\V1\ProductPrices\ProductPriceSchema;
use Misaf\VendraProductApi\JsonApi\V1\Products\ProductSchema;
use Misaf\VendraSupport\Support\AttributeApiIntegration;

final class Server extends BaseServer
{
    protected string $baseUri = '/v1';

    public function authorizable(): bool
    {
        return false;
    }

    /** @phpstan-ignore missingType.iterableValue */
    public function allSchemas(): array
    {
        $schemas = [
            ProductCategorySchema::class,
            ProductSchema::class,
            ProductPriceSchema::class,
            MultimediaSchema::class,
        ];

        if (AttributeApiIntegration::isAvailable()) {
            $schemas[] = AttributeApiIntegration::attributeValueSchema();
        }

        return $schemas;
    }
}
