<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Tests\Unit;

use Misaf\VendraSupport\Support\TagIntegration;
use Misaf\VendraTagger\Models\Tagger;

it('provides tag relationship metadata through the support contract', function (): void {
    $relationship = TagIntegration::relationship();

    expect(TagIntegration::isAvailable())->toBeTrue()
        ->and($relationship)->not->toBeNull()
        ->and($relationship?->model)->toBe(Tagger::class)
        ->and($relationship?->morphName)->toBe('taggable')
        ->and($relationship?->table)->toBe('taggables')
        ->and($relationship?->foreignPivotKey)->toBe('taggable_id')
        ->and($relationship?->relatedPivotKey)->toBe('tag_id');
});
