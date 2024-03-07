<?php

declare(strict_types=1);

namespace App\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;

final class Server extends BaseServer
{
    protected string $baseUri = '/api/v1';

    public function serving(): void
    {
        // no-op
    }

    protected function allSchemas(): array
    {
        return [
            ProductCategories\ProductCategorySchema::class,
            Products\ProductSchema::class,
            BlogPostCategories\BlogPostCategorySchema::class,
            BlogPosts\BlogPostSchema::class,
            Multimedia\MultimediaSchema::class,
        ];
    }
}
