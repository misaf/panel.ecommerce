<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('api')->group(function (): void {
    JsonApiRoute::server('vendra-custom-page')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('custom-page-categories', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasMany('customPages')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });

        $server->resource('custom-pages', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasOne('customPageCategory')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });
    });
});
