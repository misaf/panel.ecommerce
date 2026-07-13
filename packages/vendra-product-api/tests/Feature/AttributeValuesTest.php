<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('hides attribute-values routes when attribute module is unavailable', function (): void {
    $routes = collect(Route::getRoutes()->getRoutesByName());

    expect($routes->has('vendra-product.products.relationships.attribute-values'))->toBeFalse();
});
