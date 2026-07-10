<?php

declare(strict_types=1);

use Misaf\VendraBlogApi\JsonApi\V1\Server;

it('uses the registered blog api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});
