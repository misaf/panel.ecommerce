<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\ActivatePaidSubscriptionAction;
use App\Actions\ChargeSubscriptionAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Models\SubscriptionPayment;
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Throwable;

final class ProcessSubscriptionPayment implements NotTenantAware, ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public int $timeout = 30;

    public int $uniqueFor = 600;

    public function __construct(public readonly int $paymentId) {}

    public function handle(
        ChargeSubscriptionAction $chargeSubscriptionAction,
        ActivatePaidSubscriptionAction $activatePaidSubscriptionAction,
    ): void {
        $payment = SubscriptionPayment::query()->find($this->paymentId);

        if ( ! $payment instanceof SubscriptionPayment) {
            return;
        }

        if (SubscriptionPaymentStatus::Paid === $payment->status) {
            $activatePaidSubscriptionAction->execute($payment);

            return;
        }

        $chargeSubscriptionAction->execute($payment);
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [5, 30, 120, 300];
    }

    public function uniqueId(): string
    {
        return (string) $this->paymentId;
    }

    public function failed(?Throwable $exception): void
    {
        DB::transaction(function () use ($exception): void {
            $payment = SubscriptionPayment::query()
                ->whereKey($this->paymentId)
                ->lockForUpdate()
                ->first();

            if ( ! $payment instanceof SubscriptionPayment || $payment->status->isTerminal()) {
                return;
            }

            $payment->forceFill([
                'status'          => SubscriptionPaymentStatus::NeedsReconciliation,
                'failure_code'    => 'processing_exhausted',
                'failure_message' => Str::limit($exception?->getMessage() ?? 'Subscription payment processing exhausted its retries.', 1_000),
                'next_retry_at'   => now()->addMinutes(15),
            ])->save();
        });
    }
}
