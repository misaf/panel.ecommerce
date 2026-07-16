<?php

declare(strict_types=1);

use LaravelJsonApi\Core\JsonApiService;
use Misaf\VendraFaqApi\JsonApi\V1\Server;

it('uses the registered faq api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});

it('registers the multimedia schema used by faq relationships', function (): void {
    $schemas = app(JsonApiService::class)->server('vendra-faq')->schemas();

    expect($schemas->exists('multimedia'))->toBeTrue();
});
