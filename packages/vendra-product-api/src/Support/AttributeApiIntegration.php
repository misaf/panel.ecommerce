<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\Support;

/**
 * Resolves the optional misaf/vendra-attribute-api integration.
 *
 * Attribute-value schemas, relationships, filters, and include paths are only
 * exposed when the suggested package is installed; the product API must remain
 * fully functional without it.
 */
final class AttributeApiIntegration
{
    private const string ATTRIBUTE_VALUE_SCHEMA = 'Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema';

    public static function isAvailable(): bool
    {
        return class_exists(self::ATTRIBUTE_VALUE_SCHEMA);
    }

    /** @return class-string */
    public static function attributeValueSchema(): string
    {
        return self::ATTRIBUTE_VALUE_SCHEMA;
    }
}
