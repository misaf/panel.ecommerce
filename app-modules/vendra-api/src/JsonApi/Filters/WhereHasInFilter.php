<?php

declare(strict_types=1);

namespace Misaf\VendraApi\JsonApi\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\DeserializesValue;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasColumn;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasDelimiter;
use LaravelJsonApi\Eloquent\Filters\Concerns\HasRelation;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;
use LaravelJsonApi\Eloquent\Schema;

final class WhereHasInFilter implements Filter
{
    use DeserializesValue;
    use HasColumn;
    use HasDelimiter;
    use HasRelation;
    use IsSingular;

    public function __construct(Schema $schema, string $fieldName, ?string $key = null, ?string $column = null)
    {
        $this->schema = $schema;
        $this->fieldName = $fieldName;
        $this->key = $key;
        $this->column = $column ?: $this->guessColumn();
    }

    public static function make(Schema $schema, string $fieldName, ?string $key = null, ?string $column = null): static
    {
        return new static($schema, $fieldName, $key, $column);
    }

    /**
     * @param Builder<Model> $query
     * @return Builder<Model>
     */
    public function apply($query, $value): Builder
    {
        return $query->whereHas(
            $this->relationName(),
            $this->callback($value),
        );
    }

    protected function callback(mixed $value): Closure
    {
        $first = Arr::first(Arr::wrap($value));
        $value = is_string($first) || is_array($first) ? $first : null;

        return
            /**
             * @param Builder<Model> $query
             */
            function (Builder $query) use ($value): void {
                $query->whereIn(
                    $query->getModel()->qualifyColumn($this->column()),
                    $this->toArray($value),
                );
            };
    }

    private function guessColumn(): string
    {
        return Str::underscore(
            Str::singular($this->key()),
        );
    }
}
