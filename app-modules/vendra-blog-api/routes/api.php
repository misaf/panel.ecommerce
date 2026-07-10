<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::middleware(['api', 'vendra.locale'])->group(function (): void {
    JsonApiRoute::server('vendra-blog')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
        $server->resource('blog-post-categories', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasMany('blogPosts')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });

        $server->resource('blog-posts', JsonApiController::class)
            ->readOnly()
            ->relationships(function (Relationships $relations): void {
                $relations->hasOne('blogPostCategory')->readOnly();
                $relations->hasMany('multimedia')->readOnly();
            });
    });
});
