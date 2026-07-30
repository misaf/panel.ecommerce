<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use ApiPlatform\Laravel\Eloquent\State\LinksHandlerInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraBlog\Models\BlogPost;

/**
 * @implements LinksHandlerInterface<BlogPost>
 */
final class BlogPostLinksHandler implements LinksHandlerInterface
{
    /**
     * @param Builder<BlogPost> $builder
     *
     * @return Builder<BlogPost>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with([
                'blogPostCategory:id,name,slug,description,position,active,created_at,updated_at',
                'multimedia',
            ])
            ->whereHas('blogPostCategory', fn(Builder $query): Builder => $query->where('active', true))
            ->where('active', true);

        if ( ! ($context['operation'] ?? null) instanceof CollectionOperationInterface) {
            $mcpData = $context['mcp_data'] ?? [];
            $builder->whereKey($uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null));
        }

        return $builder;
    }
}
