<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Support\EloquentCurrencyResolver;

it('provides active currency values from an eloquent model', function (): void {
    Schema::create('support_test_currencies', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('iso_code');
        $table->boolean('is_default')
            ->default(false);
        $table->unsignedBigInteger('position')
            ->default(0);
        $table->boolean('status')
            ->default(false);
    });

    SupportTestCurrency::query()->insert([
        [
            'name'       => 'US Dollar',
            'iso_code'   => 'USD',
            'is_default' => true,
            'position'   => 1,
            'status'     => true,
        ],
        [
            'name'       => 'Euro',
            'iso_code'   => 'EUR',
            'is_default' => false,
            'position'   => 2,
            'status'     => true,
        ],
        [
            'name'       => 'British Pound',
            'iso_code'   => 'GBP',
            'is_default' => false,
            'position'   => 3,
            'status'     => false,
        ],
    ]);

    $resolver = new EloquentCurrencyResolver(SupportTestCurrency::class);

    expect($resolver->available())->toBeTrue()
        ->and($resolver->defaultCode())->toBe('USD')
        ->and($resolver->options())->toBe([
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
        ])
        ->and($resolver->activeCodes())->toBe(['USD', 'EUR']);
});
