<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Tests\Fixtures\AttributableRecord;

beforeEach(function (): void {
    Schema::create('attributable_records', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
});

it('attaches reusable attribute values to any model', function (): void {
    $attribute = Attribute::factory()->create([
        'name' => 'Weight',
        'unit' => 'kg',
    ]);
    $record = AttributableRecord::query()->create(['name' => 'Example']);

    $value = $record->attributeValues()->create([
        'attribute_id' => $attribute->id,
        'value'        => '1.2',
    ]);

    expect($record->attributeValues()->sole()->is($value))->toBeTrue()
        ->and($value->attribute->is($attribute))->toBeTrue()
        ->and($value->attributable->is($record))->toBeTrue();
});
