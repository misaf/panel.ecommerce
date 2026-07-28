<?php

declare(strict_types=1);

use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Property\PropertyNameCollection;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use Illuminate\Support\Facades\Cache;

/**
 * api-platform/laravel caches resource and property metadata via
 * Cache::store(config('api-platform.cache'))->rememberForever(), which is the
 * app's "file" store. Laravel's cache deserialization hardening
 * (config('cache.serializable_classes')) rejects any class in the cached
 * object graph that isn't explicitly allow-listed, silently turning it into
 * __PHP_Incomplete_Class. Reading such a value back throws a TypeError as
 * soon as api-platform tries to use it (e.g. during `package:discover`),
 * because the factories declare a concrete return type.
 *
 * These tests force a real write-then-read round trip through that cache
 * store for every resource the app registers, so a new operation, parameter,
 * or property type that introduces an unlisted class fails here instead of
 * during composer install in CI.
 */
it('round-trips every registered resource\'s metadata through the api-platform cache store', function (): void {
    $cacheStore = Cache::store(config('api-platform.cache'));
    $resourceNames = app(ResourceNameCollectionFactoryInterface::class)->create();
    $resourceMetadataFactory = app(ResourceMetadataCollectionFactoryInterface::class);

    expect(iterator_to_array($resourceNames))->not->toBeEmpty();

    foreach ($resourceNames as $resourceClass) {
        $cacheStore->forget($resourceClass);

        // First call is a cache miss: computes and writes the serialized value.
        $resourceMetadataFactory->create($resourceClass);

        // Second call is a cache hit: reads the value back from disk through
        // unserialize(['allowed_classes' => ...]), the path that breaks when
        // a class in the graph is missing from the allow-list.
        $metadata = $resourceMetadataFactory->create($resourceClass);

        expect($metadata)->toBeInstanceOf(ResourceMetadataCollection::class);

        foreach ($metadata as $apiResource) {
            expect($apiResource)->not->toBeInstanceOf(__PHP_Incomplete_Class::class);

            foreach ($apiResource->getOperations() ?? [] as $operation) {
                expect($operation)->not->toBeInstanceOf(__PHP_Incomplete_Class::class);
            }
        }
    }
});

it('round-trips every registered resource\'s property metadata through the api-platform cache store', function (): void {
    $cacheStore = Cache::store(config('api-platform.cache'));
    $resourceNames = app(ResourceNameCollectionFactoryInterface::class)->create();
    $propertyNameFactory = app(PropertyNameCollectionFactoryInterface::class);
    $propertyMetadataFactory = app(PropertyMetadataFactoryInterface::class);

    foreach ($resourceNames as $resourceClass) {
        $cacheStore->forget($resourceClass);

        $propertyNameFactory->create($resourceClass);
        $propertyNames = $propertyNameFactory->create($resourceClass);

        expect($propertyNames)->toBeInstanceOf(PropertyNameCollection::class);

        foreach ($propertyNames as $property) {
            $key = hash('xxh3', serialize(['resource_class' => $resourceClass, 'property' => $property]));
            $cacheStore->forget($key);

            $propertyMetadataFactory->create($resourceClass, $property);
            $propertyMetadata = $propertyMetadataFactory->create($resourceClass, $property);

            expect($propertyMetadata)->not->toBeInstanceOf(__PHP_Incomplete_Class::class);
        }
    }
});
