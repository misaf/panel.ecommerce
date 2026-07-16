<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('api')->group(function (): void {
    JsonApiRoute::server('vendra-cart')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('carts', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasMany('items')->readOnly();
            });

    });
});
