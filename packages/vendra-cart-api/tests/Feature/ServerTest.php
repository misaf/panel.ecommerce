<?php

declare(strict_types=1);

use Misaf\VendraCartApi\JsonApi\V1\CartItems\CartItemSchema;
use Misaf\VendraCartApi\JsonApi\V1\Carts\CartSchema;
use Misaf\VendraCartApi\JsonApi\V1\Server;

it('registers cart schemas at the v1 base uri', function (): void {
    $reflection = new ReflectionClass(Server::class);
    $properties = $reflection->getDefaultProperties();
    $server = $reflection->newInstanceWithoutConstructor();

    expect($properties['baseUri'])->toBe('/v1')
        ->and($server->allSchemas())->toBe([
            CartSchema::class,
            CartItemSchema::class,
        ]);
});
