<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraBlogApi\JsonApi\V1\BlogPostCategories\BlogPostCategorySchema;
use Misaf\VendraBlogApi\JsonApi\V1\BlogPosts\BlogPostSchema;
use Misaf\VendraMultimediaApi\JsonApi\V1\Multimedia\MultimediaSchema;

final class Server extends BaseServer
{
    protected string $baseUri = '/v1';

    public function authorizable(): bool
    {
        return false;
    }

    /**
     * @return list<class-string>
     */
    public function allSchemas(): array
    {
        return [
            BlogPostCategorySchema::class,
            BlogPostSchema::class,
            MultimediaSchema::class,
        ];
    }
}
