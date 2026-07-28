<?php

declare(strict_types=1);

namespace Misaf\VendraApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TResource of object
 *
 * @implements ProviderInterface<TResource>
 */
abstract class EloquentResourceProvider implements ProviderInterface
{
    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $query = $this->query($operation);

        if ($operation instanceof CollectionOperationInterface) {
            $query = $this->filters->apply($query, $uriVariables, $operation, $context);
            $this->applyOrder($query, $operation);

            if (false === $this->pagination->isEnabled($operation, $context)) {
                return $query->get()
                    ->map(fn(Model $model): object => $this->toResource($model, $operation))
                    ->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->setCollection(
                $paginator->getCollection()
                    ->map(fn(Model $model): object => $this->toResource($model, $operation)),
            );

            return new Paginator($paginator);
        }

        $model = $query->whereKey($uriVariables['id'] ?? null)->first();

        return $model instanceof Model ? $this->toResource($model, $operation) : null;
    }

    /**
     * @return Builder<TModel>
     */
    abstract protected function query(Operation $operation): Builder;

    /**
     * @param TModel $model
     *
     * @return TResource
     */
    abstract protected function toResource(Model $model, Operation $operation): object;

    /**
     * @param Builder<TModel> $query
     */
    private function applyOrder(Builder $query, Operation $operation): void
    {
        $order = $operation->getOrder() ?? ['id' => 'DESC'];

        foreach ($order as $property => $direction) {
            if (is_int($property)) {
                $property = $direction;
                $direction = 'ASC';
            }

            $query->orderBy($property, $direction);
        }
    }
}
