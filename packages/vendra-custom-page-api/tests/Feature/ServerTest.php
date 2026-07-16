<?php

declare(strict_types=1);

use LaravelJsonApi\Core\JsonApiService;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use Misaf\VendraCustomPageApi\JsonApi\V1\Server;

it('uses the registered custom page api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});

it('registers custom page, category, and multimedia schemas', function (): void {
    $schemas = app(JsonApiService::class)->server('vendra-custom-page')->schemas();

    expect($schemas->exists('custom-pages'))->toBeTrue()
        ->and($schemas->exists('custom-page-categories'))->toBeTrue()
        ->and($schemas->exists('multimedia'))->toBeTrue();
});

it('uses to-many fields that match the domain morph-many multimedia relations', function (): void {
    $schemas = app(JsonApiService::class)->server('vendra-custom-page')->schemas();

    expect($schemas->schemaFor('custom-pages')->field('multimedia'))->toBeInstanceOf(HasMany::class)
        ->and($schemas->schemaFor('custom-page-categories')->field('multimedia'))->toBeInstanceOf(HasMany::class);
});
