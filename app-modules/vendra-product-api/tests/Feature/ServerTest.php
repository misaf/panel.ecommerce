<?php

declare(strict_types=1);

use Misaf\VendraProductApi\JsonApi\V1\Server;

it('uses the registered product api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});
