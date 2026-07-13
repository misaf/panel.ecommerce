<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;

/**
 * Records activity for any model implementing {@see ShouldLogActivity}.
 *
 * This listener is bound to the wildcard Eloquent lifecycle events by
 * {@see \Misaf\VendraActivityLog\Providers\ActivityLogServiceProvider}, which
 * means models opt into logging purely by implementing the marker contract and
 * never need to depend on this package directly. It mirrors the previous
 * Spatie `LogsActivity` behaviour of logging fillable attributes (except `id`).
 */
final class LogModelActivity
{
    /**
     * Attributes that are never logged unless a model narrows the list itself.
     *
     * @var list<string>
     */
    private const array DEFAULT_EXCEPT = ['id'];

    /**
     * Handle a wildcard Eloquent model event (e.g. "eloquent.updated: App\Models\Foo").
     *
     * @param  array<int, mixed>  $payload
     */
    public function handle(string $eventName, array $payload): void
    {
        $model = $payload[0] ?? null;

        if ( ! $model instanceof Model || ! $model instanceof ShouldLogActivity) {
            return;
        }

        if ( ! config('activitylog.enabled', true)) {
            return;
        }

        $event = $this->eventFrom($eventName);

        $attributes = $this->loggableAttributes($model);

        if ([] === $attributes) {
            return;
        }

        activity()
            ->performedOn($model)
            ->event($event)
            ->withProperties($this->properties($event, $model, $attributes))
            ->log($event);
    }

    /**
     * Extract the bare event name ("updated") from the full event ("eloquent.updated: Foo").
     */
    private function eventFrom(string $eventName): string
    {
        return Str::of($eventName)
            ->after('eloquent.')
            ->before(':')
            ->trim()
            ->value();
    }

    /**
     * @return list<string>
     */
    private function loggableAttributes(Model $model): array
    {
        return array_values(array_diff($model->getFillable(), $this->exceptFor($model)));
    }

    /**
     * @param  list<string>  $attributes
     * @return array<string, array<string, mixed>>
     */
    private function properties(string $event, Model $model, array $attributes): array
    {
        $new = [];

        foreach ($attributes as $attribute) {
            $new[$attribute] = $model->getAttribute($attribute);
        }

        if ('updated' === $event) {
            $old = [];

            foreach ($attributes as $attribute) {
                $old[$attribute] = $model->getOriginal($attribute);
            }

            return ['attributes' => $new, 'old' => $old];
        }

        if ('deleted' === $event) {
            return ['attributes' => $new, 'old' => $new];
        }

        return ['attributes' => $new];
    }

    /**
     * @return list<string>
     */
    private function exceptFor(Model $model): array
    {
        if (method_exists($model, 'activityLogExcept')) {
            /** @var list<string> $except */
            $except = $model->activityLogExcept();

            return $except;
        }

        return self::DEFAULT_EXCEPT;
    }
}
