<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Contracts\CurrencyResolver;
use Misaf\VendraSupport\Support\EloquentCurrencyResolver;

it('binds the shared currency resolver contract', function (): void {
    expect(app(CurrencyResolver::class))->toBeInstanceOf(EloquentCurrencyResolver::class);
});

it('provides active currency options through the shared resolver', function (): void {
    Schema::create('currencies', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('iso_code');
        $table->boolean('is_default')
            ->default(false);
        $table->unsignedBigInteger('position')
            ->default(0);
        $table->boolean('status')
            ->default(false);
        $table->timestamp('deleted_at')
            ->nullable();
    });

    DB::table('currencies')->insert([
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

    $resolver = app(CurrencyResolver::class);

    expect($resolver->available())->toBeTrue()
        ->and($resolver->defaultCode())->toBe('USD')
        ->and($resolver->options())->toBe([
            'EUR' => 'Euro',
            'USD' => 'US Dollar',
        ])
        ->and($resolver->activeCodes())->toBe(['USD', 'EUR']);
});
