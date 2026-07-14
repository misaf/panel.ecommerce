<?php

declare(strict_types=1);

use Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates\AffiliateSchema;
use Misaf\VendraAffiliateApi\JsonApi\V1\Server;

it('uses the registered affiliate api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});

it('registers only the affiliate schema and stays unauthorized', function (): void {
    $server = app()->make(Server::class, ['name' => 'vendra-affiliate']);

    expect($server->allSchemas())->toBe([AffiliateSchema::class])
        ->and($server->authorizable())->toBeFalse();
});
