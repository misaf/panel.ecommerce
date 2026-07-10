<?php

declare(strict_types=1);

use Misaf\VendraAttributeApi\JsonApi\V1\Server;

it('uses the registered attribute api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});
