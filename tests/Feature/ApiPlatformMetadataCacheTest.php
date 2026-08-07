<?php

declare(strict_types=1);

use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Property\PropertyNameCollection;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceNameCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;

/**
 * api-platform/laravel caches resource and property metadata via
 * Cache::store(config('api-platform.cache'))->rememberForever(), which in
 * production is the app's "file" store. Laravel's cache deserialization
 * hardening (config('cache.serializable_classes')) rejects any class in the
 * cached object graph that isn't explicitly allow-listed, silently turning it
 * into __PHP_Incomplete_Class. The failure then surfaces far from the cause:
 * a perfectly valid GetCollection whose parameter constraint is a broken
 * object, and a 500 from every request that reaches it.
 *
 * These tests replay exactly what the file store does — serialize(), then
 * unserialize() under the configured allow-list — for every resource the app
 * registers, so a new operation, parameter, or property type that introduces
 * an unlisted class fails here instead of in production.
 *
 * The round trip is performed directly rather than through the cache facade on
 * purpose. phpunit.xml forces API_PLATFORM_CACHE_STORE=array, and the array
 * store keeps values in memory without serializing them, so exercising the
 * configured store would assert nothing at all. That is not hypothetical: it is
 * why Illuminate\Validation\Rules\In (Rule::in() on any sort parameter, from
 * OrderFilter's asc/desc enum) reached production with these tests green.
 */

/**
 * Round-trips $value the way Illuminate\Cache\FileStore does.
 */
function throughSerializableClassesAllowList(mixed $value): mixed
{
    return unserialize(serialize($value), [
        'allowed_classes' => config('cache.serializable_classes'),
    ]);
}

/**
 * Names every class in $value's object graph that came back as
 * __PHP_Incomplete_Class, as "path => ClassName".
 *
 * The whole graph has to be walked, not just the outermost object: unserialize()
 * applies the allow-list at every depth, so the outer ResourceMetadataCollection
 * can restore cleanly while a constraint five levels down is broken.
 *
 * @return array<string, string>
 */
function incompleteClassesIn(mixed $value, string $path, ?SplObjectStorage $seen = null): array
{
    $seen ??= new SplObjectStorage();
    $found = [];

    if (is_object($value)) {
        if ($seen->contains($value)) {
            return [];
        }
        $seen->attach($value);

        if ($value instanceof __PHP_Incomplete_Class) {
            return [$path => ((array) $value)['__PHP_Incomplete_Class_Name'] ?? 'unknown'];
        }
    }

    if (is_object($value) || is_array($value)) {
        foreach ((array) $value as $key => $child) {
            if (is_object($child) || is_array($child)) {
                // Non-public property names carry NUL-delimited scope prefixes
                // once cast to array; trim them so the path stays readable.
                $name = is_string($key) ? mb_trim(str_replace("\0", ' ', $key)) : $key;
                $found += incompleteClassesIn($child, $path . '->' . $name, $seen);
            }
        }
    }

    return $found;
}

/**
 * @param  array<string, string>  $incomplete
 */
function allowListFailureMessage(string $subject, array $incomplete): string
{
    return sprintf(
        "%s: add these to config('cache.serializable_classes'):\n%s",
        $subject,
        implode("\n", array_map(
            static fn(string $path, string $class): string => "  {$class} at {$path}",
            array_keys($incomplete),
            $incomplete,
        )),
    );
}

it('restores every registered resource\'s metadata under the cache allow-list', function (): void {
    $resourceNames = app(ResourceNameCollectionFactoryInterface::class)->create();
    $resourceMetadataFactory = app(ResourceMetadataCollectionFactoryInterface::class);

    expect(iterator_to_array($resourceNames))->not->toBeEmpty();

    foreach ($resourceNames as $resourceClass) {
        $metadata = $resourceMetadataFactory->create($resourceClass);

        expect($metadata)->toBeInstanceOf(ResourceMetadataCollection::class);

        $restored = throughSerializableClassesAllowList($metadata);
        $incomplete = incompleteClassesIn($restored, $resourceClass);

        expect($incomplete)->toBe([], allowListFailureMessage($resourceClass, $incomplete));
    }
});

it('restores every registered resource\'s property metadata under the cache allow-list', function (): void {
    $resourceNames = app(ResourceNameCollectionFactoryInterface::class)->create();
    $propertyNameFactory = app(PropertyNameCollectionFactoryInterface::class);
    $propertyMetadataFactory = app(PropertyMetadataFactoryInterface::class);

    foreach ($resourceNames as $resourceClass) {
        $propertyNames = $propertyNameFactory->create($resourceClass);

        expect($propertyNames)->toBeInstanceOf(PropertyNameCollection::class);

        foreach ($propertyNames as $property) {
            $subject = "{$resourceClass}::{$property}";
            $restored = throughSerializableClassesAllowList(
                $propertyMetadataFactory->create($resourceClass, $property),
            );
            $incomplete = incompleteClassesIn($restored, $subject);

            expect($incomplete)->toBe([], allowListFailureMessage($subject, $incomplete));
        }
    }
});
