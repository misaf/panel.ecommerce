<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware(['api', 'vendra.locale'])->group(function (): void {
    JsonApiRoute::server('vendra-faq')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('faq-categories', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasMany('faqs')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });

        $server->resource('faqs', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasOne('faqCategory')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });
    });
});
