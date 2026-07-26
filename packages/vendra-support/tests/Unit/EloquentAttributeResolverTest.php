<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\EloquentAttributeResolver;

it('provides enabled attribute options and the value model', function (): void {
    Schema::create('support_test_attributes', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('unit')->nullable();
        $table->unsignedBigInteger('position')->default(0);
        $table->boolean('active')->default(false);
    });

    SupportTestAttribute::query()->insert([
        ['name' => 'Material', 'unit' => null, 'position' => 1, 'active' => true],
        ['name' => 'Weight', 'unit' => 'kg', 'position' => 2, 'active' => true],
        ['name' => 'Warranty', 'unit' => 'month', 'position' => 3, 'active' => false],
    ]);

    $resolver = new EloquentAttributeResolver(
        SupportTestAttribute::class,
        SupportTestResolvedAttributeValue::class,
    );

    expect($resolver->available())->toBeTrue()
        ->and($resolver->valueModel())->toBe(SupportTestResolvedAttributeValue::class)
        ->and($resolver->options())->toBe([
            2 => 'Weight (kg)',
            1 => 'Material',
        ]);
});
