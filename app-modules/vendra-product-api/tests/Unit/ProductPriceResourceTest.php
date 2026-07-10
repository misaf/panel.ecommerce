<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use LaravelJsonApi\Contracts\Schema\Schema;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraProductApi\JsonApi\V1\ProductPrices\ProductPriceResource;

it('serializes product price as raw integer and structured money', function (): void {
    $app = new Container();
    $config = new Repository([
        'app'   => ['currency' => 'USD', 'locale' => 'en_US'],
    ]);

    $app->instance('config', $config);

    Container::setInstance($app);
    Facade::setFacadeApplication($app);

    $config->set('money', require getcwd() . '/vendor/cknow/laravel-money/config/config.php');

    $productPrice = new ProductPrice();
    $productPrice->setRawAttributes([
        'currency_code' => 'USD',
        'price'         => 12345,
    ], true);

    $attributes = iterator_to_array(
        (new ProductPriceResource(Mockery::mock(Schema::class), $productPrice))
            ->attributes(Request::create('/'))
    );

    expect($attributes['price'])->toBe(12345)
        ->and($attributes['money'])->toMatchArray([
            'amount'   => 12345,
            'currency' => 'USD',
        ])
        ->and($attributes['money']['formatted'])->toBeString()->not->toBeEmpty();
});
