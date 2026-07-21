<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Misaf\VendraProduct\Database\Factories\ProductFactory;
use Misaf\VendraProduct\Filament\Clusters\Resources\Products\Pages\EditProduct;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProduct\Models\ProductPrice;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();

    Filament::getPanel('admin')->plugin(
        SpatieTranslatablePlugin::make()->defaultLocales(['en', 'de']),
    );
});

it('creates a duplicate with a copy suffix', function (): void {
    $product = ProductFactory::new()->create([
        'name' => ['en' => 'T-Shirt', 'de' => 'T-Shirt'],
        'slug' => ['en' => 't-shirt', 'de' => 't-shirt'],
    ]);

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 1500,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate')
        ->assertNotified();

    $duplicate = Product::whereKeyNot($product->getKey())->first();

    expect($duplicate)->not->toBeNull()
        ->and($duplicate->getTranslations('name'))->toBe([
            'en' => 'T-Shirt Copy',
            'de' => 'T-Shirt Copy',
        ])
        ->and($duplicate->getTranslations('slug'))->toBe([
            'en' => 't-shirt-copy',
            'de' => 't-shirt-copy',
        ]);
});

it('increments the suffix when a previous duplicate exists', function (): void {
    $product = ProductFactory::new()->create([
        'name' => ['en' => 'T-Shirt', 'de' => 'T-Shirt'],
        'slug' => ['en' => 't-shirt', 'de' => 't-shirt'],
    ]);

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 1500,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate');

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate');

    $duplicates = Product::whereKeyNot($product->getKey())
        ->orderBy('id')
        ->get();

    expect($duplicates)->toHaveCount(2)
        ->and($duplicates[0]->getTranslations('name'))->toBe([
            'en' => 'T-Shirt Copy',
            'de' => 'T-Shirt Copy',
        ])
        ->and($duplicates[0]->getTranslations('slug'))->toBe([
            'en' => 't-shirt-copy',
            'de' => 't-shirt-copy',
        ])
        ->and($duplicates[1]->getTranslations('name'))->toBe([
            'en' => 'T-Shirt Copy 2',
            'de' => 'T-Shirt Copy 2',
        ])
        ->and($duplicates[1]->getTranslations('slug'))->toBe([
            'en' => 't-shirt-copy-2',
            'de' => 't-shirt-copy-2',
        ]);
});

it('increments the suffix when a product with the copy name already exists', function (): void {
    $product = ProductFactory::new()->create([
        'name' => ['en' => 'T-Shirt', 'de' => 'T-Shirt'],
        'slug' => ['en' => 't-shirt', 'de' => 't-shirt'],
    ]);

    ProductFactory::new()->create([
        'name' => ['en' => 'T-Shirt Copy', 'de' => 'T-Shirt Copy'],
        'slug' => ['en' => 't-shirt-copy', 'de' => 't-shirt-copy'],
    ]);

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 1500,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate')
        ->assertNotified();

    $duplicate = Product::whereKeyNot($product->getKey())
        ->where('name->en', 'T-Shirt Copy 2')
        ->first();

    expect($duplicate)->not->toBeNull()
        ->and($duplicate->getTranslations('slug'))->toBe([
            'en' => 't-shirt-copy-2',
            'de' => 't-shirt-copy-2',
        ]);
});

it('duplicates prices', function (): void {
    $product = ProductFactory::new()->create();

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 2500,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate')
        ->assertNotified();

    $duplicate = Product::whereKeyNot($product->getKey())->first();

    expect($duplicate)->not->toBeNull()
        ->and($duplicate->productPrices()->count())->toBe(1)
        ->and((int) $duplicate->productPrices()->first()->price->getAmount())->toBe(2500);
});

it('duplicates relations without affecting the original record', function (): void {
    $product = ProductFactory::new()->create();

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 1000,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate')
        ->assertNotified();

    $product->refresh();

    expect($product->productPrices()->count())->toBe(1);
});

it('generates a new token for the duplicate', function (): void {
    $product = ProductFactory::new()->create();

    $product->productPrices()->create([
        'currency_code' => ProductPrice::defaultCurrencyCode(),
        'price'         => 1000,
    ]);

    livewire(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('replicate')
        ->assertNotified();

    $duplicate = Product::whereKeyNot($product->getKey())->first();

    expect($duplicate)->not->toBeNull()
        ->and($duplicate->token)->not->toBe($product->token);
});
