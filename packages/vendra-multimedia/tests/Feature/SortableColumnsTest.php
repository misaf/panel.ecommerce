<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Misaf\VendraMultimedia\Filament\Clusters\Resources\Pages\ListMultimedia;
use Misaf\VendraMultimedia\Models\Multimedia;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

function createMultimediaRecord(string $collectionName, int $size): Multimedia
{
    return Multimedia::query()->create([
        'model_type'            => 'test-model',
        'model_id'              => 1,
        'uuid'                  => (string) Str::uuid(),
        'collection_name'       => $collectionName,
        'name'                  => $collectionName,
        'file_name'             => "{$collectionName}.jpg",
        'mime_type'             => 'image/jpeg',
        'disk'                  => 'public',
        'conversions_disk'      => 'public',
        'size'                  => $size,
        'manipulations'         => [],
        'custom_properties'     => [],
        'generated_conversions' => [],
        'responsive_images'     => [],
    ]);
}

it('sorts the multimedia table by every sortable column following the stored values', function (): void {
    $first = createMultimediaRecord('aaa-collection', 100);
    $second = createMultimediaRecord('bbb-collection', 90_000);

    expect(livewire(ListMultimedia::class)->call('loadTable'))
        ->toSortByEverySortableColumn([$first, $second]);
});
