<?php

declare(strict_types=1);

namespace Misaf\VendraApi\JsonApi\Sorting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Eloquent\Contracts\SortField;

final class RandomPositionSort implements SortField
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    /**
     * @param Builder<Model> $query
     * @return Builder<Model>
     */
    public function sort($query, string $direction = 'asc'): Builder
    {
        $query->inRandomOrder();

        return $query;
    }

    public function sortField(): string
    {
        return $this->name;
    }
}
