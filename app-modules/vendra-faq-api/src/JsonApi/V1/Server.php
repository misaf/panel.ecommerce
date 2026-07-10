<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;
use Misaf\VendraFaqApi\JsonApi\V1\FaqCategories\FaqCategorySchema;
use Misaf\VendraFaqApi\JsonApi\V1\Faqs\FaqSchema;

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
            FaqCategorySchema::class,
            FaqSchema::class,
        ];
    }
}
