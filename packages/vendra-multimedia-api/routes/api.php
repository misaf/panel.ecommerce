<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware('api')->group(function (): void {
    JsonApiRoute::server('vendra-multimedia')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('multimedia', JsonApiController::class)
            ->readOnly();
    });
});
