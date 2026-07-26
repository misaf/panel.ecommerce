<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Observers;

use Misaf\VendraSubscription\Models\Plan;

final class PlanObserver
{
    public function creating(Plan $plan): void
    {
        if ( ! $plan->status) {
            $plan->is_default = false;

            return;
        }

        if ( ! Plan::query()->enabled()->exists()) {
            $plan->is_default = true;
        }
    }

    public function saving(Plan $plan): void
    {
        if ( ! $plan->status) {
            $plan->is_default = false;

            return;
        }

        if ($plan->is_default) {
            Plan::query()
                ->where('is_default', true)
                ->whereKeyNot($plan->getKey())
                ->update(['is_default' => false]);

            return;
        }

        if ($plan->exists && true === $plan->getOriginal('is_default')) {
            $hasAnotherDefault = Plan::query()
                ->enabled()
                ->where('is_default', true)
                ->whereKeyNot($plan->getKey())
                ->exists();

            if ( ! $hasAnotherDefault) {
                $plan->is_default = true;
            }
        }
    }

    public function saved(Plan $plan): void
    {
        if ($plan->wasChanged(['status', 'is_default'])) {
            $this->ensureEnabledDefault();
        }
    }

    public function deleted(Plan $plan): void
    {
        if ( ! $plan->is_default) {
            return;
        }

        $this->ensureEnabledDefault();
    }

    private function ensureEnabledDefault(): void
    {
        if (Plan::query()->enabled()->where('is_default', true)->exists()) {
            return;
        }

        Plan::query()
            ->enabled()
            ->orderBy('id')
            ->first()
            ?->update(['is_default' => true]);
    }
}
