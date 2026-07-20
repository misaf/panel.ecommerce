<?php

declare(strict_types=1);

use Composer\InstalledVersions;
use Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema;
use Misaf\VendraProductApi\Support\AttributeApiIntegration;

it('resolves the attribute-value schema when vendra-attribute-api is installed', function (): void {
    expect(AttributeApiIntegration::isAvailable())->toBeTrue()
        ->and(AttributeApiIntegration::attributeValueSchema())->toBe(AttributeValueSchema::class);
})->skip(
    fn(): bool => ! InstalledVersions::isInstalled('misaf/vendra-attribute-api'),
    'vendra-attribute-api is not installed',
);
