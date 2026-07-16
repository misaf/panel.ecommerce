<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('api')->group(function (): void {
    JsonApiRoute::server('vendra-product')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('product-categories', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasMany('products')->readOnly();
                $relations->hasMany('productPrices')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });

        $server->resource('products', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasOne('productCategory')->readOnly();
                $relations->hasMany('productPrices')->readOnly();
                $relations->hasOne('latestProductPrice')->readOnly();
                $relations->hasOne('oldestProductPrice')->readOnly();
                $relations->hasMany('multimedia')->readOnly();

                if (class_exists('Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues\AttributeValueSchema')) {
                    $relations->hasMany('attributeValues')->readOnly();
                }
            });

        $server->resource('product-prices', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasOne('product')->readOnly();
            });
    });
});
